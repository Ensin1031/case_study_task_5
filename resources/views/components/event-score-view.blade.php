@props(['score_data'])

@if ($score_data)
    <div class="flex flex-row gap-1 size-fit">
        @for ($i = $score_data['id']; $i >= 1; $i--)
            <x-score-element :color="$score_data['color']" />
        @endfor
    </div>
@endif
