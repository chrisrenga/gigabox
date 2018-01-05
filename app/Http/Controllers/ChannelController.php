<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public $gigabox = 'http://192.168.2.4/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'channels' => json_decode(file_get_contents(public_path("/results.json")))->e2service,
            'current' => $this->get('web/subservices')->e2service
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $response = $this->get('web/getservices?sRef=1:7:1:0:0:0:0:0:0:0:FROM BOUQUET "userbouquet.B_IPTV__tv_.tv" ORDER BY bouquet');
        
        $file = fopen('results.json', 'w');
        
        fwrite($file, json_encode($response));
        
        fclose($file);

        return 'ok';

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function curl($url)
    {
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $this->gigabox . $url); 
        curl_setopt($curl, CURLOPT_HEADER, 1); 
        curl_setopt($curl, CURLOPT_NOBODY, TRUE); // remove body 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);  

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
