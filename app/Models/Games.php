<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $fillable = ['tour_id','user_id','player_id','goal_for','goal_away','result','type'];

    public $timestamps = false;
}
