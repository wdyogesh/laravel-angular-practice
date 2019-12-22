<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\User;
use Exception;
use Validator;
use Carbon\Carbon;
use App\Packages\Logger\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

// Repository
use App\Repository\UserRepository;

class UserManagementController extends Controller
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
    private $profile_picture_upload_path;
    private $profile_picture_display_path;


    public function __construct(
        Request $Request,
        UserRepository $UserRepository
    ) {
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
    public function getUserData(Request $request)
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

            // $resultSet = $this->userRepository->getData(['role_id' => $role_id], 'get', ['mobileCode']);
            //return $resultSet;
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

    public function getUserCount($role_id)
    {
        try {

            $result['userCount'] = $this->userRepository->getCount([
                ['role_id', '=', $role_id]
            ]);

            $result['active'] = $this->userRepository->getCount([
                ['role_id', '=', $role_id],
                ['is_active', '=', '1']
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $result
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
    public function getUserDetails($id)
    {
        try {
            $resultSet = $this->userRepository->getData(['id' => $id], 'first', ['mobileCode']);

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

    /**
     *  Function to Update user profile data.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 1st March 2019
     **/
    public function updateProfileData(UserRepository $UserRepository)
    {
        try {

            $validator = Validator::make($this->request->all(), [
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha',
                'email' => 'required | email',
                // 'ip_address' => 'required',
                'mobile_code' => 'required',
                'mobile' => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $data = $this->request;

            /*$resultSet = $this->userRepository->getData(['email' => $this->request->email, 'idNotEqualsTo' => $this->request->id], 'first');*/

            $resultSet = $this->userRepository->getData(['email' => $this->request->email], 'first');

            $userscount = User::where([
                ['email', '=', $this->request->email],
                ['id', '!=', $this->request->id]
            ])->count();
            // ->whereNotIn('id', '<>', $this->request->id)->count();
            if ($userscount > 0) {
                throw new Exception('User already registered with same Email ID.', 1);
            }

            $updateProfile = $this->userRepository->createUpdateData(['id' => $data->id], [
                'first_name' => ucwords($data->first_name),
                'last_name' => ucwords($data->last_name),
                'email' => $data->email,
                'ip_address' => $data->ip_address,
                'mobile_code' => $data->mobile_code,
                'mobile' => $data->mobile
            ]);

            Log::write([
                'operation' => 'Update Profile of ' . $data->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Profile Updated Successfully.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile Updated Successfully.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Update Profile of ' . $data->email . ' by ' . Auth::user()->email,
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

    /**
     *  Function to Update user profile data.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 1st March 2019
     **/
    public function updateUserProfile(UserRepository $UserRepository)
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha',
                'email' => 'required | email',
                'mobile_code' => 'required',
                'mobile' => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $data = $this->request;

            $updateProfile = $this->userRepository->createUpdateData(['id' => $data->id], [
                'first_name' => ucwords($data->first_name),
                'last_name' => ucwords($data->last_name),
                'email' => $data->email,
                'mobile_code' => $data->mobile_code,
                'mobile' => $data->mobile
            ]);

            Log::write([
                'operation' => 'Update Profile of ' . $data->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Profile Updated Successfully.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile Updated Successfully.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Add Admin',
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

    /**
     *  Function to activate/deactivate user.
     *
     *  @param Request data and user repository instance.
     *
     *  @return json response.
     *
     *  Created By : Shagun | Created On : 4th March 2019
     **/
    public function toggleUser()
    {
        try {

            $validator = Validator::make($this->request->all(), [
                'id' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $userData = $this->userRepository->createUpdateData(['id' => $this->request->id], ['is_active' => $this->request->status]);

            $message = ($this->request->status == 0) ? 'User Deactivated Successfully' : 'User Activated Successfully';

            Log::write([
                'operation' => 'Activated/Deactivated Profile of ' . $userData->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => $message
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Activated/Deactivated Profile of ' . $userData->email . ' by ' . Auth::user()->email,
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

    /**
     *  Function to update profile picture.
     *
     *  @param Null.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 7th March 2019
     **/
    public function doUpdateProfilePicture()
    {
        try {

            $validator = Validator::make($this->request->all(), [
                'id' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            // fetching user data.
            $responseUserData = $this->userRepository->getData(['id' => $this->request->id], 'first');

            // removing old file if available
            if (!empty($responseUserData['image'])) {
                File::delete($this->profile_picture_upload_path . $responseUserData['image']);
            }

            $file = $this->request->file('file');

            $image = Image::make($file)->resize(config('app.resolution.profile_image.width'), config('app.resolution.profile_image.height'));

            $file_name = strtotime(Carbon::now()) . '.' . $file->getClientOriginalExtension();

            $image->save($this->profile_picture_upload_path . $file_name);

            $responseUserData = $this->userRepository->createUpdateData(['id' => $this->request->id], ['image' => $file_name]);
            $responseUserData['picture_path'] = $this->profile_picture_display_path;

            Log::write([
                'operation' => 'Change profile picture of ' . $responseUserData->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Profile picture uploaded successfully.'
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $responseUserData,
                'message' => 'Profile picture uploaded successfully.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Change profile picture of ' . $responseUserData->email . ' by ' . Auth::user()->email,
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

    /**
     *  Function to delete user permanently.
     *
     *  @param user_id as integer.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 7th March 2019
     **/
    public function deleteUserPermanently($id)
    {
        try {
            $delete_user_response = User::find($id);

            if (!empty($delete_user_response['image'])) {
                File::delete($this->profile_picture_upload_path . $delete_user_response['image']);
            }

            $delete_user_response->delete();

            Log::write([
                'operation' => 'Delete user ' . $delete_user_response->email . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'User Deleted Permanently.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Deleted Permanently.'
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Delete user ' . $delete_user_response->email . ' by ' . Auth::user()->email,
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

    /**
     *  Function to delete multiple users.
     *
     *  @return JSON response.
     *
     *  Created By : Shagun | Created On : 7th March 2019
     **/
    public function deleteMultipleUsersPermanently()
    {
        //dd("Deleted multiple users");
        try {

            if (count($this->request->ids) == 0) {
                throw new Exception("Please select the user first.", 1);
            }

            foreach ($this->request->ids as $key => $value) {
                $delete_user_response = User::find($value);

                if (!empty($delete_user_response['image'])) {
                    File::delete($this->profile_picture_upload_path . $delete_user_response['image']);
                }

                $delete_user_response->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Users Deleted Successfully.'
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
