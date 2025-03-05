@props(['event', 'can_edit' => false, 'need_full_content' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($event)

    <div class="flex flex-row" style="margin-bottom: 1rem;margin-top: 1rem;">
        <div class="flex flex-col items-center" style="min-width: 14rem;min-height: 8rem;overflow: hidden;">
            @if(!!$can_edit)
                <x-primary-button
                        class="w-full mb-6"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'create-event-photo-{{ $event->id }}')"
                >{{ __('Добавить фото') }}</x-primary-button>
            @endif
            <div class="flex justify-center">
                @if(count($event->event_images) > 0)
                    <x-images-slider :images="$event->event_images" :size="160" :event_id="$event->id" :travel_id="$event->travel_id" :can_edit="$can_edit" :redirect_to="$redirect_to" :query_parameters="$query_parameters"/>
                @else
                    <div style="line-height: 48px;">Нет фотографий</div>
                @endif
            </div>
        </div>
        <div class="p-0 w-full" style="margin-left: 1rem;">
            <div class="mb-2 mt-1">
                <x-event-score-status :score_data="$event->score_data()" />
            </div>
            <h2 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">
                <span class="flex gap-2 text-nowrap">
                    <span>{{ date_create($event->event_at)->format('d.m.Y H:i') }}</span>
                    <span>{{ $event->event_title }}</span>
                    @if(!!$can_edit)
                        <x-secondary-button
                                style="padding: .2rem;border: none;"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'update-event-header-{{ $event->id }}')"
                        >
                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-secondary-button>
                    @endif
                </span>
            </h2>
            <div class="pb-2">
                <span>{{ $event->event_description }}</span>
            </div>
            <div class="flex items-center gap-2 pb-2">
                Потрачено за событие: <h2 class="text-lg font-medium text-gray-900">{{ $event->event_price }} ₽</h2>
                @if(!!$can_edit)
                    <x-secondary-button
                            style="padding: .2rem;border: none;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'update-event-price-{{ $event->id }}')"
                    >
                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-secondary-button>
                @endif
            </div>
            <div class="flex items-center gap-2 pb-2">
                Оценка события: <h2 class="text-lg font-medium text-gray-900">
                    <x-event-score-view :score_data="$event->score_data()" />
                </h2>
                @if(!!$can_edit)
                    <x-secondary-button
                            style="padding: .2rem;border: none;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'update-event-status-{{ $event->id }}')"
                    >
                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-secondary-button>
                @endif
            </div>
        </div>
        @if(!!$can_edit)
            <div class="flex flex-col gap-2">
                <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-event-description-{{ $event->id }}')"
                >{{ __('Редактировать') }}</x-primary-button>
                <x-danger-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-event-{{ $event->id }}')"
                >{{ __('Удалить') }}</x-danger-button>
            </div>
        @endif
    </div>

    @if(!!$can_edit)

        <x-modal name="create-event-photo-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-images.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Прикрепить фотографию к событию путешествия') }}
                </h2>

                <!-- travel_id -->
                <div style="display: none;">
                    <x-text-input id="travel_id" type="number" name="travel_id" :value="$event->travel_id" required/>
                </div>

                <!-- travel_event_id -->
                <div style="display: none;">
                    <x-text-input id="travel_event_id" type="number" name="travel_event_id" :value="$event->id" required/>
                </div>

                <!-- travel_photo -->
                <div>
                    <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                        <x-text-input id="travel_photo" name="travel_photo" type="file" class="block w-full" :value="old('travel_photo')" required/>
                    </div>
                    <x-input-error :messages="$errors->get('travel_photo')" class="mt-2" />
                </div>

                <!-- image_title -->
                <div>
                    <x-input-label for="image_title" :value="__('Подпись к фотографии')" />
                    <x-text-input id="image_title" name="image_title" type="text" minlength="1" maxlength="70" class="mt-1 block w-full" :value="old('image_title')" autofocus autocomplete="image_title" />
                    <x-input-error class="mt-2" :messages="$errors->get('image_title')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Создать') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-event-header-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-events.update-header', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать заголовок и дату с временем события') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$event->id" required/>
                </div>

                <!-- event_title -->
                <div>
                    <x-input-label for="event_title" :value="__('Заголовок события')" />
                    <x-text-input id="event_title" name="event_title" type="text" minlength="10" maxlength="70" class="mt-1 block w-full" :value="old('event_title', $event->event_title)" autocomplete="event_title" />
                    <x-input-error class="mt-2" :messages="$errors->get('event_title')" />
                </div>

                <!-- event_at -->
                <div>
                    <x-input-label for="event_at" :value="__('Дата и время события')" />
                    <x-text-input id="event_at" name="event_at" type="datetime-local" class="mt-1 block w-full" :value="old('event_at', $event->event_at)" required autofocus autocomplete="event_at" />
                    <x-input-error class="mt-2" :messages="$errors->get('event_at')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-event-price-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-events.update-price', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать количество потраченных денег за событие') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$event->id" required/>
                </div>

                <!-- event_price -->
                <div>
                    <x-text-input id="event_price" name="event_price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('event_price', $event->event_price)" autofocus autocomplete="event_price" />
                    <x-input-error class="mt-2" :messages="$errors->get('event_price')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-event-description-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-events.update-description', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать описание события путешествия') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$event->id" required/>
                </div>

                <!-- event_description -->
                <div>
                    <textarea id="event_description" name="event_description" rows="7" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" autocomplete="event_description">{{ old('event_description', $event->event_description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('event_description')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-event-status-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-events.update-status', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать описание события путешествия') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$event->id" required/>
                </div>

                {{-- event_score --}}
                <div>
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="event_score" id="event_score" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                            @foreach(\App\Models\TravelEvent::EVENT_SCORES as $score)
                                <option value="{{ $score['id'] }}"
                                        @if ($score['id'] == old('event_score', $event->event_score))
                                            selected
                                        @endif
                                >{{ $score['title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-event-{{ $event->id }}" focusable>
            <form method="post" action="{{ route('travel-events.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить событие путешествия') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$event->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Удалить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

    @endif

@endif
