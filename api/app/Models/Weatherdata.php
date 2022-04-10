<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weatherdata extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql1t';
    protected $table = 'Weatherdata';
    protected $fillable = ['Station_name','Date','Time','Temperature','Dewpoint','Station_airpressure','Sealevel_airpressure','Sight','Windspeed','Rainfall','Snowdepth','FRSHTT','Overcast','Winddirection'];
}
