<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Living extends Model
{
    protected $table = 'livings';

    public function items()
    {
        return $this->hasMany('App\LivingItem');
    }
}
