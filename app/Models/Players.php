<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = [
        'name_player',
        'family_player',
        'city',
        'plaer_from_id'   
    ];
    public $timestamps = false;
}
