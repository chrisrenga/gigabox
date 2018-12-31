<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public $gigabox;

    protected function __construct()
    {
        $this->property = env('GIGABOX_URL') . '/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'channels' => json_decode(file_get_contents(public_path('/results.json')))->e2service,
            'current' => $this->get('web/subservices')->e2service
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = $this->get('/web/getservices?sRef=1:7:1:0:0:0:0:0:0:0:FROM%20BOUQUET%20%22userbouquet.B2_IPTV__tv_.tv%22%20ORDER%20BY%20bouquet');

        $file = fopen('results.json', 'w');

        fwrite($file, json_encode($response));

        fclose($file);

        return 'ok';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $url = $request['command'];

        $this->curl($url);
    }

    protected function curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->gigabox . $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, true); // remove body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        // Then, after your curl_exec call:
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        curl_close($curl);

        return $response;
    }

    protected function get($url)
    {
        return simplexml_load_file($this->gigabox . $url);
    }
}
