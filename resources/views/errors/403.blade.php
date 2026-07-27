<x-errors.shell
    :code="403"
    :title="__('errors.forbidden')"
    :message="$message ?? __('errors.forbidden_detail')"
/>
