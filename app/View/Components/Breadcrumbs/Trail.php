<?php

namespace App\View\Components\Breadcrumbs;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;
use Throwable;

class Trail extends Component
{
    /** @var Collection<int, object> */
    public Collection $crumbs;

    public function __construct(?string $name = null, array $params = [])
    {
        $this->crumbs = collect();

        if (! class_exists(\Diglactic\Breadcrumbs\Breadcrumbs::class)) {
            return;
        }

        $name ??= request()->route()?->getName();
        if (! $name) {
            return;
        }

        try {
            if (! \Diglactic\Breadcrumbs\Breadcrumbs::exists($name)) {
                return;
            }

            $this->crumbs = \Diglactic\Breadcrumbs\Breadcrumbs::generate($name, ...$params);
        } catch (Throwable) {
            $this->crumbs = collect();
        }
    }

    public function shouldRender(): bool
    {
        return $this->crumbs->isNotEmpty();
    }

    public function render(): View
    {
        return view('components.breadcrumbs.trail');
    }
}
