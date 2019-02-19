<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'comment_key';
}
