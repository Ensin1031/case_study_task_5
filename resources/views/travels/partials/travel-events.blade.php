<section>
    <header>
        <div class="flex justify-between items-center" style="min-height: 2.2rem;">
            <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                События путешествия
            </h2>
            @auth
                @if(!!$can_edit)
                    <x-secondary-button
                        class="w-fit"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'create-travel-event-{{ $travel->id }}')"
                    >{{ __('Добавить событие') }}</x-secondary-button>
                @endif
            @endauth
        </div>
    </header>
    <div class="mt-6 space-y-6">
        <hr style="margin: 0;">
        @foreach($travel->travel_events as $travel_event)
            <x-travel-event-card :event="$travel_event" :can_edit="$can_edit" :redirect_to="$redirect_to " :query_parameters="$travel->id" class="mt-2"/>
            <hr style="margin: 0;">
        @endforeach
    </div>
</section>

@auth
    <x-modal name="create-travel-event-{{ $travel->id }}" focusable>
        <form method="post" action="{{ route('travel-events.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6 flex flex-col gap-2" enctype="multipart/form-data">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Создать запись события путешествия') }}
            </h2>

            <!-- travel_id -->
            <div style="display: none;">
                <x-text-input id="travel_id" type="number" name="travel_id" :value="$travel->id" required/>
            </div>

            <!-- event_title -->
            <div>
                <x-input-label for="event_title" :value="__('Заголовок события')" />
                <x-text-input id="event_title" name="event_title" type="text" minlength="10" maxlength="70" class="mt-1 block w-full" :value="old('event_title')" autofocus autocomplete="event_title" />
                <x-input-error class="mt-2" :messages="$errors->get('event_title')" />
            </div>

            <!-- event_description -->
            <div>
                <x-input-label for="event_description" :value="__('Описание')" />
                <textarea id="event_description" name="event_description" rows="7" class="mt-1 block w-full" style="resize: vertical;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" autocomplete="event_description">{{ old('event_description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('event_description')" />
            </div>

            <div class="flex justify-between gap-4">
                <!-- event_at -->
                <div style="width: 49%;">
                    <x-input-label for="event_at" :value="__('Дата и время события')" />
                    <x-text-input id="event_at" name="event_at" type="datetime-local" class="mt-1 block w-full" :value="old('event_at')" required autofocus autocomplete="event_at" />
                    <x-input-error class="mt-2" :messages="$errors->get('event_at')" />
                </div>

                <!-- event_price -->
                <div style="width: 49%;">
                    <x-input-label for="event_price" :value="__('Потрачено за событие')" />
                    <x-text-input id="event_price" name="event_price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('event_price')" autocomplete="event_price" />
                    <x-input-error class="mt-2" :messages="$errors->get('event_price')" />
                </div>
            </div>

            {{-- event_score --}}
            <div>
                <x-input-label for="event_score" :value="__('Оценка события')" />
                <div class="flex justify-between gap-4 mt-1">
                    <select name="event_score" id="event_score" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                        @foreach(\App\Models\TravelEvent::EVENT_SCORES as $score)
                            <option value="{{ $score['id'] }}">{{ $score['title'] }}</option>
                        @endforeach
                    </select>
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
