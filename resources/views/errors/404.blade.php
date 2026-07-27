<x-errors.shell
    :code="404"
    :title="__('errors.not_found')"
    :message="$message ?? __('errors.not_found_detail')"
/>
