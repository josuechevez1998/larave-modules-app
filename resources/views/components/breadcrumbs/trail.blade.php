@unless ($crumbs->isEmpty())
    <nav {{ $attributes->merge(['aria-label' => __('Breadcrumb')]) }}>
        <flux:breadcrumbs class="flex-wrap">
            @foreach ($crumbs as $breadcrumb)
                @if ($breadcrumb->url && ! $loop->last)
                    <flux:breadcrumbs.item
                        :href="$breadcrumb->url"
                        separator="slash"
                        wire:navigate
                    >
                        {{ $breadcrumb->title }}
                    </flux:breadcrumbs.item>
                @else
                    <flux:breadcrumbs.item separator="slash">
                        {{ $breadcrumb->title }}
                    </flux:breadcrumbs.item>
                @endif
            @endforeach
        </flux:breadcrumbs>
    </nav>
@endunless
