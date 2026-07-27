<section class="w-full space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('Blogs') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Crear :resource', ['resource' => __('Blog')]) }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('blogs.index')" wire:navigate>
            {{ __('Volver') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="ui-surface p-4 sm:p-8">
        <form method="POST" wire:submit="save" role="form" enctype="multipart/form-data" class="max-w-xl">
            @csrf
            @include('blog::livewire.blog.form')
        </form>
    </div>
</section>
