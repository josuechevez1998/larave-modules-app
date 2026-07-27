<?php

namespace App\Console\Commands;

use Ibex\CrudGenerator\Commands\CrudGenerator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

/**
 * Extiende ibex make:crud.
 *
 * - Sin --module → genera en App\ (comportamiento raíz).
 * - Con --module=Blog → genera en Modules/Blog (Livewire, API o blade).
 */
class MakeCrudCommand extends CrudGenerator
{
    protected $signature = 'make:crud
                            {name : Table name}
                            {stack : The development stack that should be installed (bootstrap,tailwind,livewire,api)}
                            {--route= : Custom route name}
                            {--module= : Module name (e.g. Blog). Omit to generate under App\\}';

    protected $description = 'Create Laravel CRUD (App root, or a nwidart module with --module=)';

    protected ?string $moduleStudly = null;

    protected string $serviceNamespace = 'App\\Services';

    protected string $moduleLower = '';

    /**
     * @var array<string, array{
     *     relation: string,
     *     table: string,
     *     class: string,
     *     model: string,
     *     label: string,
     *     label_mode: string
     * }>|null
     */
    protected ?array $foreignKeysMap = null;

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $moduleOption = trim((string) $this->option('module'));

        if ($moduleOption !== '') {
            $this->moduleStudly = Str::studly($moduleOption);
            $this->moduleLower = Str::lower($this->moduleStudly);

            if (! $this->moduleExists($this->moduleStudly)) {
                $this->error("El módulo `{$this->moduleStudly}` no existe. Crea primero: php artisan module:make {$this->moduleStudly}");

                return self::FAILURE;
            }

            $this->applyModuleContext();
            $this->ensureModuleHttpController();
            $this->info("CRUD → módulo {$this->moduleStudly}");
        } else {
            $this->serviceNamespace = config('crud.service.namespace', $this->serviceNamespace);
            $this->info('CRUD → App\\ (raíz)');
        }

        $result = parent::handle();

        if ($result === false) {
            return self::FAILURE;
        }

        $this->buildService();
        $this->writeBreadcrumbs();

