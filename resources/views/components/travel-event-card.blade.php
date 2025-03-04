@props(['event', 'can_edit' => false, 'need_full_content' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($event)

    <div class="p-0 w-full" style="margin-top: .5rem">
        <h2 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">
            <span class="flex gap-2 text-nowrap">
                @if(!!$can_edit)
                    <x-secondary-button
                        style="padding: .2rem;border: none;"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-event-header-{{ $event->id }}')"
                    >
                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-secondary-button>
                @endif
                <span>{{ date_create($event->event_at)->format('d.m.Y H:i') }}</span>
                <span>{{ $event->event_title }}</span>
            </span>
        </h2>
        <div>
            {{ $event->event_description }}
        </div>
    </div>

@endif
