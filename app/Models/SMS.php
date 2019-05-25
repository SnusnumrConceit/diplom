<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    protected $table = 'sms';

    protected $fillable = ['phone', 'message', 'link'];
}
