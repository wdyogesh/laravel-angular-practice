<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// models
use App\Country;

class MasterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Master Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the request relatetd to master table data.
    |
    */

    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $Request)
    {
        $this->request = $Request;
    }

    /**
     *  Function to get phone codes.
     *
     *  @return : phonecode, id.
     *
     *  Created By : RaHHuL | Created On : 21 sept 2018
     **/
    public function getPhoneCodes(Country $Country)
    {
        try {

            $resultSet = $Country::orderBy('iso')->get(['id', 'phonecode', 'iso']);

            return response()->json([
                'status' => 'success',
                'data' => $resultSet
            ], 200);
        } catch (\Exception $ex) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $ex->getMessage(),
                'error_details' => 'on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile(),
            ], 200);
        }
    }

    /**
     *  Function to get country names.
     *
     *  @return : countryname, id.
     *
     *  Created By : Yashwant | Created On : 06 oct 2018
     **/
    public function getCountryNames(Country $Country)
    {
        #code
        try {
            $resultSet = $Country->orderBy('name')->get(['id', 'name']);

            return response()->json([
                'status' => 'success',
                'data' => $resultSet
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $ex->getMessage(),
                'error_details' => 'on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile(),
            ], 200);
        }
    }
}
