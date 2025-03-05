@props(['score_data'])

@if ($score_data)
    <div style="color: #ffffff;width: fit-content;">
        <span class="w-full" style="padding: .125rem .725rem;border-radius: 9px;background-color: {{ $score_data['color'] }};">{{ $score_data['title'] }}</span>
    </div>
@endif
