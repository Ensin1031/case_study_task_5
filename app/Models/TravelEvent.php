<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelEvent extends Model
{
    protected $table = 'travel_events';
    protected $fillable = [
        'event_title',
        'event_description',
        'event_at',
        'event_price',
        'event_score',
        'travel_id',
    ];

    const SCORE_REALLY_DID_NOT_LIKE = 1;
    const SCORE_DID_NOT_LIKE = 2;
    const SCORE_AVERAGE = 3;
    const SCORE_LIKE = 4;
    const SCORE_REALLY_LIKED = 5;

    const SCORE_REALLY_DID_NOT_LIKE_ELEMENT = [
        'id' => TravelEvent::SCORE_REALLY_DID_NOT_LIKE,
        'title' => 'Очень не понравилось',
        'color' => 'red',
    ];
    const SCORE_DID_NOT_LIKE_ELEMENT = [
        'id' => TravelEvent::SCORE_DID_NOT_LIKE,
        'title' => 'Не понравилось',
        'color' => 'darkred',
    ];
    const SCORE_AVERAGE_ELEMENT = [
        'id' => TravelEvent::SCORE_AVERAGE,
        'title' => 'Средне понравилось',
        'color' => 'darkblue',
    ];
    const SCORE_LIKE_ELEMENT = [
        'id' => TravelEvent::SCORE_LIKE,
        'title' => 'Понравилось',
        'color' => 'darkgreen',
    ];
    const SCORE_REALLY_LIKED_ELEMENT = [
        'id' => TravelEvent::SCORE_REALLY_LIKED,
        'title' => 'Очень понравилось',
        'color' => 'green',
    ];

    const EVENT_SCORES = [
        TravelEvent::SCORE_REALLY_DID_NOT_LIKE_ELEMENT,
        TravelEvent::SCORE_DID_NOT_LIKE_ELEMENT,
        TravelEvent::SCORE_AVERAGE_ELEMENT,
        TravelEvent::SCORE_LIKE_ELEMENT,
        TravelEvent::SCORE_REALLY_LIKED_ELEMENT,
    ];

    public function score_data(): array
    {
        switch ($this->event_score) {
            case self::SCORE_REALLY_DID_NOT_LIKE: {return self::SCORE_REALLY_DID_NOT_LIKE_ELEMENT;}
            case self::SCORE_DID_NOT_LIKE: {return self::SCORE_DID_NOT_LIKE_ELEMENT;}
            case self::SCORE_AVERAGE: {return self::SCORE_AVERAGE_ELEMENT;}
            case self::SCORE_LIKE: {return self::SCORE_LIKE_ELEMENT;}
            case self::SCORE_REALLY_LIKED: {return self::SCORE_REALLY_LIKED_ELEMENT;}
            default: {return self::SCORE_REALLY_DID_NOT_LIKE_ELEMENT;}
        }
    }

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class, 'travel_id', 'id');
    }

    public function event_images(): HasMany
    {
        return $this->hasMany(TravelImage::class, 'travel_event_id', 'id');
    }

    public function event_coordinates(): HasMany
    {
        return $this->hasMany(EventMapCoordinates::class, 'travel_event_id', 'id');
    }

    public function event_map_coordinates(): array
    {
        return coordinates_view_data(coordinates: $this->event_coordinates()->get());
    }

}
