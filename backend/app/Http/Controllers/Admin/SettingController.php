<?php

namespace App\Http\Controllers\Admin;

use DB;
use Exception;
use Validator;
use App\Api_keys;
use App\Key_meta;
use App\PaymentGateway;
use Illuminate\Http\Request;
use App\Packages\Logger\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Packages\EnvUpdater\EnvUpdater;

class SettingController extends Controller
{
    public function getKeysMeta()
    {
        $data = Key_meta::select('id', 'title')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function addKeysMeta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'  => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $title_key = str_replace(' ', '_', strtolower($request->title));
            $addKey = Key_meta::firstOrCreate([
                'title' => $request->title,
                'title_key' => $title_key
            ]);

            Log::write([
                'operation' => 'Add New Api meta ' . $request->title . ' by ' . Auth::user()->email,
                'request_details' => $request->all(),
                'status' => 'success',
                'message' => 'Api added to the list successfully.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Api added to the list successfully.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Add New Api meta ' . $request->title . ' by ' . Auth::user()->email,
                'request_details' => $request->all(),
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

    public function getApiDetails($id)
    {
        $api = Key_meta::where('id', '=', $id)->first();

        if ($api != NULL) {
            return response()->json([
                'status' => 'success',
                'data' => $api
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The requested API could not be found.'
            ]);
        }
    }

    public function editApi(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title'  => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $api = Key_meta::find($request->id);

            $title_key = str_replace(' ', '_', strtolower($request->title));

            $api->title = $request->title;
            $api->title_key = $title_key;

            $result = $api->save();

            if ($result) {

                Log::write([
                    'operation' => 'Edit Api meta ' . $request->title . ' by ' . Auth::user()->email,
                    'request_details' => $request->all(),
                    'status' => 'success',
                    'message' => 'Api Updated Successfully'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Api Updated Successfully'
                ], 200);
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Edit Api meta ' . $request->title . ' by ' . Auth::user()->email,
                'request_details' => $request->all(),
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

    public function deleteApi($id)
    {
        try {

            $api = Key_meta::find($id);
            $api_keys = $api->api_keys()->get();
            $result = $api->delete();
            $api->api_keys()->delete();

            foreach ($api_keys as $key) {
                $envKey = $api->title_key . "_" . $key->key_title;
                EnvUpdater::delEnvironmentValue($envKey);
            }

            if ($result) {

                Log::write([
                    'operation' => 'Delete Api Meta ' . $api->title . ' by ' . Auth::user()->email,
                    'request_details' => request()->all(),
                    'status' => 'success',
                    'message' => 'Api and keys Deleted Successfully'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Api and keys Deleted Successfully'
                ], 200);
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Delete Api Meta ' . $api->title . ' by ' . Auth::user()->email,
                'request_details' => request()->all(),
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

    public function getKey($id)
    {
        try {

            $findkey = DB::table('api_keys')->where('key_meta_id', $id)->get();
            $response = [];

            foreach ($findkey as $key => $value) {
                $response[$key] = $value;
            }

            return response()->json([
                'status'    => 'success',
                'data'      => $response
            ]);
        } catch (\Exception $ex) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

    public function addkey(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id'  => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $keymeta = Key_meta::where('id', $request->id)->first();

            if (empty($keymeta)) {

                throw new \Exception('API does not exist', 1);
            }

            $apikeys = Api_keys::where('key_meta_id', $request->id)->get();

            if (count($apikeys) == 0) {
                //New Record
                $input = $request->all();

                foreach ($input['keys'] as $key => $value) {

                    $savekey = Api_keys::create([
                        'key_meta_id'   => $request->id,
                        'key_title'     => $value['key_title'],
                        'key_value'     => $value['key_value']
                    ]);

                    $envkey = $keymeta->title_key . '_' . $value['key_title'];
                    $envvalue = $value['key_value'];

                    EnvUpdater::setEnvironmentValue($envkey, $envvalue);

                }

                Log::write([
                    'operation' => 'Add Api Keys on ' . $keymeta->title . ' by ' . Auth::user()->email,
                    'request_details' => $request->all(),
                    'status' => 'success',
                    'message' => 'Key details saved successfully'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Key details saved successfully'
                ]);
            } else {
                //Existing record
                $input = $request->all();

                $path = base_path('.env');

                foreach ($input['keys'] as $key => $value) {

                    $apikeys = Api_keys::where([
                        ['key_meta_id', '=', $request->id],
                        ['key_title', '=', $value['key_title']],
                    ])->first();

                    if (!empty($apikeys)) {

                        $apikeys->key_value = $value['key_value'];
                        $apikeys->save();

                        $envkey = $keymeta->title_key . '_' . $value['key_title'];
                        $envvalue = $value['key_value'];

                        EnvUpdater::setEnvironmentValue($envkey, $envvalue);

                    } else {

                        $savekey = Api_keys::create([
                            'key_meta_id'   => $keymeta->id,
                            'key_title'     => $value['key_title'],
                            'key_value'     => $value['key_value']
                        ]);

                        $envkey = $keymeta->title_key . '_' . $value['key_title'];
                        $envvalue = $value['key_value'];

                        EnvUpdater::setEnvironmentValue($envkey, $envvalue);

                    }
                }

                Log::write([
                    'operation' => 'Add Api Keys on ' . $keymeta->title . ' by ' . Auth::user()->email,
                    'request_details' => $request->all(),
                    'status' => 'success',
                    'message' => 'Key details saved successfully'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Key details saved successfully'
                ]);
            }
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Add Api Keys on ' . $keymeta->title . ' by ' . Auth::user()->email,
                'request_details' => $request->all(),
                'status' => 'error',
                'message' => $ex->getMessage() . ' on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

    public function deleteKey($id)
    {
        try {
            $api_key = Api_keys::find($id);
            $keymeta = Key_meta::find( $api_key->key_meta_id);
            $result = $api_key->delete();

            $envkey = $keymeta->title_key . '_' . $api_key->key_title;

            if ($result) {

                EnvUpdater::delEnvironmentValue($envkey);

                Log::write([
                    'operation' => 'Delete Api Key' . $api_key->title . ' by ' . Auth::user()->email,
                    'request_details' => request()->all(),
                    'status' => 'success',
                    'message' => 'Api Key Deleted Successfully'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Api Key Deleted Successfully'
                ], 200);
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Delete Api Key' . $api_key->title . ' by ' . Auth::user()->email,
                'request_details' => request()->all(),
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

    public function addPaymentGateway(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'            => 'required | alpha',
                'publishable_key' => 'required',
                'secret_key'      => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }
            $addAdmin = PaymentGateway::firstOrCreate([
                'name' => $request->name,
                'merchant_id' => $request->merchant_id,
                'publishable_key' => $request->publishable_key,
                'secret_key' => $request->secret_key,
                'live_api_key'    => $request->live_api_key,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Gateway added Successfully.'
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $ex->getMessage(),
                'error_details' => 'on line : ' . $ex->getLine() . ' on file : ' . $ex->getFile(),
            ], 200);
        }
    }

    public function getPaymentGateway($id)
    {
        try {

            $data = PaymentGateway::where('id', $id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

    public function getEmailDetails()
    {
        //return "hello";
        $driver = config('mail.driver');
        $host =  config('mail.host');
        $port = config('mail.port');
        $username = config('mail.username');
        $password = config('mail.password');
        $encryption = config('mail.encryption');

        return response()->json([
            'driver' => $driver,
            'host'    => $host,
            'port'    => $port,
            'username' => $username,
            'password' => $password,
            'encryption' => $encryption,
        ], 200);
    }

    /*public function setEmailDetails(Request $request)
    {
        config(['mail.driver' => $request->driver]);
        config(['mail.host' => $request->host]);
        config(['mail.port' => $request->port]);
        config(['mail.username'=> $request->username]);
        config(['mail.password' => $request->password]);
        config(['mail.encryption'=> $request->encryption]);

        return config('mail.username');
        return response()->json([
            'status'=>'success',
            'message' => 'Email Setting saved Successfully'
        ],200);
    }*/

    public function getAllKeys()
    {
        $apikeys = Api_keys::all();
        return response()->json([
            'status' => 'success',
            'data' => $apikeys
            ]);
        return $apikeys;
    }
}
