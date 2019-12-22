<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Validator;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Http\Request;
use App\Packages\Logger\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;

// Repository
use App\Repository\UserRepository;

class ForgotAndResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Forgot And Reset Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the forget password request and reset password request from user.
    |
    */

    private $request;

    private $mailController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $Request, MailController $MailController)
    {

        $this->request = $Request;

        $this->mailController = $MailController;
    }

    /**
     *  Function to generate reset password link for the users who forget their password.
     *
     *  Created By : RaHHuL | Created On : 24 sept 2018
     **/
    public function forgotPassword(UserRepository $UserRepository)
    {

        try {
            $validator = Validator::make($this->request->all(), [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $UserData = $UserRepository->getData(['email' => $this->request->email], 'first');

            if (empty($UserData)) {
                throw new Exception('Invalid Email Id.', 1);
            }

            $confirmation_code = str_random(30);

            PasswordReset::create([
                'email' => $this->request->email,
                'token' => $confirmation_code
            ]);

            $this->mailController->forgotPasswordResetMail($this->request->email, $confirmation_code);

            Log::write([
                'operation' => 'Reset password of ' . $this->request->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Reset password link has been sent to your registered mail id.'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset password link has been sent to your registered mail id.',
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Reset password of ' . $this->request->email,
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
     *  Function to reset password for the users, clicked on generated link.
     *
     *  @param user repository instance and mail controller instance.
     *
     *  @return json response
     *
     *  Createted By : RaHHuL | Created On : 24 sept 2018
     **/
    public function resetPassword(UserRepository $UserRepository)
    {

        try {

            $validator = Validator::make($this->request->all(), [
                'token' => 'required',
                'password' => 'required | confirmed',
                'password_confirmation' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $data = PasswordReset::where('token', $this->request->token)->first();

            if (empty($data)) {
                throw new Exception('Reset password link is Invalid.', 1);
            }

            if ($data->is_used == 1) {
                throw new Exception("Looks like your link is already used.", 1);
            }

            $time = date('G:i', strtotime(Carbon::now()) - strtotime($data->created_at));

            if ($time >= '0:30') {
                throw new Exception("Looks like your link is expired.", 1);
            }

            DB::beginTransaction();

            PasswordReset::where('token', $this->request->token)->update(['is_used' => '1']);

            $userDataResponse = $UserRepository->createUpdateData(['email' => $data->email], ['password' => Hash::make($this->request->password)]);

            $this->mailController->changePasswordMail($userDataResponse->id);

            DB::commit();

            Log::write([
                'operation' => 'Password Change by reset password of ' . $this->request->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

            Log::write([
                'operation' => 'Password Change by reset password of ' . $this->request->email,
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
     *  Function to change password.
     *
     *  @param Request data and user repository instance.
     *
     *  @return json response.
     *
     *  Created By : RaHHuL | Created On : 26 sept 2018
     **/
    public function changePassword(UserRepository $UserRepository)
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'user_id' => 'required',
                'old_password' => 'required',
                'password' => 'required | confirmed',
                'password_confirmation' => 'required',
            ]);

            if ($validator->fails()) {
                # code...
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $hashPassword = $UserRepository->getValue(['id' => $this->request->user_id], 'password');

            if (!Hash::check($this->request->old_password, $hashPassword)) {
                // valid
                throw new Exception("Old password not matching, please check old password.", 1);
            }

            DB::beginTransaction();

            $UserRepository->createUpdateData(['id' => $this->request->user_id], ['password' => Hash::make($this->request->password)]);

            $this->mailController->changePasswordMail($this->request->user_id);

            DB::commit();

            Log::write([
                'operation' => 'Password Change by ' . $this->request->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

            Log::write([
                'operation' => 'Password Change by ' . $this->request->email,
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
     *  Function to change password by admin.
     *
     *  @param Request data and user repository instance.
     *
     *  @return json response.
     *
     *  Created By : Shagun | Created On : 6th March 2019
     **/
    public function changePasswordByAdmin(UserRepository $UserRepository)
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'id' => 'required',
                'password' => 'required | confirmed',
                'password_confirmation' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            DB::beginTransaction();

            $userData = $UserRepository->getData([
                'id' => $this->request->id
            ], 'first');

            $UserRepository->createUpdateData([
                'id' => $this->request->id
            ], [
                'password' => Hash::make($this->request->password)
            ]);

            $this->mailController->changePasswordMail($this->request->id);

            DB::commit();

            Log::write([
                'operation' => 'Password Change of ' . $userData['email'] . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Changed Successfully.',
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

            Log::write([
                'operation' => 'Password Change of ' . $userData['email'] . ' by ' . Auth::user()->email,
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
