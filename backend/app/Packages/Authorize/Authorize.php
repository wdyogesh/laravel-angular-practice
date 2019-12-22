<?php

namespace App\Packages\Authorize;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;


class Authorize
{
    /*
    |--------------------------------------------------------------------------
    | Authorize Controller
    |--------------------------------------------------------------------------
    |
    |    "authorizenet/authorizenet": "~1.9.6",
    |
    |   This controller handles the request related to Authorize.net payment.
    |
    */

    private static $merchantAuthentication;

    private static $refId;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function initialize()
    {
        // Common setup for API credentials
        Self::$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        Self::$merchantAuthentication->setName(config('services.authorize.login'));
        Self::$merchantAuthentication->setTransactionKey(config('services.authorize.key'));

        SELF::$refId = 'ref' . time();
    }

    public static function test()
    {
        Self::initialize();
        echo "string";
        $response = SELF::doChargeCard([
            'card_number' => '4242424242424242',
            'card_expiry_year' => '2023',
            'card_expiry_month' => '11',
            'card_cvv' => '181',
            'amount' => 100,
        ]);

        $response = SELF::getTransactionDetails('40023575726');
        // $response = SELF::refundTransaction('40023575726');

        print_r($response);
    }

    /**
     *	Function to charge amount.
     *
     *  @param @var card_data as array,
     *
     *  @return response from authorize.net api as array.
     *
     *	Created By : RaHHuL | Created On : 31 Oct 2018
     **/
    public static function doChargeCard(array $card_data)
    {
        Self::initialize();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card_data['card_number']);
        $expiry = $card_data['card_expiry_year'] . '-' . $card_data['card_expiry_month'];
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($card_data['card_cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($card_data['amount']);
        $transactionRequestType->setPayment($paymentOne);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication(Self::$merchantAuthentication);
        $request->setRefId(Self::$refId);
        $request->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            if (($tresponse != null) && !empty($tresponse->getResponseCode())) {

                /*echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/

                return [
                    'status' => 'success',
                    'transaction_id' => $tresponse->getTransId(),
                    'transaction_response_code' => $tresponse->getResponseCode(),
                    'auth_code' => $tresponse->getAuthCode(),
                    'card_type' => $tresponse->getAccountType(),
                    'description' => $tresponse->getMessages()[0]->getDescription(),
                ];
            } else {
                /*echo "Charge Credit Card ERROR :  Invalid response\n";
				echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";*/

                return [
                    'status' => 'error',
                    'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                    'error_message' => $tresponse->getErrors()[0]->getErrorText(),
                ];
            }
        } else {
            // echo "Charge Credit Card Null response returned";
            // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
            // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

            return [
                'status' => 'error',
                'error_message' => 'No Response Returned.',
            ];
        }
    }

    /**
     *	Function to refund transaction amount.
     *
     *	@param @var transactionId as int,
     *	@param @var amount as int,
     *	@param @var card_details as array,
     *
     *	@return response from authorize.net api as array.
     *
     *	Created By : RaHHuL | Created On : 31 Oct 2018
     **/
    public static function refundTransaction($refTransId, $amount, array $card_details)
    {
        Self::initialize();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card_details['card_no']);
        $creditCard->setExpirationDate($card_details['card_exp']);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId($refTransId);


        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication(Self::$merchantAuthentication);
        $request->setRefId(Self::$refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    // echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
                    // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";

                    return [
                        'status' => 'success',
                        'transaction_id' => $tresponse->getTransId(),
                        'transaction_response_code' => $tresponse->getResponseCode(),
                        'code' => $tresponse->getMessages()[0]->getCode(),
                        'description' => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    // echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

                        return [
                            'status' => 'error',
                            'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_message' => $tresponse->getErrors()[0]->getErrorText(),
                        ];
                    }
                }
            } else {
                $tresponse = $response->getTransactionResponse();

                /*echo "Transaction Failed \n";
		        echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
		        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";*/

                return [
                    'status' => 'error',
                    'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                    'error_message' => $tresponse->getErrors()[0]->getErrorText(),
                ];
            }
        } else {
            /*echo  "No response returned \n";*/

            return [
                'status' => 'error',
                'error_message' => 'No response returned',
            ];
        }
    }

    /**
     *	Function to fetch payment transaction details
     *
     *	@param @var transactionId as int,
     *
     *	@return detailed info about transaction as array.
     *
     *	Created By : RaHHuL | Created On : 31 Oct 2018
     **/
    public static function getTransactionDetails($transactionId)
    {
        Self::initialize();

        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication(Self::$merchantAuthentication);
        $request->setTransId($transactionId);


        $controller = new AnetController\GetTransactionDetailsController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);


        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return [
                'status' => 'success',
                'transaction_status' => $response->getTransaction()->getTransactionStatus(),
                'transaction_amount' => $response->getTransaction()->getAuthAmount(),
                'transaction_id' => $response->getTransaction()->getTransId(),
                'transaction_card_no' => $response->getTransaction()->getPayment()->getCreditCard()->getCardNumber(),
                'transaction_card_exp_date' => $response->getTransaction()->getPayment()->getCreditCard()->getExpirationDate(),
            ];
        } else {
            return [
                'status' => 'error',
                'error_code' => $response->getMessages()->getMessage()[0]->getCode(),
                'error_description' => $response->getMessages()->getMessage()[0]->getText(),
            ];
        }
    }
}
