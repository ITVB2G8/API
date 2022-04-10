<?php

namespace App\Http\Controllers;

use App\Models\Weatherdata;
use http\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeatherdataController extends Controller
{
    public function index()
    {
        $weatherdata = DB::connection('mysql1t')->select('SELECT * FROM Weatherdata');
        return response()->json($weatherdata, 200);
    }

    public function show($id){
        $weatherdata = DB::connection('mysql1t')->select('SELECT * FROM Weatherdata WHERE id = :id', ['id' => $id]);
        return response()->json($weatherdata, 200);
    }

    public function search($station_name, $type){
        $weatherdata = DB::connection('mysql1t')->select('SELECT * FROM Weatherdata WHERE Station_name = :station_name', ['station_name' => $station_name]);
        return response()->json($weatherdata, 200);
    }

    public function store(Request $request){
        $array = $request['WEATHERDATA'];
        foreach($array as $data){
            DB::connection('mysql1t')->insert('insert into Weatherdata (id, Station_name, Date, Time, Temperature, Dewpoint, Station_airpressure, Sealevel_airpressure, Sight, Windspeed, Rainfall, Snowdepth, FRSHTT, Overcast, Winddirection) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$data["STN"], $data["DATE"], $data["TIME"], $data["TEMP"], $data["DEWP"], $data["STP"], $data["SLP"], $data["VISIB"], $data["WDSP"], $data["PRCP"], $data["SNDP"], $data["FRSHTT"], $data["CLDC"], $data["WNDDIR"]] );
        }
        return response()->json($request, 200);
    }
}
