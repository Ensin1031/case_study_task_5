<section>
    <header>
        <div class="flex justify-between items-center" style="min-height: 2.2rem;">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h2>
            @auth
                <x-secondary-button
                    class="w-fit"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-travel-{{ $user->id }}')"
                >{{ __('Добавить путешествие') }}</x-secondary-button>
            @endauth
        </div>
    </header>

    <div>
        РЕДИРЕКТ: {{ $redirect_to }}
    </div>
    <div>
        ЮЗЕР: {{ $user }}
    </div>
    <div>
        ТРЕВЕЛЫ: {{ $travels }}
    </div>
    <div class="mt-6 space-y-6">
        @foreach($travels as $travel)
            <x-travel-card :travel="$travel" :need_subs_manager="true" :redirect_to="$redirect_to" class="mt-2"/>
        @endforeach
    </div>
</section>

@auth
    <x-modal name="create-travel-{{ $user->id }}" focusable>
        <form method="post" action="{{ route('travel.create', ['redirect_to' => $redirect_to]) }}" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Создать запись путешествия пользователя') }}
            </h2>

            <!-- user_id -->
            <div style="display: none;">
                <x-text-input id="user_id" type="number" name="user_id" :value="$user->id" required/>
            </div>

            <!-- travel_title -->
            <div>
                <x-input-label for="travel_title" :value="__('Наименование путешествия')" />
                <x-text-input id="travel_title" name="travel_title" type="text" minlength="10" maxlength="70" class="mt-1 block w-full" :value="old('travel_title')" required autofocus autocomplete="travel_title" />
                <x-input-error class="mt-2" :messages="$errors->get('travel_title')" />
            </div>

            <!-- short_description -->
            <div>
                <x-input-label for="short_description" :value="__('Краткое описание')" />
                <textarea id="short_description" name="short_description" rows="3" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="short_description">{{ old('short_description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('short_description')" />
            </div>

            <div class="flex justify-between gap-4">
                <!-- start_at -->
                <div style="width: 49%;">
                    <x-input-label for="start_at" :value="__('Дата начала')" />
                    <x-text-input id="start_at" name="start_at" type="date" class="mt-1 block w-full" :value="old('start_at')" required autofocus autocomplete="start_at" />
                    <x-input-error class="mt-2" :messages="$errors->get('travel_title')" />
                </div>

                <!-- travel_main_photo -->
                <div style="width: 49%;">
                    <x-input-label for="travel_main_photo" :value="__('Обложка путешествия')" />
                    <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                        <x-text-input id="travel_main_photo" name="travel_main_photo" type="file" class="block w-full" :value="old('travel_main_photo')" required/>
                    </div>
                    <x-input-error :messages="$errors->get('travel_main_photo')" class="mt-2" />
                </div>
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
@endauth
