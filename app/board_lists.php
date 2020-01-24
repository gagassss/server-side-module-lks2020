<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class board_lists extends Model
{
    use Notifiable;
    protected $fillable = [
        'name', 'board_id', 'id', 'order'
    ];
}
