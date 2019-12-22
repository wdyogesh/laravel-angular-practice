<?php

namespace App\Packages\AmazonPay;

use Exception;
use Validator;
use DB;
use Carbon\Carbon;

// amazon libraries...
use Tuurbo\AmazonPayment\AmazonPayment;
use Tuurbo\AmazonPayment\AmazonPaymentClient;
use GuzzleHttp\Client;

class AmazonPay
{
    /*
    |--------------------------------------------------------------------------
    |	AmazonPay
    |--------------------------------------------------------------------------
    |
    |	This class handles the request to perform all the operation related to Amazon pay.
    |
    |   "tuurbo/amazon-payment": "^1.5"
    |
    */

    private static $amazonPayment;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function initialize()
    {
        Self::$amazonPayment = new AmazonPayment(
		    new AmazonPaymentClient(
		        new Client, config('services.amazonpayment')
		    ),
		    config('services.amazonpayment')
		);
    }

    /**
     *	Function to charge amount.
     *
     *	@param @var parameters as array,
     *
     *	@return response from authorize.net api as array.
     *
     *	Created By : RaHHuL | Created On : 15 Jan 2019
    **/
    public static function createCharge(array $parameters)
    {
        try{
    	   Self::initialize();

            // set amazon order details
            $order_detail_response = Self::$amazonPayment->setOrderDetails([
                'referenceId' => $parameters['order_refrence_id'],
                'amount' => $parameters['amount'],
                'orderId' => $parameters['order_refrence_id'],
                // optional note from customer
                // 'note' => $_POST['note']
            ]);

            // comfirm the amazon order
            $confirm_order_response = Self::$amazonPayment->confirmOrder([
                'referenceId' => $parameters['order_refrence_id'],
            ]);

            return [
                'status' => 'success',
                'order_detail_response' => $order_detail_response,
                'confirm_order_response' => $confirm_order_response,
            ];
        } catch (\Tuurbo\AmazonPayment\Exceptions\OrderReferenceNotModifiableException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'detail' => $e->getFile().' line no. '.$e->getLine(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'detail' => $e->getFile().' line no. '.$e->getLine(),
            ];
        }
	}

    /**
     *  Function to get transaction details.
     *
     *  @param @var order_refrence_id as string,
     *
     *  @return response from api as array.
     *
     *  Created By : RaHHuL | Created On : 15 Jan 2019
    **/
    public static function getTransactionDetails($order_refrence_id)
    {
        try {
            Self::initialize();
            // get amazon order details and
            // save the response to your customers order
            $amazon = Self::$amazonPayment->getOrderDetails([
                'referenceId' => $order_refrence_id,
            ]);

            return [
                'status' => 'success',
                'data' => $amazon,
            ];
        } catch (\Tuurbo\AmazonPayment\Exceptions\OrderReferenceNotModifiableException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'detail' => $e->getFile().' line no. '.$e->getLine(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'detail' => $e->getFile().' line no. '.$e->getLine(),
            ];
        }
    }

    /**
     *  Function to refund transaction amount partial or full.
     *
     *  @param @var order_refrence_id as string,
     *  @param @var amount as string,
     *
     *  @return response from api as array.
     *
     *  Created By : RaHHuL | Created On : 15 Jan 2019
    **/
    public static function refund($order_refrence_id, $amount)
    {
        try {
            Self::initialize();

            $refund_response = Self::$amazonPayment->refund([
                'referenceId' => $order_refrence_id,
                'amount' => $amount,
            ]);

            return [
                'status' => 'success',
                'data' => $refund_response,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'detail' => $e->getFile().' line no. '.$e->getLine(),
            ];
        }
    }
}
