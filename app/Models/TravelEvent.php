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
        'travel_id',
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class, 'travel_id', 'id');
    }

    public function event_images(): HasMany
    {
        return $this->hasMany(TravelImage::class, 'travel_event_id', 'id');
    }

}
