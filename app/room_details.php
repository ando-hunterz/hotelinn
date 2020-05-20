<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class room_details extends Model
{
    protected $table = 'room_details';

    public function hotel()
    {
        return $this->belongsTo('App\Hotel');
    }

    public function room_description(){
        return $this->hasMany('App\Room_description');
    }
}
