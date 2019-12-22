<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

// models
use App\User;
use Exception;
use Validator;
use App\EmailTemplate;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Packages\Logger\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

// Repository
use App\Repository\EmailTemplateRepository;


class MailController extends Controller
{

    private $user;

    private $request;

    private $model;

    protected $baseUrl;

    private $first_name = 'XXX';

    private $from = 'XXX@XXX.com';

    // private $telephone = ['04235692627','04235692627'];


    public function __construct(
        Request $Request,
        User $User,
        EmailTemplateRepository $EmailTemplateRepository,
        EmailTemplate $EmailTemplate
    ) {
        $this->request = $Request;

        $this->user = $User;

        $this->emailTemplateRepository = $EmailTemplateRepository;

        $this->model = $EmailTemplate;

        $this->baseUrl = env('APP_URL');
    }

    public function sendMail($parameters = [])
    {
        if (empty($parameters)) {
            throw new Exception("Email parameters missing", 1);
        }

        $parameters['first_name'] = $this->first_name;

        $parameters['from'] = $this->from;

        Mail::to($parameters['email'])->queue(new SendMail($parameters));

        /*Mail::queue($parameters['template_name'], $parameters['template_data'], function( $message ) use ($parameters)
        {
            $message->to( $parameters['email'] )
                ->from($parameters['from'], $parameters['first_name'] )
                ->subject($parameters['subject']);
        });*/

        return true;
    }

    public function getEmailTemplates()
    {
        # code...
        try {
            $resultSet = $this->model->get();

            if (!empty($resultSet)) {
                # code...
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

    public function getEmailTemplate($title)
    {
        try {
            $resultSet = $this->emailTemplateRepository->getData(['title' => $title], 'first');
            if (empty($resultSet)) {
                # code...
                throw new Exception("Email template not found.", 1);
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

    private function getUserData($user_id)
    {
        $response = User::where('id', $user_id)->first();

        if (empty($response)) {
            # code...
            throw new Exception("User details not found.", 1);
        }

        return $response;
    }

    public function convertText($array, $template)
    {
        return $result = str_replace(
            array_keys($array),
            array_values($array),
            $template
        );
    }

    public function forgotPasswordResetMail($email, $confirmation_code)
    {
        if (!empty($email)) {
            $emailData = $this->getEmailTemplate('reset_password')->original['data'];
            $template = $emailData['template'];
            $replaceArrary = [
                '{{$url}}' => '<a  style="background-color: #00bb55;color: #fff;font-size: x-large;font-weight: bolder;padding: 10px 30px;text-decoration: none;" href="' . $this->baseUrl . '/reset-password/' . $confirmation_code . '">Click here to reset password</a>',
            ];

            $newTemplate = $this->convertText($replaceArrary, $template);

            $parameter = [
                'template_name' => 'emailTemplate.email-template',
                'subject' => $emailData['subject'],
                'email' => $email,
                'template_data' => [
                    'template' => $newTemplate,
                ],
            ];

            if ($this->sendMail($parameter)) {
                # code...
                return true;
            } else {
                # code...
                return false;
            }
        } else {
            throw new Exception("Email Id not found", 1);
        }
    }

    public function changePasswordMail($id)
    {
        $userData = $this->getUserData($id);

        $emailData = $this->getEmailTemplate('change_password')->original['data'];
        $template = $emailData['template'];

        $replaceArrary = [
            '{{$name}}' => ucwords($userData->first_name . ' ' . $userData->last_name),
        ];

        $newTemplate = $this->convertText($replaceArrary, $template);

        if (!empty($userData->email) && !empty($userData->first_name)) {
            # code...
            $parameter = [
                'template_name' => 'emailTemplate.email-template',
                'subject' => $emailData['subject'],
                'email' => $userData->email,
                'template_data' => [
                    'template' => $newTemplate,
                ],
            ];

            if ($this->sendMail($parameter)) {
                # code...
                return true;
            } else {
                # code...
                return false;
            }
        } else {
            throw new Exception("Email Id not found", 1);
        }
    }

    public function userRegistrationMail($parameters)
    {

        if (!empty($parameters['email']) && !empty($parameters['name'])) {
            $emailData = $this->getEmailTemplate('new_registration')->original['data'];

            $template = $emailData['template'];

            $token = Crypt::encryptString($parameters['email']);

            $replaceArrary = [
                '{{$name}}' => ucwords($parameters['name']),
                '{{$email}}' => $parameters['email'],
                '{{$confirmation_link}}' => '<a style="background-color: #00bb55;color: #fff;font-size: x-large;font-weight: bolder;padding: 10px 30px;text-decoration: none;" href="' . $this->baseUrl . '/api/auth/verify-email/' . $token . '">Verify Email</a>',
            ];

            $newTemplate = $this->convertText($replaceArrary, $template);

            $parameter = [
                'template_name' => 'emailTemplate.email-template',
                'subject' => $emailData['subject'],
                'email' => $parameters['email'],
                'template_data' => [
                    'template' => $newTemplate,
                ],
            ];

            if ($this->sendMail($parameter)) {
                # code...
                return true;
            } else {
                # code...
                return false;
            }
        } else {
            throw new Exception("Email Id not found", 1);
        }
    }

    /**
     *  Function to update email template.
     *
     *  @return JSON response
     *
     *  Created By : Shagun | Created On : 8th March 2019
     **/
    public function updateEmailTemplateData()
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'template' => 'required',
                'subject' => 'required',
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                # code...
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    throw new Exception($messages[0], 1);
                }
            }

            $this->model->where('id', $this->request->id)->update([
                'subject' => $this->request->subject,
                'template' => $this->request->template,
            ]);

            Log::write([
                'operation' => 'Edit Mail Template ' . $this->request->subject . ' by ' . Auth::user()->email,
                'request_details' => $this->request->all(),
                'status' => 'success',
                'message' => 'Template updated successfully'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Template Updated successfully',
            ], 200);
        } catch (\Exception $ex) {

            Log::write([
                'operation' => 'Edit Mail Template ' . $this->request->subject . ' by ' . Auth::user()->email,
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

    public function newsLetterSubscriptionWelcomeMail($parameters)
    {
        if (!empty($parameters['email'])) {
            $emailData = $this->getEmailTemplate('newsletter_welcome');

            $template = $emailData['template'];

            $replaceArrary = [];

            $newTemplate = $this->convertText($replaceArrary, $template);

            $parameter = [
                'template_name' => 'emailTemplate.email-template',
                'subject' => $emailData['subject'],
                'email' => $parameters['email'],
                'template_data' => [
                    'template' => $newTemplate,
                ],
            ];

            if ($this->sendMail($parameter)) {
                return true;
            } else {
                # code...
                return false;
            }
        } else {
            throw new Exception("Email Id not found", 1);
        }
    }
}
