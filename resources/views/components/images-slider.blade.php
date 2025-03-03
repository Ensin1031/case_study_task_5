@props(['images' => [], 'size' => 150, 'title_font_size' => 12, 'can_edit' => false, 'redirect_to' => '', 'query_parameters' => []])

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
                width: {{ $size * $view_count + $padding * ($view_count - 1) }}px;
                overflow: hidden;
                margin: 0 {{ $size / $padding }}px;
                & .images-work-container {
                    display: flex;
                    width: fit-content;
                    & .image-title-container {
                        padding-bottom:10px;
                        cursor: default;
                        font-size: {{ $title_font_size }}px;
                        text-wrap: wrap;
                        overflow: hidden;
                        width: {{ $size }}px;
                    }
                    & .image-item-container {
                        width: {{ $size }}px;
                        height: {{ $size }}px;
                        margin-right: {{ $padding }}px;
                        overflow: hidden;
                        & img {
                            object-fit: cover;
                            width: 100%;
                            height: 100%;
                        }
                    }
                    & .image-management-container {
                        width: {{ $size }}px;
                    }
                }
            }
            .images-prev-btn, .images-next-btn {
                height: {{ $size }}px;
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
            .images-prev-btn {
                padding-left: {{ $size / $padding }}px;
            }
            .images-next-btn {
                padding-right: {{ $size / $padding }}px;
            }
            .arrow {
                top: -5px;
                content: "";
                display: inline-block;
                width: {{ $size / 10 }}px;
                height: {{ $size / 10 }}px;
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
        function slideToPrev() {
            const container = document.querySelector('#carouselWorkContainer')
            const blockValue = Number(!!container.style.webkitTransform ? container.style.webkitTransform.replace('translate3d(', '').replace('-', '').replace('px, 0px, 0px)', '') : '0')
            const containerWidth = container.scrollWidth
            const step = {{ $size + $padding }};
            let nextValue = blockValue - step;
            if (nextValue < 0) {
                nextValue = containerWidth - step;
            }
            setTimeout(function ()
            {
                container.style.webkitTransitionDuration = "0.5s";
                container.style.webkitTransitionTimingFunction = "marginLeft";
                container.style.webkitTransform = `translate3d(-${nextValue}px, 0, 0)`;
            }, 0);
        }
        function slideToNext() {
            const container = document.querySelector('#carouselWorkContainer')
            const blockValue = Number(!!container.style.webkitTransform ? container.style.webkitTransform.replace('translate3d(', '').replace('-', '').replace('px, 0px, 0px)', '') : '0')
            const containerWidth = container.scrollWidth
            const step = {{ $size + $padding }};
            let nextValue = blockValue + step;
            if (nextValue >= containerWidth) {
                nextValue = 0
            }
            setTimeout(function ()
            {
                container.style.webkitTransitionDuration = "0.5s";
                container.style.webkitTransitionTimingFunction = "marginLeft";
                container.style.webkitTransform = `translate3d(-${nextValue}px, 0, 0)`;
            }, 0);
        }
    </script>
    <div class="w-full">
        <div class="images-container">
            <div class="images-prev-btn" onclick="slideToPrev()">
                <div class="arrow left-arrow" aria-hidden="true"></div>
            </div>
            <div class="images-slide-container">
                <div class="images-work-container m-0" id="carouselWorkContainer">
                    @foreach($images as $image)
                        <div>
                            <div class="image-title-container">
                                {{ $image->image_title }}
                            </div>
                            <div class="image-item-container">
                                <a href="{{ url($image->url) }}" target="_blank"><img src="{{ $image->url }}" alt="{{ $image->image_title }}"></a>
                            </div>
                            @if($can_edit)
                                <div class="image-management-container flex justify-around items-center">
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
            <div class="images-next-btn" onclick="slideToNext()">
                <div class="arrow right-arrow" aria-hidden="true"></div>
            </div>
        </div>
    </div>
@endif
