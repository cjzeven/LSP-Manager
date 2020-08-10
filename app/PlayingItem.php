<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayingItem extends Model
{
    protected $table = 'playing_items';

    protected $fillable = ['playing_id', 'datetime', 'amount', 'receipt_photo'];

    public function playing()
    {
        return $this->belongsTo(Playing::class);
    }

}
