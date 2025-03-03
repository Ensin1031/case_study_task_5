@props(['travel', 'can_edit' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($travel)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
{{--            <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">--}}
{{--                <div class="w-full" style="height: 10rem;overflow: hidden;">--}}
{{--                    <img src="{{ url('Image/books/'.$book->image) }}" alt="">--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="flex flex-col pl-4 w-full">
                <div class="flex items-center justify-between mr-4 pb-2">
                    <div class="flex items-center gap-2">
                        <a class="hover:underline" href="{{ route('travels.travel-show', $travel->id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->travel_title }}</h2></a>
                    </div>
                </div>

                <div style="display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;">{{ $travel->short_description }}</div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-travel-{{ $book->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    <x-danger-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-travel-{{ $book->id }}')"
                    >{{ __('Удалить') }}</x-danger-button>
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)

        <x-modal name="update-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать запись книги') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
                </div>

                {{-- category --}}
                <div>
                    <x-input-label for="category_id_{{ $book->id }}" :value="__('Категория')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="category_id" id="category_id_{{ $book->id }}" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if ($category->id == old('category_id', $book->category_id))
                                        selected
                                    @endif
                                >{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- author --}}
                <div>
                    <x-input-label for="author_id" :value="__('Автор')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="author_id" id="author_id" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}"
                                    @if ($author->id == old('author_id', $book->author_id))
                                        selected
                                    @endif
                                >{{ $author->author_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- title --}}
                <div>
                    <x-input-label for="title" :value="__('Наименование')" />
                    <x-text-input id="title" name="title" type="text" minlength="3" maxlength="120" class="mt-1 block w-full" :value="old('title', $book->title)" required autofocus autocomplete="title" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                {{-- description --}}
                <div>
                    <x-input-label for="description" :value="__('Краткое описание')" />
                    <textarea id="description" name="description" rows="7" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="description">{{ old('description', $book->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-between gap-4">
                    {{-- price --}}
                    <div style="width: 49%;">
                        <x-input-label for="price" :value="__('Цена')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('price', $book->price)" required autocomplete="price" />
                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                    </div>

                    {{-- published_year --}}
                    <div style="width: 49%;">
                        <x-input-label for="published_year" :value="__('Год публикации')" />
                        <x-text-input id="published_year" name="published_year" type="number" min="-9999" max="9999" class="mt-1 block w-full" :value="old('published_year', $book->published_year)" required autocomplete="published_year" />
                        <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
                    </div>
                </div>

                <div class="flex justify-between gap-4">
                    {{-- status --}}
                    <div style="width: 49%;">
                        <x-input-label for="status_{{ $book->id }}" :value="__('Статус')" />
                        <select name="status" id="status_{{ $book->id }}" class="block w-full mt-1" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                            @foreach($statuses as $status)
                                <option value="{{ $status['id'] }}"
                                    @if ($status['id'] == old('status', $book->status))
                                        selected
                                    @endif
                                >{{ $status['title'] }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    {{-- image --}}
                    <div style="width: 49%;">
                        <x-input-label for="image" :value="__('Изображение книги')" />
                        <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                            <x-text-input id="image" name="image" type="file" class="block w-full" :value="old('image')" required/>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
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

        <x-modal name="update-book-status-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.update-status', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    Изменить статус книги на "{{ $book->next_status()['title'] }}"?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
                </div>

                <!-- status -->
                <div style="display: none;">
                    <x-text-input id="status" type="number" name="status" :value="$book->next_status()['id']" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Изменить статус') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись книги') }}
                </h2>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
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

    @endif

@endif
