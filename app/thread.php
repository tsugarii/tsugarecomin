<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class thread extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'thread_key';
}
