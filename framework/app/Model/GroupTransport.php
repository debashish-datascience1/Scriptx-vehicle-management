<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GroupTransport extends Model
{
    protected $table = 'group_transport';
    protected $fillable = ['group_name'];
}