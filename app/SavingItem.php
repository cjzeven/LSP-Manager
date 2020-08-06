<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavingItem extends Model
{
    protected $table = 'saving_items';

    protected $fillable = ['saving_id', 'amount', 'datetime', 'receipt_photo'];
}
