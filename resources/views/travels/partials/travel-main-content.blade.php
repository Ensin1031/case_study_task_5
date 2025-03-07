<section class="flex w-full">
    <script defer>
        function setEventDescriptionViewBlock(view_count, travel_id = '', event_id = '', coord_id = '') {
            const description = document.getElementById(`description_${travel_id}_${event_id}_${coord_id}`)
            const description_manual = document.getElementById(`description_postfix_${travel_id}_${event_id}_${coord_id}`)
            const description_row = description_manual?.attributes?.getNamedItem('data-description_row')?.value
            const need_view = description_manual?.attributes?.getNamedItem('data-full_view')?.value === '2'
            if (need_view) {
                description.innerHTML = description_row.slice(0, view_count)
                description_manual.innerHTML = ' . . . показать полностью'
            } else {
                description.innerHTML = description_row
                description_manual.innerHTML = 'скрыть'
            }
            const attr = document.createAttribute('data-full_view');
            attr.value = need_view ? '1' : '2';
            description_manual?.attributes.setNamedItem(attr)
        }
    </script>
    <div class="flex flex-col items-center" style="min-width: 12rem;min-height: 11rem;overflow: hidden;">
        <div class="w-full {{ !!$can_edit ? 'mb-4' : '' }} " style="background-image: url({{$travel->main_photo_url}}); background-size: cover; height: 11rem;border-radius: 5px;"></div>
        @if(!!$can_edit)
            <x-secondary-button
                class="w-full"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'update-travel-main-photo-{{ $travel->id }}')"
            >{{ __('Сменить обложку') }}</x-secondary-button>
        @endif
    </div>
    <div class="flex flex-col pl-4 w-full">
        <div class="flex items-center justify-between mr-4 pb-2">
            <div class="flex items-center gap-2">
                @if(!!$need_full_content)
                    <h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->travel_title }}</h2>
                @else
                    <a class="hover:underline" href="{{ route('travels.travel-show', $travel->id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->travel_title }}</h2></a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 pb-2">
            Начало: <h2 class="text-lg font-medium text-gray-900">{{ date_create($travel->start_at)->format('d.m.Y') }}</h2>
            @if(!!$can_edit)
                <x-secondary-button
                    style="padding: .2rem;border: none;"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'update-travel-start_at-{{ $travel->id }}')"
                >
                    <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </x-secondary-button>
            @endif
        </div>
        @if(!!$travel->end_at)
            <div class="flex items-center gap-2 pb-2">
                Окончание: <h2 class="text-lg font-medium text-gray-900">{{ date_create($travel->end_at)->format('d.m.Y') }}</h2>
                @if(!!$can_edit)
                    <x-secondary-button
                        style="padding: .2rem;border: none;"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-travel-end_at-{{ $travel->id }}')"
                    >
                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-secondary-button>
                @endif
            </div>
        @endif
        <div class="flex items-center gap-2 pb-2">
            Потрачено за путешествие: <h2 class="text-lg font-medium text-gray-900">{{ $travel->full_price() }} ₽</h2>
        </div>
        @if(!!$travel->short_description)
            <div class="pb-2">
                @if(!!$need_full_content)
                    <div class="pb-2" id="description_block_{{ $travel->id }}__">
                        <span id="description_{{ $travel->id }}__">{{ substr($travel->short_description, 0, 500) }}</span>
                        @if(strlen($travel->short_description) > 500)
                            <span
                                class="hover:underline"
                                style="color: blue;cursor: pointer;font-size: .825rem;"
                                id="description_postfix_{{ $travel->id }}__"
                                data-full_view="1"
                                data-description_row="{{ $travel->short_description }}"
                                onclick="setEventDescriptionViewBlock(500, {!! json_encode($travel->id ?? 0) !!})"
                            > . . . показать полностью</span>
                        @endif
                    </div>
                @else
                    <span style="display: -webkit-box;-webkit-line-clamp: {{ !!$can_edit ? 4 : 3 }};-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->short_description }}</span>
                @endif
            </div>
        @endif
        @if(!!$need_full_content)
            <details class="w-full">
                <summary class="flex flex-row gap-2 items-center" style="cursor: pointer;" id="main_travel_map_{{$travel->id}}">
                    <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">-</span>
                    <span class="text-lg font-medium text-gray-900">{{ __('Показать карту путешествия') }}</span>
                </summary>
                <div class="w-full" style="position: relative;">
                    <div class="w-full mt-2 mb-2" style="min-height: 20rem;border: 1px solid #bbb;border-radius: 5px;overflow: hidden;">
                        <x-map :map_id="'travel_map_id_'.$travel->id" :height="'20rem'" :markers="$travel->travel_map_coordinates()" />
                    </div>
                    @if(!!$can_edit || !!(count($travel->travel_map_coordinates()) > 0))
                        <div style="border: 1px solid #bbb;border-radius: 5px;padding: .5rem;">
                            @if(!!$can_edit)
                                <div class="mb-2">
                                    <x-secondary-button
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'create-user-coordinates-{{ $travel->id }}')"
                                    >Добавить событие на карту</x-secondary-button>
                                </div>
                            @endif
                            @foreach($travel->travel_map_coordinates() as $marker)
                                <div class="pl-2 pb-2">
                                    <div class="flex items-center gap-2">
                                        <a class="hover:underline" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;" href="#main_travel_map_{{ $travel->id }}" onclick="goToMapObjPoint({{ json_encode($marker ?? []) }})">
                                            {{ $marker['title'] }}
                                        </a>
                                        @if(!!$can_edit)
                                            <div class="flex gap-2">
                                                <x-secondary-button
                                                    style="padding: 0;border: none;width: 18px;height: 18px;"
                                                    x-data=""
                                                    data-coord_id="{{ $marker['id'] }}"
                                                    data-coord_title="{{ $marker['title'] }}"
                                                    data-coord_description="{{ $marker['description'] }}"
                                                    data-coord_lat="{{ $marker['position']['lat'] }}"
                                                    data-coord_lng="{{ $marker['position']['lng'] }}"
                                                    x-on:click.prevent="$dispatch('open-modal', 'update-coordinates')"
                                                >
                                                    <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                                </x-secondary-button>
                                                <x-secondary-button
                                                    style="padding: 0;border: none;width: 18px;height: 18px;"
                                                    x-data=""
                                                    data-coord_id="{{ $marker['id'] }}"
                                                    x-on:click.prevent="$dispatch('open-modal', 'delete-coordinates')"
                                                >
                                                    <x-delete-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                                </x-secondary-button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pl-2" id="description_block_{{ $travel->id }}__{{ $marker['id'] }}">
                                        <span id="description_{{ $travel->id }}__{{ $marker['id'] }}" style="font-size: .825rem;color: #666;">{{ substr($marker['description'], 0, 150) }}</span>
                                        @if(strlen($marker['description']) > 150)
                                            <span
                                                class="hover:underline"
                                                style="color: blue;cursor: pointer;font-size: .825rem;"
                                                id="description_postfix_{{ $travel->id }}__{{ $marker['id'] }}"
                                                data-full_view="1"
                                                data-description_row="{{ $marker['description'] }}"
                                                onclick="setEventDescriptionViewBlock(150, {!! json_encode($travel->id ?? 0) !!}, '', {!! json_encode($marker['id'] ?? 0) !!})"
                                            > . . . показать полностью</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </details>
        @elseif(!!$need_full_content && !!$can_edit)
            <div class="mb-2">
                <x-secondary-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-user-coordinates-{{ $travel->id }}')"
                >Добавить событие на карту</x-secondary-button>
            </div>
        @endif
    </div>
    @if(!!$can_edit || !!$need_full_content)
        <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4" style="min-width: 15rem;">
            @if(!!$can_edit)
                <x-primary-button
                    class="w-full"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'update-travel-{{ $travel->id }}')"
                >{{ __('Редактировать') }}</x-primary-button>
                @if(!$travel->end_at)
                    <x-secondary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-travel-end_at-{{ $travel->id }}')"
                    >{{ __('Закончить путешествие') }}</x-secondary-button>
                @endif
                <x-danger-button
                    class="w-full"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'delete-travel-{{ $travel->id }}')"
                >{{ __('Удалить') }}</x-danger-button>
                @if(!!$need_full_content)
                    <x-primary-button
                        class="w-full mb-6"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'create-travel-photos-{{ $travel->id }}')"
                    >{{ __('Добавить фото') }}</x-primary-button>
                @endif
            @endif

            @if(!!$need_full_content)
                <div class="flex justify-center">
                    <x-images-slider :images="$travel->travel_images" :travel_id="$travel->id" :size="160" :can_edit="$can_edit" :redirect_to="$redirect_to" :query_parameters="$query_parameters"/>
                </div>
            @endif
        </div>
    @endif

    @if(!!$can_edit)

        <x-modal name="update-travel-main-photo-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel.change-main-photo', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Сменить обложку путешествия') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$travel->id" required/>
                </div>

                <!-- travel_main_photo -->
                <div>
                    <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                        <x-text-input id="travel_main_photo" name="travel_main_photo" type="file" class="block w-full" :value="old('travel_main_photo')" required/>
                    </div>
                    <x-input-error :messages="$errors->get('travel_main_photo')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сменить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-travel-start_at-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel.update-travel-dates', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Сменить дату начала путешествия') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$travel->id" required/>
                </div>

                <!-- start_at -->
                <div>
                    <x-text-input id="start_at" name="start_at" type="date" class="mt-1 block w-full" :value="old('start_at', $travel->start_at)" required autofocus autocomplete="start_at" />
                    <x-input-error class="mt-2" :messages="$errors->get('start_at')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сменить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-travel-end_at-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel.update-travel-dates', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Закончить путешествие') }}?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Внесите дату окончания путешествия') }}
                </p>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$travel->id" required/>
                </div>

                <!-- end_at -->
                <div>
                    <x-text-input id="end_at" name="end_at" type="date" class="mt-1 block w-full" :value="old('end_at', $travel->end_at)" required autofocus autocomplete="end_at" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_at')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        @if(!!$travel->end_at)
                            {{ __('Сменить') }}
                        @else
                            {{ __('Внести') }}
                        @endif
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-travel-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать запись путешествия') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$travel->id" required/>
                </div>

                <!-- travel_title -->
                <div>
                    <x-input-label for="travel_title" :value="__('Наименование путешествия')" />
                    <x-text-input id="travel_title" name="travel_title" type="text" minlength="10" maxlength="70" class="mt-1 block w-full" :value="old('travel_title', $travel->travel_title)" required autofocus autocomplete="travel_title" />
                    <x-input-error class="mt-2" :messages="$errors->get('travel_title')" />
                </div>

                <!-- short_description -->
                <div>
                    <x-input-label for="short_description" :value="__('Краткое описание')" />
                    <textarea id="short_description" name="short_description" rows="3" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" autocomplete="short_description">{{ old('short_description', $travel->short_description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('short_description')" />
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

        <x-modal name="delete-travel-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись путешествия') }}?
                </h2>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$travel->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-danger-button class="ms-3">
                        {{ __('Удалить') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="create-travel-photos-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel-images.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Прикрепить фотографию к путешествию') }}
                </h2>

                <!-- travel_id -->
                <div style="display: none;">
                    <x-text-input id="travel_id" type="number" name="travel_id" :value="$travel->id" required/>
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

        <script defer>
            window.addEventListener('open-modal', event => {
                if (event.detail === 'create-user-coordinates-{{ $travel->id }}') {
                    const event_id = event?.target?.attributes?.getNamedItem('data-event_id')?.value
                    const latitude = getLatitude()
                    const longitude = getLongitude()
                    if (!!latitude && longitude) {
                        setTimeout(function ()
                        {
                            const create_address_latitude_input = document.querySelector('#create_address_latitude_input')
                            if (create_address_latitude_input) {
                                create_address_latitude_input.value = latitude
                            }
                            const create_address_longitude_input = document.querySelector('#create_address_longitude_input')
                            if (create_address_longitude_input) {
                                create_address_longitude_input.value = longitude
                            }
                        }, 100);
                    } else if (typeof navigator !== 'undefined' && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            const { latitude, longitude } = position.coords
                            setTimeout(function ()
                            {
                                const create_address_latitude_input = document.querySelector('#create_address_latitude_input')
                                if (create_address_latitude_input) {
                                    create_address_latitude_input.value = latitude
                                }
                                const create_address_longitude_input = document.querySelector('#create_address_longitude_input')
                                if (create_address_longitude_input) {
                                    create_address_longitude_input.value = longitude
                                }
                            }, 100);
                        })
                    }
                    if (!!event_id) {
                        const travel_event_id_input = document.querySelector('#travel_event_id_input')
                        if (travel_event_id_input) {
                            travel_event_id_input.value = event_id
                        }
                    }
                }
                if (event.detail === 'delete-image') {
                    const image_id = event?.target?.attributes?.getNamedItem('data-image_id')?.value
                    if (image_id) {
                        setTimeout(function ()
                        {
                            const id_input = document.querySelector('#delete-image-id-input')
                            if (id_input) {
                                id_input.value = image_id
                            }
                        }, 100);
                    }
                }
                if (event.detail === 'delete-coordinates') {
                    const coord_id = event?.target?.attributes?.getNamedItem('data-coord_id')?.value
                    if (coord_id) {
                        setTimeout(function ()
                        {
                            const delete_coordinates_id_input = document.querySelector('#delete_coordinates_id_input')
                            if (delete_coordinates_id_input) {
                                delete_coordinates_id_input.value = coord_id
                            }
                        }, 100);
                    }
                }
                if (event.detail === 'update-coordinates') {
                    const coord_id = event?.target?.attributes?.getNamedItem('data-coord_id')?.value;
                    const coord_title = event?.target?.attributes?.getNamedItem('data-coord_title')?.value;
                    const coord_description = event?.target?.attributes?.getNamedItem('data-coord_description')?.value ?? '';
                    const coord_lat = event?.target?.attributes?.getNamedItem('data-coord_lat')?.value;
                    const coord_lng = event?.target?.attributes?.getNamedItem('data-coord_lng')?.value;
                    if (coord_id && coord_title && coord_lat && coord_lng) {
                        setTimeout(function ()
                        {
                            const update_coordinates_id_input = document.querySelector('#update_coordinates_id_input')
                            if (update_coordinates_id_input) {
                                update_coordinates_id_input.value = coord_id
                            }
                            const update_coordinates_title_input = document.querySelector('#update_coordinates_title_input')
                            if (update_coordinates_title_input) {
                                update_coordinates_title_input.value = coord_title
                            }
                            const update_coordinates_description_input = document.querySelector('#update_coordinates_description_input')
                            if (update_coordinates_description_input) {
                                update_coordinates_description_input.value = coord_description
                            }
                            const update_address_latitude_input = document.querySelector('#update_address_latitude_input')
                            if (update_address_latitude_input) {
                                update_address_latitude_input.value = coord_lat
                            }
                            const update_address_longitude_input = document.querySelector('#update_address_longitude_input')
                            if (update_address_longitude_input) {
                                update_address_longitude_input.value = coord_lng
                            }
                        }, 100);
                    }
                }
                if (event.detail === 'update-image-title') {
                    const image_id = event?.target?.attributes?.getNamedItem('data-image_id')?.value
                    const title = event?.target?.attributes?.getNamedItem('data-title')?.value
                    if (image_id && title) {
                        setTimeout(function ()
                        {
                            const id_input = document.querySelector('#update-image-id-input')
                            if (id_input) {
                                id_input.value = image_id
                            }
                            const title_input = document.querySelector('#update-image-title-input')
                            if (title_input) {
                                title_input.value = title
                            }

                        }, 100);
                    }
                }

            });
        </script>

        <x-modal name="create-user-coordinates-{{ $travel->id }}" focusable>
            <form method="post" action="{{ route('travel-map-coordinates.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Создать отметку на карте по координатам') }}
                </h2>

                <!-- travel_id -->
                <div style="display: none;">
                    <x-text-input id="travel_id" type="number" name="travel_id" :value="$travel->id" required/>
                </div>

                <!-- travel_event_id -->
                <div style="display: none;">
                    <x-text-input id="travel_event_id_input" type="number" name="travel_event_id" :value="0" required/>
                </div>

                <!-- title -->
                <div>
                    <x-input-label for="title" :value="__('Заголовок')" />
                    <x-text-input id="title" name="title" type="text" minlength="3" maxlength="70" class="mt-1 block w-full" :value="old('title')" autofocus autocomplete="title" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <!-- description -->
                <div>
                    <x-input-label for="description" :value="__('Описание')" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" autocomplete="description">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-between gap-4">
                    <!-- address_latitude -->
                    <div style="width: 49%;">
                        <x-input-label for="create_address_latitude_input" :value="__('Широта')" />
                        <x-text-input id="create_address_latitude_input" name="address_latitude" type="number" step="0.000000000000001" class="mt-1 block w-full" :value="old('address_latitude')" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_latitude')" />
                    </div>
                    <!-- address_longitude -->
                    <div style="width: 49%;">
                        <x-input-label for="create_address_longitude_input" :value="__('Долгота')" />
                        <x-text-input id="create_address_longitude_input" name="address_longitude" type="number" step="0.000000000000001" class="mt-1 block w-full" :value="old('address_longitude')" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_longitude')" />
                    </div>
                </div>

                <details class="w-full">
                    <summary class="flex flex-row gap-2 items-center"  style="cursor: pointer;" onclick="mapCreateInvalidateSize()">
                        <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">-</span>
                        <span class="text-lg font-medium text-gray-900">{{ __('Установить координаты по карте') }}</span>
                    </summary>
                    <div class="w-full" style="position: relative;">
                        <div class="w-full" style="min-height: 20rem;">
                            <x-map-create-for-get-coordinates :map_id="'set_create_coordinates_map_id_'.$travel->id" :travel_id="$travel->id" :height="'20rem'" />
                        </div>
                    </div>
                </details>

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

        <x-modal name="update-coordinates" focusable>
            <form method="post" action="{{ route('travel-map-coordinates.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать отметку на карте') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="update_coordinates_id_input" type="number" name="id" :value="0" required/>
                </div>

                <!-- title -->
                <div>
                    <x-input-label for="update_coordinates_title_input" :value="__('Заголовок')" />
                    <x-text-input id="update_coordinates_title_input" name="title" type="text" minlength="3" maxlength="70" class="mt-1 block w-full" :value="old('title')" autofocus autocomplete="title" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <!-- description -->
                <div>
                    <x-input-label for="update_coordinates_description_input" :value="__('Описание')" />
                    <textarea id="update_coordinates_description_input" name="description" rows="3" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" autocomplete="description">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-between gap-4">
                    <!-- address_latitude -->
                    <div style="width: 49%;">
                        <x-input-label for="update_address_latitude_input" :value="__('Широта')" />
                        <x-text-input id="update_address_latitude_input" name="address_latitude" type="number" step="0.000000000000001" class="mt-1 block w-full" :value="old('address_latitude')" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_latitude')" />
                    </div>
                    <!-- address_longitude -->
                    <div style="width: 49%;">
                        <x-input-label for="update_address_longitude_input" :value="__('Долгота')" />
                        <x-text-input id="update_address_longitude_input" name="address_longitude" type="number" step="0.000000000000001" class="mt-1 block w-full" :value="old('address_longitude')" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_longitude')" />
                    </div>
                </div>

                <details class="w-full">
                    <summary class="flex flex-row gap-2 items-center"  style="cursor: pointer;" onclick="mapUpdateInvalidateSize()">
                        <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">-</span>
                        <span class="text-lg font-medium text-gray-900">{{ __('Установить координаты по карте') }}</span>
                    </summary>
                    <div class="w-full" style="position: relative;">
                        <div class="w-full" style="min-height: 20rem;">
                            <x-map-update-for-get-coordinates :map_id="'set_update_coordinates_map_id_'.$travel->id" :travel_id="$travel->id" :height="'20rem'" />
                        </div>
                    </div>
                </details>

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

        <x-modal name="delete-coordinates" focusable>
            <form method="post" action="{{ route('travel-map-coordinates.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить отметку на карте') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="delete_coordinates_id_input" type="number" name="id" :value="0" required/>
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

        <x-modal name="delete-image" id="delete-image" focusable>
            <form method="post" action="{{ route('travel-images.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить фотографию путешествия') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="delete-image-id-input" type="number" name="id" required/>
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

        <x-modal name="update-image-title" id="delete-image" focusable>
            <form method="post" action="{{ route('travel-images.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Сменить подпись фотографии') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="update-image-id-input" type="number" name="id" required/>
                </div>

                <!-- image_title -->
                <div>
                    <x-text-input id="update-image-title-input" name="image_title" type="text" minlength="1" maxlength="70" class="mt-1 block w-full" :value="old('image_title')" autofocus autocomplete="image_title" />
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

    @endif

</section>
