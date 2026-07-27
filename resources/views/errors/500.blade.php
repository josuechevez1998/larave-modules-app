<x-errors.shell
    :code="500"
    :title="__('errors.unexpected')"
    :message="$message ?? __('errors.unexpected_detail')"
/>