        return self::SUCCESS;
    }

    protected function buildOptions(): static
    {
        $this->options['route'] = $this->option('route') ?: null;
        $this->options['stack'] = $this->argument('stack');

        return $this;
    }

    protected function applyModuleContext(): void
    {
        $base = 'Modules\\'.$this->moduleStudly;

        $this->modelNamespace = $base.'\\Models';
        $this->controllerNamespace = $base.'\\Http\\Controllers';
        $this->apiControllerNamespace = $base.'\\Http\\Controllers\\Api';
        $this->resourceNamespace = $base.'\\Http\\Resources';
        $this->requestNamespace = $base.'\\Http\\Requests';
        $this->livewireNamespace = $base.'\\Livewire';
        $this->serviceNamespace = $base.'\\Services';
        // Vista Blade del componente de layout del módulo (no dispara install de starter kits).
        $this->layout = 'components.layouts.app';
    }

    /**
     * Nunca instalar breeze/starter-kit: el proyecto ya tiene layouts Flux.
     */
    protected function buildLayout(): void
    {
        // no-op
    }

    /**
     * @throws FileNotFoundException
     * @throws \Exception
     */
    protected function buildViews(): static
    {
        if ($this->options['stack'] == 'api') {
            return $this;
        }

        $this->info('Creating Views ...');

        $tableHead = "\n";
        $tableBody = "\n";
        $viewRows = "\n";
        $form = "\n";
        $filters = "\n";

        foreach ($this->getFilteredColumns() as $column) {
            $title = Str::title(str_replace('_', ' ', $column));

            $tableHead .= $this->getHead($title);
            $tableBody .= $this->getBody($column);
            $viewRows .= $this->getField($title, $column, 'view-field');
            $form .= $this->getField($title, $column);

            if ($this->options['stack'] === 'livewire') {
                $filters .= $this->isForeignKeyColumn($column)
                    ? $this->getField($title, $column, 'filter-select')
                    : $this->getField($title, $column, 'filter-text');
            }
        }

        $filtersBar = '';
        if ($this->options['stack'] === 'livewire' && trim($filters) !== '') {
            $filtersBar = <<<BLADE
    <div class="flex flex-wrap items-end gap-4">
{$filters}
        <div class="pb-1">
            <x-ui.clear-filters />
        </div>
    </div>

BLADE;
        }

        $replace = array_merge($this->buildReplacements(), $this->modelReplacements(), [
            '{{tableHeader}}' => $tableHead,
            '{{tableBody}}' => $tableBody,
            '{{viewRows}}' => $viewRows,
            '{{form}}' => $form,
            '{{filtersBar}}' => $filtersBar,
        ]);

        $this->buildLayout();

        foreach (['index', 'create', 'edit', 'form', 'show'] as $view) {
            $viewTemplate = str_replace(
                array_keys($replace),
                array_values($replace),
                $this->getStub($this->viewStubPath($view))
            );

            $this->write($this->_getViewPath($view), $viewTemplate);
        }

        return $this;
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getField(string $title, string $column, string $type = 'form-field'): string
    {
        if ($type === 'form-field'
            && $this->options['stack'] === 'livewire'
            && $this->isForeignKeyColumn($column)
        ) {
            $type = 'form-field-select';
        }

        $fk = $this->getForeignKeysMap()[$column] ?? null;

        $replace = array_merge($this->buildReplacements(), [
            '{{title}}' => $title,
            '{{column}}' => $column,
            '{{column_snake}}' => Str::snake($column),
            '{{relation}}' => $fk['relation'] ?? Str::camel(Str::beforeLast($column, '_id')),
        ]);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->getStub($this->viewStubPath($type))
        );
    }

    /**
     * Custom stub_path + livewire → usar carpeta 12/ (Laravel 12), no views/livewire/{view}.
     */
    protected function viewStubPath(string $view): string
    {
        return match ($this->options['stack']) {
            'livewire' => $this->isLaravel12()
                ? "views/livewire/12/{$view}"
                : "views/livewire/default/{$view}",
            default => "views/{$this->options['stack']}/{$view}",
        };
    }

    protected function buildReplacements(): array
    {
        $modelView = Str::kebab($this->name);
        $viewPrefix = $this->moduleStudly
            ? $this->moduleLower.'::livewire.'.$modelView
            : 'livewire.'.$modelView;

        $base = array_merge(parent::buildReplacements(), [
            '{{serviceNamespace}}' => $this->serviceNamespace,
            '{{model}}' => $this->name,
            '{{modelViewFull}}' => $viewPrefix,
        ]);

        // Incluir siempre (también en buildLivewire) para que stubs no dejen {{catalog*}} sin sustituir.
        if ($this->table && $this->name) {
            return array_merge($base, $this->filterCatalogReplacements());
        }

        return array_merge($base, $this->emptyFilterCatalogReplacements());
    }

    protected function modelReplacements(): array
    {
        $replace = parent::modelReplacements();
        $fkMap = $this->getForeignKeysMap();

        $rules = $replace['{{rules}}'] ?? '';
        $formProps = $replace['{{livewireFormProperties}}'] ?? '';

        foreach ($fkMap as $column => $meta) {
            $exists = 'exists:'.$meta['table'].','.$meta['owner_key'];
            $nullable = $this->columnIsNullable($column);
            $rule = ($nullable ? 'nullable' : 'required').'|integer|'.$exists;
            $rules = preg_replace(
                "/'".preg_quote($column, '/')."' => '[^']*'/",
                "'{$column}' => '{$rule}'",
                $rules
            ) ?? $rules;

            $formProps = preg_replace(
                '/public \$'.preg_quote($column, '/')." = '';/",
                'public $'.$column.' = null;',
                $formProps
            ) ?? $formProps;
        }

        $replace['{{rules}}'] = $rules;
        $replace['{{livewireFormProperties}}'] = $formProps;

        // filterCatalog ya viene de buildReplacements(); merge de nuevo es idempotente.
        return array_merge($replace, $this->filterCatalogReplacements());
    }

    /**
     * @return array<string, string>
     */
    protected function emptyFilterCatalogReplacements(): array
    {
        return [
            '{{catalogImports}}' => '',
            '{{catalogMethods}}' => '',
            '{{filterProperties}}' => '',
            '{{filterPropertyList}}' => "'sort', 'per_page'",
            '{{clearFiltersBody}}' => '        //',
            '{{filterRequestMerge}}' => '',
            '{{textFilterColumns}}' => '',
            '{{exactFilterColumns}}' => '',
            '{{sortableColumns}}' => "'id', 'created_at', 'updated_at'",
            '{{includeRelations}}' => '',
            '{{eagerLoad}}' => '',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function filterCatalogReplacements(): array
    {
        $fkMap = $this->getForeignKeysMap();
        $filtered = array_values($this->getFilteredColumns());
        $textColumns = array_values(array_filter($filtered, fn (string $c) => ! isset($fkMap[$c])));
        $exactColumns = array_values(array_filter($filtered, fn (string $c) => isset($fkMap[$c])));
        $relations = array_values(array_unique(array_column($fkMap, 'relation')));

        $filterProperties = '';
        $filterRequestMerge = '';
        $clearReset = [];

        foreach ($filtered as $column) {
            $filterProperties .= "\n\n    #[Url]\n    public string \${$column} = '';";
            $filterRequestMerge .= "\n            '{$column}' => \$this->{$column},";
            $clearReset[] = "'{$column}'";
        }

        $filterPropertyList = implode(', ', array_map(
            fn (string $c) => "'{$c}'",
            array_merge($filtered, ['sort', 'per_page'])
        ));

        $clearFiltersBody = $clearReset === []
            ? '        //'
            : '        $this->reset(['.implode(', ', $clearReset).']);';

        $sortable = array_merge(['id', 'created_at', 'updated_at'], $textColumns);
        $sortable = array_values(array_unique($sortable));

        $eagerLoad = $relations === []
            ? ''
            : "->with(['".implode("', '", $relations)."'])";

        return [
            '{{catalogImports}}' => $this->buildCatalogImports(),
            '{{catalogMethods}}' => $this->buildCatalogMethods(),
            '{{filterProperties}}' => $filterProperties,
            '{{filterPropertyList}}' => $filterPropertyList !== '' ? $filterPropertyList : "'sort', 'per_page'",
            '{{clearFiltersBody}}' => $clearFiltersBody,
            '{{filterRequestMerge}}' => $filterRequestMerge,
            '{{textFilterColumns}}' => $this->quoteList($textColumns),
            '{{exactFilterColumns}}' => $this->quoteList($exactColumns),
            '{{sortableColumns}}' => $this->quoteList($sortable),
            '{{includeRelations}}' => $this->quoteList($relations),
            '{{eagerLoad}}' => $eagerLoad,
        ];
    }

    protected function buildCatalogImports(): string
    {
        $imports = [];

        foreach ($this->getForeignKeysMap() as $meta) {
            $imports[$meta['model']] = 'use '.$meta['model'].';';
        }

        if ($imports === []) {
            return '';
        }

        return implode("\n", $imports)."\n";
    }

    protected function buildCatalogMethods(): string
    {
        $methods = '';
        $seen = [];

        foreach ($this->getForeignKeysMap() as $meta) {
            if (isset($seen[$meta['relation']])) {
                continue;
            }
            $seen[$meta['relation']] = true;

            $relation = $meta['relation'];
            $class = class_basename($meta['model']);
            $label = $meta['label'];

            if ($meta['label_mode'] === 'method') {
                $order = Schema::hasColumn($meta['table'], 'sort') ? 'sort' : 'id';
                $methods .= <<<PHP

    /**
     * @return array<int|string, string>
     */
    public function {$relation}Options(): array
    {
        return {$class}::query()
            ->orderBy('{$order}')
            ->get()
            ->mapWithKeys(fn ({$class} \$row) => [\$row->getKey() => \$row->{$label}()])
            ->all();
    }

PHP;
            } else {
                $methods .= <<<PHP

    /**
     * @return array<int|string, string>
     */
    public function {$relation}Options(): array
    {
        return {$class}::query()
            ->orderBy('{$label}')
            ->pluck('{$label}', '{$meta['owner_key']}')
            ->all();
    }

PHP;
            }
        }

        return $methods;
    }

    /**
     * @return array<string, array{
     *     relation: string,
     *     table: string,
     *     class: string,
     *     model: string,
     *     label: string,
     *     label_mode: string,
     *     owner_key: string
     * }>
     */
    protected function getForeignKeysMap(): array
    {
        if ($this->foreignKeysMap !== null) {
            return $this->foreignKeysMap;
        }

        $map = [];

        foreach (Schema::getForeignKeys($this->table) as $fk) {
            if (count($fk['columns']) !== 1 || count($fk['foreign_columns']) !== 1) {
                continue;
            }

            $column = $fk['columns'][0];
            $foreignTable = $this->extractForeignTableName($fk['foreign_table']);
            $ownerKey = $fk['foreign_columns'][0];
            $class = Str::studly(Str::singular($foreignTable));
            $relation = Str::camel(Str::singular($foreignTable));
            $model = $this->resolveRelatedModelFqcn($class);
            [$labelMode, $label] = $this->resolveCatalogLabel($model, $foreignTable);

            $map[$column] = [
                'relation' => $relation,
                'table' => $foreignTable,
                'class' => $class,
                'model' => $model,
                'label' => $label,
                'label_mode' => $labelMode,
                'owner_key' => $ownerKey,
            ];
        }

        foreach ($this->getFilteredColumns() as $column) {
            if (isset($map[$column]) || ! str_ends_with($column, '_id')) {
                continue;
            }

            $base = Str::beforeLast($column, '_id');
            $class = Str::studly($base);
            $relation = Str::camel($base);
            $model = $this->resolveRelatedModelFqcn($class);

            if (! class_exists($model)) {
                continue;
            }

            $foreignTable = (new $model)->getTable();
            [$labelMode, $label] = $this->resolveCatalogLabel($model, $foreignTable);

            $map[$column] = [
                'relation' => $relation,
                'table' => $foreignTable,
                'class' => $class,
                'model' => $model,
                'label' => $label,
                'label_mode' => $labelMode,
                'owner_key' => (new $model)->getKeyName(),
            ];
        }

        return $this->foreignKeysMap = $map;
    }

    protected function isForeignKeyColumn(string $column): bool
    {
        return isset($this->getForeignKeysMap()[$column]);
    }

    protected function extractForeignTableName(string $foreignTable): string
    {
        $dot = strpos($foreignTable, '.');

        return $dot === false ? $foreignTable : substr($foreignTable, $dot + 1);
    }

    protected function resolveRelatedModelFqcn(string $class): string
    {
        $candidates = ['App\\Models\\'.$class];

        if ($this->moduleStudly) {
            array_unshift($candidates, 'Modules\\'.$this->moduleStudly.'\\Models\\'.$class);
        }

        foreach (glob(base_path('Modules/*/Models/'.$class.'.php')) ?: [] as $file) {
            $module = basename(dirname(dirname($file)));
            $candidates[] = 'Modules\\'.$module.'\\Models\\'.$class;
        }

        foreach (array_unique($candidates) as $fqcn) {
            if (class_exists($fqcn)) {
                return $fqcn;
            }
        }

        return $candidates[0];
    }

    /**
     * @return array{0: string, 1: string} [label_mode, label]
     */
    protected function resolveCatalogLabel(string $modelFqcn, string $table): array
    {
        if (str_ends_with($modelFqcn, '\\Status') || $table === 'statuses') {
            return ['method', 'label'];
        }

        $preferred = ['name', 'nombre', 'title', 'label', 'code'];

        try {
            $columns = array_column(Schema::getColumns($table), 'name');
            foreach ($preferred as $candidate) {
                if (in_array($candidate, $columns, true)) {
                    return ['attribute', $candidate];
                }
            }
        } catch (\Throwable) {
            // table may not exist yet during generation
        }

        return ['attribute', 'id'];
    }

    protected function columnIsNullable(string $column): bool
    {
        foreach ($this->getColumns() as $meta) {
            if (($meta['name'] ?? null) === $column) {
                return (bool) ($meta['nullable'] ?? false);
            }
        }

        return false;
    }

    /**
     * @param  list<string>  $items
     */
    protected function quoteList(array $items): string
    {
        return implode(', ', array_map(fn (string $item) => "'{$item}'", $items));
    }

    protected function getHead(string $title): string
    {
        $replace = array_merge($this->buildReplacements(), [
            '{{title}}' => $title,
        ]);

        $attr = match ($this->options['stack']) {
            'livewire' => 'scope="col" class="ui-th"',
            'tailwind' => 'scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500 dark:text-stone-400"',
            default => '',
        };

        return str_replace(
            array_keys($replace),
            array_values($replace),
            str_repeat(' ', 9).'<th '.$attr.'>{{title}}</th>'."\n"
        );
    }

    protected function getBody($column): string
    {
        $attr = match ($this->options['stack']) {
            'livewire', 'tailwind' => 'class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300"',
            default => '',
        };

        $cell = '{{ ${{modelNameLowerCase}}->{{column}} }}';

        if ($this->isForeignKeyColumn($column)) {
            $fk = $this->getForeignKeysMap()[$column];
            $relation = $fk['relation'];
            $cell = $fk['label_mode'] === 'method'
                ? '{{ ${{modelNameLowerCase}}->'.$relation.'?->'.$fk['label'].'() }}'
                : '{{ ${{modelNameLowerCase}}->'.$relation.'?->'.$fk['label'].' ?? ${{modelNameLowerCase}}->{{column}} }}';
        }

        $replace = array_merge($this->buildReplacements(), [
            '{{column}}' => $column,
        ]);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            str_repeat(' ', 10).'<td '.$attr.'>'.$cell.'</td>'."\n"
        );
    }

    protected function _getControllerPath(string $name): string
    {
        return $this->codePath($this->controllerNamespace)."{$name}Controller.php";
    }

    protected function _getApiControllerPath(string $name): string
    {
        return $this->codePath($this->apiControllerNamespace)."{$name}Controller.php";
    }

    protected function _getResourcePath(string $name): string
    {
        return $this->codePath($this->resourceNamespace)."{$name}Resource.php";
    }

    protected function _getLivewirePath(string $name): string
    {
        return $this->codePath($this->livewireNamespace)."{$name}.php";
    }

    protected function _getRequestPath(string $name): string
    {
        return $this->codePath($this->requestNamespace)."{$name}Request.php";
    }

    protected function _getModelPath(string $name): string
    {
        return $this->makeDirectory($this->codePath($this->modelNamespace)."{$name}.php");
    }

    protected function _getViewPath(string $view): string
    {
        $name = Str::kebab($this->name);
        $relative = match ($this->options['stack']) {
            'livewire' => "resources/views/livewire/{$name}/{$view}.blade.php",
            default => "resources/views/{$name}/{$view}.blade.php",
        };

        if ($this->moduleStudly) {
            return $this->makeDirectory(module_path($this->moduleStudly, $relative));
        }

        return $this->makeDirectory(resource_path(Str::after($relative, 'resources/')));
    }

    protected function _getServicePath(string $name): string
    {
        return $this->makeDirectory($this->codePath($this->serviceNamespace)."{$name}Service.php");
    }

    /**
     * Resolve filesystem path for a PSR-4 namespace under App\ or Modules\{X}\.
     */
    protected function codePath(string $namespace): string
    {
        if ($this->moduleStudly) {
            $prefix = 'Modules\\'.$this->moduleStudly.'\\';
            $relative = Str::after($namespace, $prefix);

            return Str::finish(module_path($this->moduleStudly, 'app/'.str_replace('\\', '/', $relative)), '/');
        }

        $relative = Str::after($namespace, 'App\\');

        return Str::finish(app_path(str_replace('\\', '/', $relative)), '/');
    }

    /**
     * @throws FileNotFoundException
     */
    protected function buildService(): void
    {
        $stubFile = Str::finish(config('crud.stub_path'), '/').'service.stub';

        if (! $this->files->exists($stubFile)) {
            $this->warn('No se encontró Service.stub; se omite el Service.');

            return;
        }

        $path = $this->_getServicePath($this->name);

        if ($this->files->exists($path) && $this->ask('Already exist Service. Do you want overwrite (y/n)?', 'y') == 'n') {
            return;
        }

        $this->info('Creating Service ...');

        $replace = array_merge($this->buildReplacements(), $this->modelReplacements());
        $template = str_replace(array_keys($replace), array_values($replace), $this->files->get($stubFile));

        $this->write($path, $template);
    }

    protected function writeRoute(): static
    {
        if (! $this->moduleStudly) {
            return parent::writeRoute();
        }

        $replacements = $this->buildReplacements();
        $routeFile = match ($this->options['stack']) {
            'api' => module_path($this->moduleStudly, 'routes/api.php'),
            default => module_path($this->moduleStudly, 'routes/web.php'),
        };

        $lines = match ($this->options['stack']) {
            'livewire' => [
                "Route::get('/{$this->_getRoute()}', \\{$this->livewireNamespace}\\{$replacements['{{modelNamePluralUpperCase}}']}\\Index::class)->name('{$this->_getRoute()}.index');",
                "Route::get('/{$this->_getRoute()}/create', \\{$this->livewireNamespace}\\{$replacements['{{modelNamePluralUpperCase}}']}\\Create::class)->name('{$this->_getRoute()}.create');",
                "Route::get('/{$this->_getRoute()}/show/{{$replacements['{{modelNameLowerCase}}']}}', \\{$this->livewireNamespace}\\{$replacements['{{modelNamePluralUpperCase}}']}\\Show::class)->name('{$this->_getRoute()}.show');",
                "Route::get('/{$this->_getRoute()}/update/{{$replacements['{{modelNameLowerCase}}']}}', \\{$this->livewireNamespace}\\{$replacements['{{modelNamePluralUpperCase}}']}\\Edit::class)->name('{$this->_getRoute()}.edit');",
            ],
            'api' => [
                "Route::apiResource('{$this->_getRoute()}', \\{$this->apiControllerNamespace}\\{$this->name}Controller::class);",
            ],
            default => [
                "Route::resource('{$this->_getRoute()}', \\{$this->controllerNamespace}\\{$this->name}Controller::class);",
            ],
        };

        $this->appendRoutes($routeFile, $lines);

        $this->info("Rutas añadidas en: {$routeFile}");
        foreach ($lines as $line) {
            $this->line('<fg=gray>'.$line.'</>');
        }

        return $this;
    }

    /**
     * @param  list<string>  $lines
     */
    protected function appendRoutes(string $routeFile, array $lines): void
    {
        if (! $this->files->exists($routeFile)) {
            $this->warn("No existe {$routeFile}; se omiten rutas automáticas.");

            return;
        }

        $block = "\n// CRUD: {$this->name} (make:crud --module={$this->moduleStudly})\n".implode("\n", $lines)."\n";
        $contents = $this->files->get($routeFile);

        if (str_contains($contents, "CRUD: {$this->name}")) {
            $this->warn('Las rutas de este CRUD ya estaban en el archivo; no se duplicaron.');

            return;
        }

        $this->files->append($routeFile, $block);
    }

    protected function writeBreadcrumbs(): void
    {
        if (($this->options['stack'] ?? null) === 'api') {
            return;
        }

        $file = base_path('routes/breadcrumbs.php');

        if (! $this->files->exists($file)) {
            $this->warn('No existe routes/breadcrumbs.php; se omiten breadcrumbs.');

            return;
        }

        $replace = $this->buildReplacements();
        $route = $replace['{{modelRoute}}'] ?? $this->_getRoute();
        $titlePlural = $replace['{{modelTitlePlural}}'] ?? Str::title(str_replace('_', ' ', $route));
        $modelFqcn = ($replace['{{modelNamespace}}'] ?? $this->modelNamespace).'\\'.($replace['{{modelName}}'] ?? $this->name);
        $modelVar = '$'.($replace['{{modelNameLowerCase}}'] ?? Str::camel($this->name));
        $marker = "CRUD: {$this->name}";

        $contents = $this->files->get($file);

        if (str_contains($contents, $marker)) {
            $this->warn('Breadcrumbs de este CRUD ya estaban definidos; no se duplicaron.');

            return;
        }

        $block = <<<PHP

// {$marker}
Breadcrumbs::for('{$route}.index', function (BreadcrumbTrail \$trail): void {
    \$trail->parent('dashboard');
    \$trail->push(__('{$titlePlural}'), route('{$route}.index'));
});

Breadcrumbs::for('{$route}.create', function (BreadcrumbTrail \$trail): void {
    \$trail->parent('{$route}.index');
    \$trail->push(__('Nuevo'), route('{$route}.create'));
});

Breadcrumbs::for('{$route}.show', function (BreadcrumbTrail \$trail, \\{$modelFqcn} {$modelVar}): void {
    \$trail->parent('{$route}.index');
    \$trail->push(__('Detalle'), route('{$route}.show', {$modelVar}));
});

Breadcrumbs::for('{$route}.edit', function (BreadcrumbTrail \$trail, \\{$modelFqcn} {$modelVar}): void {
    \$trail->parent('{$route}.index');
    \$trail->push(__('Editar'), route('{$route}.edit', {$modelVar}));
});

PHP;

        $this->files->append($file, $block);
        $this->info('Breadcrumbs añadidos en routes/breadcrumbs.php');
    }

    protected function ensureModuleHttpController(): void
    {
        $path = module_path($this->moduleStudly, 'app/Http/Controllers/Controller.php');

        if ($this->files->exists($path)) {
            return;
        }

        $this->write($path, <<<PHP
<?php

namespace Modules\\{$this->moduleStudly}\\Http\\Controllers;

use App\\Http\\Controllers\\Controller as BaseController;

abstract class Controller extends BaseController
{
    //
}

PHP);
    }

    protected function moduleExists(string $studly): bool
    {
        if (function_exists('module_path') && is_dir(module_path($studly))) {
            return true;
        }

        if (class_exists(Module::class)) {
            return Module::has($studly);
        }

        return is_dir(base_path('Modules/'.$studly));
    }
}
