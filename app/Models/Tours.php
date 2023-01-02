<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    use HasFactory;

    protected $table = 'tours';

    protected $fillable = [
       'name_tour', 'date','city_tour',
        'link','tour_user_id',
    ];
    public $timestamps = false;
}
