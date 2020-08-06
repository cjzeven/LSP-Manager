<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    protected $table = 'savings';

    protected $fillable = ['name', 'years', 'target', 'type'];

    public function items()
    {
        return $this->hasMany('App\SavingItem');
    }
}
