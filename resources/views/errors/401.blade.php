<x-errors.shell
    :code="401"
    :title="__('errors.unauthorized')"
    :message="$message ?? __('errors.unauthorized_detail')"
/>
