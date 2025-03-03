<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelImage extends Model
{
    protected $table = 'travel_images';
    protected $fillable = [
        'image_title',
        'file_name',
        'url',
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
