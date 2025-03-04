@props(['images' => [], 'travel_id' => 0, 'event_id' => 0, 'size' => 150, 'title_font_size' => 12, 'can_edit' => false, 'redirect_to' => '', 'query_parameters' => []])

@php
    $padding = 20;
    $view_count = 1
 @endphp

@if(count($images) > 0)
    <style>
        .images-container {
            display: flex;
            flex-direction: row;
            width: fit-content;
            align-items: center;
            & .images-slide-container {
                overflow: hidden;
                & .images-work-container {
                    display: flex;
                    width: fit-content;
                    & .image-title-container {
                        margin-bottom:10px;
                        cursor: default;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        text-wrap: wrap;
                    }
                    & .image-item-container {
                        overflow: hidden;
                        & img {
                            object-fit: cover;
                            width: 100%;
                            height: 100%;
                        }
                    }
                }
            }
            .images-prev-btn, .images-next-btn {
                display: flex;
                align-items: center;
                cursor: pointer;
            }
            .images-prev-btn:hover, .images-next-btn:hover {
                background-color: #eeeeee40;
            }
            .images-prev-btn:active, .images-next-btn:active {
                background-color: #eeeeee90;
            }
            .arrow {
                top: -5px;
                content: "";
                display: inline-block;
                border-right: 0.2em solid black;
                border-top: 0.2em solid black;
            }
            .right-arrow {
                transform: rotate(45deg);
            }
            .left-arrow {
                transform: rotate(-135deg);
            }
        }
    </style>
    <script>
        function slideToPrev(block_id, step) {
            const container = document.querySelector(`#${block_id}`)
            const blockValue = Number(!!container?.style?.webkitTransform ? container.style.webkitTransform.replace('translate3d(', '').replace('-', '').replace('px, 0px, 0px)', '') : '0')
            const containerWidth = container.scrollWidth
            let nextValue = blockValue - step;
            if (nextValue < 0) {
                nextValue = containerWidth - step;
            }
            setTimeout(function ()
            {
                container.style.webkitTransitionDuration = "0.5s";
                container.style.webkitTransform = `translate3d(-${nextValue}px, 0, 0)`;
                console.log('PREV ===', container.style.webkitTransform, nextValue)
            }, 0);
        }
        function slideToNext(block_id, step) {
            const container = document.querySelector(`#${block_id}`)
            const blockValue = Number(!!container?.style?.webkitTransform ? container.style.webkitTransform.replace('translate3d(', '').replace('-', '').replace('px, 0px, 0px)', '') : '0')
            const containerWidth = container.scrollWidth
            let nextValue = blockValue + step;
            if (nextValue >= containerWidth) {
                nextValue = 0
            }
            setTimeout(function ()
            {
                container.style.webkitTransitionDuration = "0.5s";
                container.style.webkitTransform = `translate3d(-${nextValue}px, 0, 0)`;
            }, 0);
        }
    </script>
    <div class="w-full">
        <div class="images-container">
            <div class="images-prev-btn" style="height: {{ $size }}px;padding-left: {{ $size / $padding }}px;" onclick="slideToPrev('carouselWorkContainer_{{ $travel_id }}_{{ $event_id }}', {{ $size + $padding }})">
                <div class="arrow left-arrow" aria-hidden="true" style="width: {{ $size / 10 }}px;height: {{ $size / 10 }}px;"></div>
            </div>
            <div class="images-slide-container" style="width: {{ $size * $view_count + $padding * ($view_count - 1) }}px;margin: 0 {{ $size / $padding }}px;">
                <div class="images-work-container m-0" id="carouselWorkContainer_{{ $travel_id }}_{{ $event_id }}">
                    @foreach($images as $image)
                        <div>
                            <div class="image-title-container" style="font-size: {{ $title_font_size }}px;width: {{ $size }}px;">
                                {{ $image->image_title }}
                            </div>
                            <div class="image-item-container" style="width: {{ $size }}px;height: {{ $size }}px;margin-right: {{ $padding }}px;">
                                <a href="{{ url($image->url) }}" target="_blank"><img src="{{ $image->url }}" alt="{{ $image->image_title }}"></a>
                            </div>
                            @if($can_edit)
                                <div class="image-management-container flex justify-around items-center" style="width: {{ $size }}px;">
                                    <x-secondary-button
                                        style="padding: .2rem;border: none;display: flex;width: 3rem;height: 3rem;"
                                        x-data=""
                                        data-image_id="{{$image->id}}"
                                        data-title="{{$image->image_title}}"
                                        x-on:click.prevent="$dispatch('open-modal', 'update-image-title')"
                                    >
                                        <x-update-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                    </x-secondary-button>
                                    <x-secondary-button
                                        style="padding: .2rem;border: none;display: flex;width: 3rem;height: 3rem;"
                                        x-data=""
                                        data-image_id="{{$image->id}}"
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-image')"
                                    >
                                        <x-delete-icon class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                    </x-secondary-button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="images-next-btn" style="height: {{ $size }}px;padding-right: {{ $size / $padding }}px;" onclick="slideToNext('carouselWorkContainer_{{ $travel_id }}_{{ $event_id }}', {{ $size + $padding }})">
                <div class="arrow right-arrow" aria-hidden="true" style="width: {{ $size / 10 }}px;height: {{ $size / 10 }}px;"></div>
            </div>
        </div>
    </div>
@endif
