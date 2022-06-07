<?php

namespace App\Http\Controllers;

use App\Models\Weatherdata;
use http\Env\Response;
use http\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\WeatherStationKeyEnum;
use mysql_xdevapi\Exception;

class WeatherdataController extends Controller
{
    public function index()
    {
        $weatherdata = DB::connection('mysql1')->select('SELECT * FROM Weatherdata');
        return response()->json($weatherdata, 200);
    }

    public function show($id)
    {
        $weatherdata = DB::connection('mysql1')->select('SELECT * FROM Weatherdata WHERE id = :id', ['id' => $id]);
        return response()->json($weatherdata, 200);
    }

    public function search($station_name, $type)
    {
        $weatherdata = DB::connection('mysql1')->select('SELECT * FROM Weatherdata WHERE Station_name = :station_name', ['station_name' => $station_name]);
        return response()->json($weatherdata, 200);
    }

    public function getData(Request $request)
    {
        $collection = collect($request);
        $keys = $collection->keys();
        $keyList = [];
        $givenKeyList = [];
        $datef = '';
        $datet = '';
        $timestampf = '';
        $timestampt = '';
        $timestamp_ago = '';
        foreach ($keys as $key) {
            if (WeatherStationKeyEnum::isValidName($key)) {
                if($key == 'Date_from'){
                    $datef = $collection[$key];
                    continue;
                }
                if($key == 'Date_to'){
                    $datet = $collection[$key];
                    continue;
                }
                if($key == 'timestamp_from'){
                    $timestampf = $collection[$key];
                    continue;
                }
                if($key == 'timestamp_to'){
                    $timestampt = $collection[$key];
                    continue;
                }
                if($key == 'timestamp_ago'){
                    $timestamp_ago = $collection[$key];
                    continue;
                }

                if($collection[$key] != ''){
                    $givenKeyList[] = $key;
                }
                $keyList[] = $key;
            }
        }
        $selectiveData = DB::connection('mysql1')->table('Weatherdata');
        foreach ($keyList as $key) {
            $selectiveData = $selectiveData->addSelect($key);
        }
        foreach ($givenKeyList as $givenKey) {
            $selectiveData = $selectiveData->where($givenKey, $collection[$givenKey]);
        }

        if($datef != ''){
            $selectiveData = $selectiveData->whereDate('Date', '>=', Date($datef));
        }
        if($datet != ''){
            $selectiveData = $selectiveData->whereDate('Date', '<=', Date($datet));
        }
        if($timestampf != ''){
            $selectiveData = $selectiveData->where('timestamp', '<=', $timestampf);
        }
        if($timestampt != ''){
            $selectiveData = $selectiveData->where('timestamp', '<=', $timestampt);
        }
        if($timestamp_ago != ''){
            $past_time = time() - $timestamp_ago;
            $selectiveData = $selectiveData->where('timestamp', '<=', $past_time);
        }
        $data = $selectiveData->get();
        //$selectiveData = $selectiveData->where("Station_name", $station_name)->get();
        return response()->json($data, 200);

    }

    public function store(Request $request)
    {
        $array = $request['WEATHERDATA'];
        foreach ($array as $data) {
            DB::connection('mysql1')->insert('insert into Weatherdata (id, Station_name, Date, Time, Temperature, Dewpoint, Station_airpressure, Sealevel_airpressure, Sight, Windspeed, Rainfall, Snowdepth, FRSHTT, Overcast, Winddirection, timestamp) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$data["STN"], $data["DATE"], $data["TIME"], $data["TEMP"], $data["DEWP"], $data["STP"], $data["SLP"], $data["VISIB"], $data["WDSP"], $data["PRCP"], $data["SNDP"], $data["FRSHTT"], $data["CLDC"], $data["WNDDIR"], time()]);
        }
        return response()->json($request, 200);
    }
}
