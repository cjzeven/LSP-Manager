<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playing extends Model
{
    protected $table = 'playings';

    protected $fillable = ['name', 'target_budget', 'datetime'];

    public function items()
    {
        return $this->hasMany(PlayingItem::class);
    }
}
