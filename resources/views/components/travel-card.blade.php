@props(['travel', 'can_edit' => false, 'need_full_content' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($travel)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        @include('travels.partials.travel-main-content')
    </div>

@endif
