<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMapCoordinates extends Model
{
    protected $table = 'event_map_coordinates';
    protected $fillable = [
        'title',
        'description',
        'address_latitude',
        'address_longitude',
        'travel_id',
        'travel_event_id',
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class, 'travel_id', 'id');
    }

    public function travel_event(): BelongsTo
    {
        return $this->belongsTo(TravelEvent::class, 'travel_event_id', 'id');
    }

}
