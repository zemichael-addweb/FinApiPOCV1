<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
use App\Models\PaymentForm;
use App\Models\Payment;
use App\Services\FinAPIService;
use GuzzleHttp\Client;

class VerifyFinApiPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finapi:verify-payment {form_id}';

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
        $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));

        $loggedForm = PaymentForm::where('form_id', $this->argument('form_id'))->first();
        if(!$loggedForm){
            $this->error('Form not found. Please check the form id.');
            return;
        }
        $finApiUser = $loggedForm->finapiUser;
        if ($accessToken) {

            if(!$finApiUser){
                $this->error('FinApi user not found. Please check the form id.');
                return;
            }

            if ($finApiUser) {
                $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->username, $finApiUser->password);

                if ($finApiUserAccessToken) {
                    // {"access_token":"k3mvEvxNC4GYTzrtBU7KZE2o3uj2d05jOWkc8CfvOW9mZlG8ZUAR8RM8TahbaXRxUhASV4R0gHkmj8ApLA8RgiZkAG1GHMYgV9FIY9MrUaX3G2OgwCdnRZGpZtdF9Oc2","token_type":"bearer","refresh_token":"9Ld_45TcIOcx3w1oJjdRcqenRn_spharcIF2lPu8E8KEZuUIac69SaHC8YXdUwfcxtV2CFaAGbbgknpmvkWL6sC6ltA2dxeukNhLex4HcBMalSkXclWFO_Rfb0Xym0Ok","expires_in":3599,"scope":"all"}

                    $accessToken = $finApiUserAccessToken->access_token;
                    $finApiUser->access_token = $accessToken;
                    $finApiUser->save();

                    if ($accessToken) {
                        $client = new Client();

                        // API Call 1: Get form details using the form ID
                        $formId = $this->argument('form_id');
                        $formDetails = FinAPIService::getFromDetails($accessToken, $formId);

                        $updatedPayments = [];

                        if (!isset($formDetails->payload->paymentId)) {
                            $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
                            return;
                        }

                        if (isset($formDetails->payload->paymentId)) {
                            $paymentId = $formDetails->payload->paymentId;

                            // API Call 2: Get payment details using the payment ID from the form response
                            $paymentDetails = FinAPIService::getPaymentDetails($accessToken, $paymentId);

                            if (isset($paymentDetails->payments)) {
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
                                    $updatedPayments[] = $savedPayment;
                                }
                            }
                        }
                        $this->info('Updated Payment Details : ' . json_encode($updatedPayments));
                        $this->info('Done verifying payment details!');
                    }
                }
            }
        }
    }
}
