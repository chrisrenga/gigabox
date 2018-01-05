<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;

class YoutubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = str_replace('hdts.md.german', ' ', strtolower($request->q));
        $query = str_replace('.', ' ', $query);
        $query = str_replace('bdrip', ' ', $query);
        $query = str_replace('german', ' ', $query);
        $query = str_replace('md', ' ', $query);
        $query = str_replace('ac3d', ' ', $query);
        $query = str_replace('webrip', ' ', $query);
        $query = str_replace('dvdrip', ' ', $query);

        $query = str_replace('720p', ' ', $query);
        $query = str_replace('hdtvrip', ' ', $query);
        $query = str_replace('dvdrip', ' ', $query);

        return Youtube::searchVideos($query . ' trailer');
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
        //
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
        //
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
}
