<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use Exception;
use Carbon\Carbon;
use App\Packages\Logger\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

// Repository
use App\Repository\UserRepository;

class AdminManagementController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    |  User Management Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the user management from admin.
    |
    */

    private $request;

    private $userRepository;


    public function __construct(Request $Request, UserRepository $UserRepository)
    {

        $this->request = $Request;

        $this->userRepository = $UserRepository;

        $this->profile_picture_upload_path = public_path() . config('app.img_path.profile_picture');
        $this->profile_picture_display_path = url('/') . config('app.img_path.profile_picture');
    }

    /**
     *  Function to get front-end user info.
     *
     *  @param Request data and user repository instance.
     *
     *  @return json response.
     *
     *  Created By : Shagun | Created On : 1st March 2019
     **/
    public function getAdminData(Request $request)
    {
        try {

            $query = User::where(['role_id' => $this->request->role_id])->with('mobileCode');

            if (isset($this->request->by_status)) {
                $query->where('is_active', $this->request->by_status);
            }

            if (!empty($this->request->by_text)) {
                $text = $this->request->by_text;

                $query->where(function ($q) use ($text) {
                    $q->where('first_name', 'like', "%$text%")
                        ->orwhere('last_name', 'like', "%$text%")
                        ->orwhere('email', 'like', "%$text%")
                        ->orwhere('mobile', 'like', "%$text%");
                });
            }

            $count = $query->count();

            $resultSet = $query->orderBy('id', 'desc')
                ->take($this->request->rows)
                ->skip($this->request->first)
                ->get();

            // $resultSet = $this->userRepository->getPaginatedData(['role_id' => $this->request->role_id], 'get', ['mobileCode'], $this->request->rows, $this->request->first);
            // $count = $this->userRepository->getCount(['role_id' => $this->request->role_id]);

            return response()->json([
                'status' => 'success',
                'data' => $resultSet,
                'image_path' => $this->profile_picture_display_path,
                'count' => $count
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

    /**
     *  Function to get front-end user info.
     *
     *  @param @var id as integer.
     *
     *  @return json response.
     *
     *  Created By : Shagun | Created On : 1st March 2019
     **/
    public function getAdminDetails($id)
    {
        try {
            $resultSet = $this->userRepository->getData(['id' => $id], 'first', ['mobileCode']);
            return response()->json([
                'status' => 'success',
                'data' => $resultSet,
                'image_path' => $this->profile_picture_display_path
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

        /**
     *  Function to get front-end user info.
     *
     *  @param @var id as integer.
     *
     *  @return json response.
     *
     *  Created By : Shagun | Created On : 27th Nov 2019
     **/
    public function getProfile()
    {
        try {
            $resultSet = $this->userRepository->getData(['id' => Auth::id()], 'first', ['mobileCode']);

            return response()->json([
                'status' => 'success',
                'data' => $resultSet,
                'image_path' => $this->profile_picture_display_path,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $ex->getMessage(),
                'error_details' => 'on line: ' . $ex->getLine() . 'on file: ' . $ex->getFile()
            ], 200);
        }
    }

    public function addAdmin()
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha',
                'email' => 'required | email',
                'mobile_code' => 'required',
                'mobile' => 'required',
                'password' => 'required | confirmed',
                'password_confirmation' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $data = $this->request;

            $resultSet = $this->userRepository->getData(['email' => $this->request->email], 'first');

            if (!empty($resultSet)) {
                throw new Exception('User already registered with same Email ID.', 1);
            }

            $addAdmin = $this->userRepository->createUpdateData(
                ['id' => $data->id],
                [
                    'role_id' => 1,
                    'first_name' => ucwords($data->first_name),
                    'last_name' => ucwords($data->last_name),
                    'email' => $data->email,
                    'mobile_code' => $data->mobile_code,
                    'mobile' => $data->mobile,
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make($this->request->password),
                ]
            );

            Log::write([
                'operation' => 'Add Admin ' . $this->request->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Admin Created Successfully.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Admin Created Successfully.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Add Admin ' . $this->request->email . ' by ' . Auth::user()->email,
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
