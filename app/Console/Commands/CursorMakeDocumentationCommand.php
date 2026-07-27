<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class CursorMakeDocumentationCommand extends Command
{
    protected $signature = 'cursor:make:documentation
        {url : URL de una página de documentación (ej. https://livewire.laravel.com/docs/4.x/quickstart)}
        {--prefix= : Prefijo / carpeta de skills (auto si se omite; ej. livewire-4x, flux, chartjs)}
        {--output=.cursor/skills : Raíz de skills; se crea .cursor/skills/{prefix}/}
        {--flat : Escribir en --output sin subcarpeta por prefijo (legacy)}
        {--scope=nav : nav=menú del sitio; base=solo path base de la URL}
        {--limit=0 : Limitar cantidad de páginas (0 = todas)}
        {--dry-run : Solo listar URLs descubiertas sin escribir archivos}
        {--no-crawl : Solo semilla + bundles SPA (sin BFS de enlaces)}
        {--pause=0.35 : Pausa entre requests en segundos}';

    protected $description = 'Exporta documentación web a .cursor/skills/{prefix}/*.mdc descubriendo el índice desde la URL dada';

    public function handle(): int
    {
        $url = trim((string) $this->argument('url'));

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error('La URL no es válida.');

            return self::FAILURE;
        }

        $scriptRoot = base_path('scripts/cursor-docs-export');
        $pythonScript = $scriptRoot.DIRECTORY_SEPARATOR.'export_docs.py';
        $requirements = $scriptRoot.DIRECTORY_SEPARATOR.'requirements.txt';

        if (! is_dir($scriptRoot)) {
            $this->error("No se encontró el directorio de scripts: {$scriptRoot}");

            return self::FAILURE;
        }

        if (! is_file($pythonScript)) {
            $this->error("No se encontró el exportador: {$pythonScript}");

            return self::FAILURE;
        }

        if (! is_file($requirements)) {
            $this->error("No se encontró requirements.txt: {$requirements}");

            return self::FAILURE;
        }

        $venvPython = $this->ensurePythonEnvironment($scriptRoot, $requirements);

        if ($venvPython === null) {
            return self::FAILURE;
        }

        $output = $this->resolveOutputPath((string) $this->option('output'));
        $prefix = trim((string) $this->option('prefix'));
        $scope = (string) $this->option('scope');
        $limit = (int) $this->option('limit');
        $pause = (string) $this->option('pause');

        if (! in_array($scope, ['nav', 'base'], true)) {
            $this->error('El scope debe ser "nav" o "base".');

            return self::FAILURE;
        }

        $command = [
            $venvPython,
            $pythonScript,
            $url,
            '--output='.$output,
            '--scope='.$scope,
            '--limit='.(string) $limit,
            '--pause='.$pause,
        ];

        if ($prefix !== '') {
            $command[] = '--prefix='.$prefix;
        }

        if ($this->option('dry-run')) {
            $command[] = '--dry-run';
        }

        if ($this->option('no-crawl')) {
            $command[] = '--no-crawl';
        }

        if ($this->option('flat')) {
            $command[] = '--flat';
        }

        $this->info('Exportando documentación Cursor…');
        $this->line('URL: '.$url);
        $this->line('Raíz skills: '.$output);
        if ($prefix !== '') {
            $folder = $this->option('flat')
                ? $output
                : rtrim($output, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$prefix;
            $this->line('Prefijo/carpeta: '.$prefix);
            $this->line('Salida efectiva: '.$folder);
        } else {
            $this->line('Prefijo/carpeta: (auto desde URL)');
        }
        $this->newLine();

        $result = Process::path(base_path())
            ->timeout(60 * 30)
            ->env([
                'PYTHONUNBUFFERED' => '1',
            ])
            ->run($command, function (string $type, string $outputChunk): void {
                $this->output->write($outputChunk);
            });

        if (! $result->successful()) {
            $this->newLine();
            $this->error('La exportación falló (exit '.$result->exitCode().').');

            if (filled($result->errorOutput())) {
                $this->line($result->errorOutput());
            }

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Documentación exportada correctamente.');

        return self::SUCCESS;
    }

    private function ensurePythonEnvironment(string $scriptRoot, string $requirements): ?string
    {
        if (! $this->commandExists('python3')) {
            $this->error('python3 no está disponible en el PATH. Instálalo en WSL/Debian e inténtalo de nuevo.');

            return null;
        }

        $venvDir = $scriptRoot.DIRECTORY_SEPARATOR.'.venv';
        $venvPython = $this->resolveVenvPython($scriptRoot);
        $venvUsable = $venvPython !== null
            && $this->pythonWorks($venvPython, $scriptRoot)
            && $this->pipWorks($venvPython, $scriptRoot);

        if (! $venvUsable) {
            if (is_dir($venvDir)) {
                $this->warn('El venv existente no es usable (python/pip); se recreará.');
                File::deleteDirectory($venvDir);
            }

            $this->line('Creando entorno virtual Python en scripts/cursor-docs-export/.venv …');

            $create = Process::path($scriptRoot)
                ->timeout(120)
                ->run(['python3', '-m', 'venv', '--upgrade-deps', '.venv']);

            if (! $create->successful()) {
                // Debian a veces no trae ensurepip; reintentar sin --upgrade-deps.
                $create = Process::path($scriptRoot)
                    ->timeout(120)
                    ->run(['python3', '-m', 'venv', '.venv']);
            }

            $venvPython = $this->resolveVenvPython($scriptRoot);

            if (! $create->successful() || $venvPython === null) {
                if (is_dir($venvDir)) {
                    File::deleteDirectory($venvDir);
                }

                $this->error('No se pudo crear el venv (falta python3-venv / ensurepip).');
                $this->line($create->errorOutput() ?: $create->output());
                $this->newLine();
                $this->warn('En Laravel Sail (sin sudo) instala como root:');
                $this->line('./vendor/bin/sail root-shell');
                $this->line('apt-get update && apt-get install -y python3 python3-venv python3-pip curl');
                $this->line('exit');
                $this->line('rm -rf scripts/cursor-docs-export/.venv');
                $this->line('sail php artisan cursor:make:documentation "…"');

                return null;
            }
        }

        if (! $this->pipWorks($venvPython, $scriptRoot)) {
            $this->line('Bootstrap de pip en el venv (ensurepip)…');

            if (! $this->bootstrapPip($venvPython, $scriptRoot)) {
                $this->error('El venv no tiene pip y no se pudo instalar.');
                $this->line('En Sail/Debian: apt-get install -y python3-venv python3-pip');
                $this->line('Luego borra scripts/cursor-docs-export/.venv y vuelve a ejecutar el comando.');

                return null;
            }
        }

        $this->line('Instalando dependencias Python…');

        $pip = Process::path($scriptRoot)
            ->timeout(300)
            ->run([
                $venvPython,
                '-m',
                'pip',
                'install',
                '--upgrade',
                'pip',
                '--quiet',
            ]);

        if (! $pip->successful()) {
            $this->warn('No se pudo actualizar pip; se continúa con la versión actual.');
            $this->line($pip->errorOutput() ?: $pip->output());
        }

        $deps = Process::path($scriptRoot)
            ->timeout(300)
            ->run([
                $venvPython,
                '-m',
                'pip',
                'install',
                '-r',
                $requirements,
                '--quiet',
            ]);

        if (! $deps->successful()) {
            $this->error('No se pudieron instalar las dependencias de requirements.txt.');
            $this->line($deps->errorOutput() ?: $deps->output());

            return null;
        }

        $probeImports = Process::path($scriptRoot)
            ->timeout(30)
            ->run([
                $venvPython,
                '-c',
                'import requests, bs4, markdownify; print("ok")',
            ]);

        if (! $probeImports->successful()) {
            $this->error('El venv no puede importar requests/bs4/markdownify.');
            $this->line($probeImports->errorOutput() ?: $probeImports->output());

            return null;
        }

        return $venvPython;
    }

    private function resolveVenvPython(string $scriptRoot): ?string
    {
        $candidates = [
            $scriptRoot.DIRECTORY_SEPARATOR.'.venv'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'python3',
            $scriptRoot.DIRECTORY_SEPARATOR.'.venv'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'python',
            $scriptRoot.DIRECTORY_SEPARATOR.'.venv'.DIRECTORY_SEPARATOR.'Scripts'.DIRECTORY_SEPARATOR.'python.exe',
            $scriptRoot.DIRECTORY_SEPARATOR.'.venv'.DIRECTORY_SEPARATOR.'Scripts'.DIRECTORY_SEPARATOR.'python',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function pythonWorks(string $python, string $scriptRoot): bool
    {
        $probe = Process::path($scriptRoot)
            ->timeout(30)
            ->run([$python, '-c', 'import sys; print(sys.executable)']);

        return $probe->successful();
    }

    private function pipWorks(string $python, string $scriptRoot): bool
    {
        $probe = Process::path($scriptRoot)
            ->timeout(30)
            ->run([$python, '-c', 'import pip; print(pip.__version__)']);

        return $probe->successful();
    }

    private function bootstrapPip(string $python, string $scriptRoot): bool
    {
        $ensure = Process::path($scriptRoot)
            ->timeout(120)
            ->run([$python, '-m', 'ensurepip', '--upgrade']);

        if ($ensure->successful() && $this->pipWorks($python, $scriptRoot)) {
            return true;
        }

        // Fallback: get-pip.py
        $getPip = $scriptRoot.DIRECTORY_SEPARATOR.'get-pip.py';
        $download = Process::path($scriptRoot)
            ->timeout(120)
            ->run([
                'bash',
                '-lc',
                'curl -fsSL https://bootstrap.pypa.io/get-pip.py -o '.escapeshellarg($getPip),
            ]);

        if (! $download->successful() || ! is_file($getPip)) {
            return false;
        }

        $install = Process::path($scriptRoot)
            ->timeout(180)
            ->run([$python, $getPip, '--force-reinstall']);

        @unlink($getPip);

        return $install->successful() && $this->pipWorks($python, $scriptRoot);
    }

    private function resolveOutputPath(string $output): string
    {
        if (Str::startsWith($output, ['/', '\\']) || preg_match('/^[A-Za-z]:[\\\\\\/]/', $output) === 1) {
            return rtrim($output, DIRECTORY_SEPARATOR);
        }

        if (Str::startsWith($output, '~')) {
            $home = rtrim((string) ($_SERVER['HOME'] ?? getenv('HOME') ?: ''), DIRECTORY_SEPARATOR);

            return rtrim($home.DIRECTORY_SEPARATOR.ltrim(substr($output, 1), '/\\'), DIRECTORY_SEPARATOR);
        }

        return base_path(trim($output, '/\\'));
    }

    private function commandExists(string $command): bool
    {
        $which = Process::run(['bash', '-lc', 'command -v '.escapeshellarg($command)]);

        if ($which->successful() && trim($which->output()) !== '') {
            return true;
        }

        $fallback = Process::run([$command, '--version']);

        return $fallback->successful();
    }
}
