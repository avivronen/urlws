<?php

namespace App\Http\Controllers;

use App\Models\Link as Link;

use Illuminate\Http\Request;
//https://github.com/ivanakimov/hashids.php
use Hashids\Hashids;

class LinkController extends Controller
{

    private  $hashIdsObject = null;

    public function __construct()
    {
        //$this->middleware('core');
        $this->hashIdsObject  = new Hashids(getenv('HASH_KEY'));
    }

    private function getHashIdObject(){
        return $this->hashIdsObject;
    }

    private function returnFalseResponse() {
        return response()->json(false, 500);
    }


    /**
     * @param $shortUrl string
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLongLink($shortUrl)
    {
       // return $this->returnFalseResponse();
        if($shortUrl == '') return response()->json(false, 500);

        $link = Link::where('shortUrl', $shortUrl)->first();
        if(!$link ) {
            return $this->returnFalseResponse();
        }
        return response()->json($link->url);
    }

    /**
     * @param Request $request $request->url string
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        $url = $request->post('url');

        //Saving Long link to DB
        $link = Link::saveLink($url);

        if(! $link  instanceof Link ) {
            return $this->returnFalseResponse();
        }

        //Generating Short Link
        $shortUrl = $this->getHashIdObject()->encode($link->id);

        if($shortUrl != '') {
            $link->shortUrl = $shortUrl;
            //Saving Short Link to DB
            if( $link->save() )
                return response()->json($shortUrl, 201);
        }

        return $this->returnFalseResponse();
    }
}