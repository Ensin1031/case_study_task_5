<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    protected $table = 'travel';
    protected $fillable = [
        'travel_title',
        'short_description',
        'main_photo_url',
        'start_at',
        'end_at',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function travel_events(): HasMany
    {
        return $this->hasMany(TravelEvent::class, 'travel_id', 'id');
    }

    public function full_price(): float
    {
        $result_price = 0.00;
        $events = $this->travel_events()->get();
        foreach ($events as $event) {$result_price += $event->event_price;}

        return $result_price;
    }

    public function travel_images(): HasMany
    {
        return $this->hasMany(TravelImage::class, 'travel_id', 'id');
    }

}
