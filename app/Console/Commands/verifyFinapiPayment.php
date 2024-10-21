<?php

namespace App\Console\Commands;

use App\Models\FinapiForm;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
use App\Models\Payment;
use App\Services\FinAPIService;
use GuzzleHttp\Client;

class verifyFinapiPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finapi:verify-payment {payment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and verify finApi import bank connection status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $loggedForm = FinapiForm::where('payment_id', $this->argument('payment_id'))->first();

        if(!$loggedForm){
            $this->error('No logged form found for this payment id. Please make sure payment form is verified.');
            return;
        }

        $finApiUser = $loggedForm->finapiUser;

        if(!$finApiUser){
            $this->error('FinApi user not found. Please check the payment_id.');
            return;
        }

        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user', $finApiUser->username, $finApiUser->password);
        } catch (\Exception $e) {
            $this->error('Error while fetching access token. Please check the payment_id.');
            return;
        }

        if(!$finApiUserAccessToken || !$finApiUserAccessToken->access_token){
            $this->error('FinApi user access token not found. Please check the payment_id.');
            return;
        }

        $accessToken = $finApiUserAccessToken->access_token;

        // API Call 1: Get payment details using the payment ID
        $payemntId = $this->argument('payment_id');
        try{
            $paymentDetails = FinAPIService::getPaymentDetails($accessToken, $payemntId);
            $this->info('fetched Payment Details : ' . json_encode($paymentDetails));

            return;

            $updatedPayments = [];

            if (!isset($formDetails->payload->paymentId)) {
                $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
                return;
            }

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

            $this->info('Updated Payment Details : ' . json_encode($updatedPayments));
            $this->info('Done verifying payment details!');

        } catch (\Exception $e) {
            $this->error('Error fetching form details. Please make sure payment form is verified.');
            return;
        }
    }
}
