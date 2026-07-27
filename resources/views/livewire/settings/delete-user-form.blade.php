<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('app.delete_account') }}</flux:heading>
        <flux:subheading>{{ __('app.delete_account_subheading') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger">{{ __('app.delete_account') }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('app.delete_account_confirm') }}</flux:heading>
                <flux:subheading>{{ __('app.delete_account_warning') }}</flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('app.delete_account') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
