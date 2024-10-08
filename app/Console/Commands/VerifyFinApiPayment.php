<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
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
        // Get OAuth token for FinAPI
        $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));

        if ($accessToken) {
            $userDetails = [
                'id' => Str::random(),
                'password' => 'hellopassword',
                'email' => 'email@localhost.de',
                'phone' => '+49 99 999999-999',
                'isAutoUpdateEnabled' => true
            ];

            $finApiUser = FinAPIService::createFinApiUser($accessToken->access_token, $userDetails);

            if ($finApiUser) {

                 // {"id":"lOCOne5IisOKWzUN","password":"hellopassword","email":"email@localhost.de","phone":"+49 99 999999-999","isAutoUpdateEnabled":true}

                 // TODO get user form DB
                $finApiUserDetails = new FinapiUser([
                        'user_id' => auth()->user() ? auth()->user()->id : null,
                        'username' => auth()->user() ? auth()->user()->name : 'NO_USERNAME', // !
                        'password' => $finApiUser->password,
                        'email' => $finApiUser->email,
                ]);

                $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->id, $finApiUser->password);

                if ($finApiUserAccessToken) {
                    // {"access_token":"k3mvEvxNC4GYTzrtBU7KZE2o3uj2d05jOWkc8CfvOW9mZlG8ZUAR8RM8TahbaXRxUhASV4R0gHkmj8ApLA8RgiZkAG1GHMYgV9FIY9MrUaX3G2OgwCdnRZGpZtdF9Oc2","token_type":"bearer","refresh_token":"9Ld_45TcIOcx3w1oJjdRcqenRn_spharcIF2lPu8E8KEZuUIac69SaHC8YXdUwfcxtV2CFaAGbbgknpmvkWL6sC6ltA2dxeukNhLex4HcBMalSkXclWFO_Rfb0Xym0Ok","expires_in":3599,"scope":"all"}

                    // ! $finApiUserDetails->access_token = $finApiUserAccessToken->access_token;
                    $accessToken = $finApiUserAccessToken->access_token;

                    if ($accessToken) {
                        $client = new Client();

                        // API Call 1: Get form details using the form ID
                        $formId = 'your_form_id_here'; // TODO Set the correct form ID
                        $formId = $this->argument('form_id');
                        $formResponse = $client->get("https://webform-sandbox.finapi.io/api/webForms/{$formId}", [
                            'headers' => [
                                'Authorization' => "Bearer {$accessToken->access_token}"
                            ]
                        ]);

                        $formDetails = json_decode($formResponse->getBody()->getContents());

                        if (isset($formDetails->payload->paymentId)) {
                            $paymentId = $formDetails->payload->paymentId;

                            // API Call 2: Get payment details using the payment ID from the form response
                            $paymentResponse = $client->get('https://webform-sandbox.finapi.io/api/v2/payments', [
                                'headers' => [
                                    'Authorization' => "Bearer {$accessToken->access_token}"
                                ],
                                'query' => ['ids' => [$paymentId]]
                            ]);

                            $paymentDetails = json_decode($paymentResponse->getBody()->getContents());

                            if (isset($paymentDetails->payments)) {
                                foreach ($paymentDetails->payments as $payment) {
                                    // Save payment information to the database
                                    Payment::create([
                                        'finapi_user_id' => $formDetails->payload->bankConnectionId,
                                        'order_ref_number' => $payment->msgId, // ! No Confirmation Number  in payment message ?
                                        'amount' => $payment->amount,
                                        'currency' => 'EUR', // ! Need to get from DB
                                        'type' => $payment->type,
                                        'status' => $payment->status
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }   
    }
}
