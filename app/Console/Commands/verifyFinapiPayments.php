<?php

namespace App\Console\Commands;

use App\Models\FinapiForm;
use App\Models\FinapiPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
use App\Models\Payment;
use App\Services\FinAPIService;
use GuzzleHttp\Client;

class verifyFinapiPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finapi:verify-finapi-payments';

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
        $processedStatuses = ['SUCCESSFUL', 'NOT_SUCCESSFUL', 'DISCARDED','UNKNOWN'];

        $finapiPayments = FinapiPayment::whereNotIn('status_v2', $processedStatuses)
            ->get();

        if(!$finapiPayments  || count($finapiPayments) == 0){
            $this->error('No unprocessed payment found.');
            return;
        }

        foreach($finapiPayments as $finapiPayment) {
            $finapiUser = $finapiPayment->finapiUser;
            $payment = $finapiPayment->payment;

            if(!$finapiUser){
                $this->error('FinApi user not found. Please check the form_id.');
                return;
            }

            try{
                $finApiUserAccessToken = FinAPIService::getAccessToken('user', $finapiUser->username);
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

            try{
                $paymentId = $finapiPayment->finapi_id;

                // API Call: Get payment details using the payment ID from the form response
                try{
                    $paymentDetails = FinAPIService::getPaymentDetails($accessToken, $paymentId);
                } catch (\Exception $e) {
                    $this->error('Error while fetching payment details. API call failed.');
                    \Log::info('er', ['er'=>$e]);
                    continue;
                }

                if(!isset($paymentDetails->payments)) {
                    $this->error('No Payment Found. Please make sure you paied the order.');
                    continue;
                }

                foreach ($paymentDetails->payments as $payment) {
                    $savedPayment = Payment::where('id', $payment->id)->first();

                    if ($savedPayment) {
                        $savedPayment->status = $payment->status;
                        $savedPayment->save();
                    } else {
                        $savedPayment = Payment::create([
                            'finapi_user_id' => $finapiUser->id,
                            'amount' => $payment->amount,
                            'currency' => 'EUR',
                            'type' => 'ORDER', // schema does not match
                            'status' => $payment->status
                        ]);

                        $savedPayment->save();
                    }

                    if($finapiPayment) {
                        $finapiPayment->status_v2 = $payment->statusV2;
                        $finapiPayment->save();
                    } else {
                        $finapiPayment = FinapiPayment::create([
                            'finapi_id' => $payment->id,
                            'finapi_user_id' => $finapiUser->id,
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

                    $updatedPayments[] = $savedPayment;
                }

                $this->info('Updated Payment Details : ' . json_encode($updatedPayments));
                $this->info('Done verifying payment details!');
                $this->info('xxxxxxxxxxxxxxxxxxxxxxxxxxxx');

            } catch (\Exception $e) {
                $this->error('Error fetching payment details. Please make sure payment is made or initialized.');
                \Log::info('er', ['er'=>$e]);
                return;
            }
        }
    }
}
