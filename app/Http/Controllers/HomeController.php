<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller 
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

    }
    /*
    get the spotify album tracks
    */
    function getAlbumTracks() {
        $album_id=Input::get("albumid") ;
        $end_point='/albums/'.$album_id.'/tracks';
        $data=$this->getSpotifyEndPointsData($end_point);
        return view('albumtracks',["album_id"=>$album_id,'album_tracks'=>$data]);
    }
    function getSpotifyEndPointsData($end_point){
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.spotify.com'.$end_point,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_SSL_VERIFYPEER=> 0
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);    
        return json_decode($resp);
    }
}
