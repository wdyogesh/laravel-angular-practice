<?php

namespace App\Http\Controllers\Admin;

use App\Cms;
use Exception;
use Validator;
use App\Packages\Logger\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CMSController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    |  CMS Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the CMS related request from admin.
    |
    */
    private $request;

    private $model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $Request, Cms $Cms)
    {

        $this->request = $Request;

        $this->model = $Cms;
    }

    /*
     *  Function to get cms list.
     *
     *  Created By : Shagun | Created On : 11th March 2019
    **/
    public function getCmsList()
    {
        try {

            $resultSet = $this->model->orderBy('flag', 'asc')->get();

            if (!empty($resultSet)) {
                $resultSet = $resultSet->toArray();
            }

            return response()->json([
                'status' => 'success',
                'data' => $resultSet,
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
     *  Function to get cms details.
     *
     *  @param @var id as integer
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 11th March 2019
     **/
    public function getCmsDetail($id)
    {
        try {

            $resultSet = $this->model->whereId($id)->first();

            if (!empty($resultSet)) {
                $resultSet = $resultSet->toArray();
            }

            return response()->json([
                'status' => 'success',
                'data' => $resultSet,
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
     *  Function to update cms details.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 11th March 2019
     **/
    public function updateCMSDetail()
    {
        try {


            $validator = Validator::make($this->request->all(), [
                'id' => 'required',
                'display_title' => 'required',
                'content' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $this->model->whereId($this->request->id)->update([
                'display_title' => $this->request->display_title,
                'content' => $this->request->content,
            ]);

            Log::write([
                'operation' => 'Edit CMS ' . $this->request->display_title . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Template updated successfully'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Template updated successfully',
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Edit CMS' . $this->request->display_title . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'error',
                'message' => $ex->getMessage() . ' on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $ex->getMessage(),
                'error_details' => 'on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile(),
            ], 200);
        }
    }
}
