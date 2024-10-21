<?php

namespace App\Console\Commands;

use App\Models\FinapiForm;
use App\Models\FinapiPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
use App\Models\PaymentForm;
use App\Models\Payment;
use App\Services\FinAPIService;
use GuzzleHttp\Client;

class VerifyFinapiPaymentForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finapi:verify-payment-form {form_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and verify finApi payment status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $loggedForm = FinapiForm::where('form_id', $this->argument('form_id'))->first();

        if(!$loggedForm){
            $this->error('No logged form found for this form id. Please make sure payment is made.');
            return;
        }

        $finApiUser = $loggedForm->finapiUser;

        if(!$finApiUser){
            $this->error('FinApi user not found. Please check the form_id.');
            return;
        }

        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user', $finApiUser->username);
        } catch (\Exception $e) {
            $this->error('Error while fetching access token. Please check the form_id.', $e);
            return;
        }

        if(isset($finApiUserAccessToken->access_token)){
            $accessToken = $finApiUserAccessToken->access_token;
        } else {
            $this->error('FinApi user access token not found. Please check the form_id.');
            return;
        }

        // API Call 1: Get form details using the form ID
        $formId = $this->argument('form_id');
        try{
            $formDetails = FinAPIService::getFromDetails($accessToken, $formId);
        } catch (\Exception $e) {
            $this->error('Error while fetching access token. Please check the payment_id.');
            return;
        }

        $updatedPayments = [];

        if (!isset($formDetails->payload->paymentId)) {
            $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
            return;
        }

        $paymentId = $formDetails->payload->paymentId;

        // API Call 2: Get payment details using the payment ID from the form response
        try{
            $paymentDetails = FinAPIService::getPaymentDetails($accessToken, $paymentId);
        } catch (\Exception $e) {
            $this->error('Error while fetching payment details. Please check the payment_id.');
            return;
        }

        if(!isset($paymentDetails->payments)) {
            $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
            return;
        }

        foreach ($paymentDetails->payments as $payment) {
            $savedPayment = Payment::where('finapi_user_id', $finApiUser)
                ->where('order_ref_number', $loggedForm->standing_order_id)
                ->first();

            if ($savedPayment) {
                $savedPayment->status = $payment->status;
                $savedPayment->save();
            } else {
                $savedPayment = Payment::create([
                    'finapi_user_id' => $finApiUser->id,
                    'order_ref_number' => $loggedForm->standing_order_id,
                    'amount' => $payment->amount,
                    'currency' => 'EUR',
                    'type' => 'ORDER', // schema does not match
                    'status' => $payment->status
                ]);

                $savedPayment->save();
            }

            $finapiPayment = FinapiPayment::where('payment_id', $payment->id)->first();

            if($finapiPayment) {
                $finapiPayment->status_v2 = $payment->status_v2;
                $finapiPayment->save();
            } else {
                $finapiPayment = FinapiPayment::create([
                    'finapi_id' => $payment->id,
                    'finapi_user_id' => $finApiUser->id,
                    'form_id' => $loggedForm->id,
                    'payment_id' => $savedPayment->id,
                    'iban' => $payment->iban,
                    'bank_id' => $payment->bankId,
                    'type' => $payment->type,
                    'amount' => $payment->amount,
                    'order_count' => $payment->orderCount,
                    'status' => $payment->status,
                    'request_date' => $payment->requestDate,
                    'instant_payment' => $payment->instantPayment,
                    'status_v2' => $payment->statusV2
                ]);

                $finapiPayment->save();
            }

            $loggedForm->finapi_payment_id = $finapiPayment->id;
            $loggedForm->finapi_user_id = $finApiUser->id;
            $loggedForm->save();

            $updatedPayments[] = $savedPayment;
        }

        $this->info('Updated Payment Details : ' . json_encode($updatedPayments));
        $this->info('Done verifying payment details!');
    }
}
