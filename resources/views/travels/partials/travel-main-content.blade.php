<section class="flex w-full">
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
                    <span>{{ $travel->short_description }}</span>
                @else
                    <span style="display: -webkit-box;-webkit-line-clamp: {{ !!$can_edit ? 4 : 3 }};-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->short_description }}</span>
                @endif
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

        <script>
            window.addEventListener('open-modal', event => {
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
