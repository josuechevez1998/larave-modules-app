<x-errors.shell
    :code="503"
    :title="__('errors.service_unavailable')"
    :message="$message ?? __('errors.service_unavailable_detail')"
/>
