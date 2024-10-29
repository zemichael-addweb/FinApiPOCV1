<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Models\FinapiForm;
use App\Models\FinapiPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\FinapiUser;
use App\Services\FinApiLoggerService;
use App\Services\FinAPIService;
use Carbon\Carbon;
use GuzzleHttp\Client;

class VerifyFinapiForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finapi:verify-finapi-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and verify finApi forms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $processedStatuses = ['COMPLETED', 'COMPLETED_WITH_ERROR', 'EXPIRED','ABORTED','CANCELLED'];
        // $processedStatuses = ['EXPIRED','COMPLETED_WITH_ERROR'];

        $loggedForms = FinapiForm::whereNotIn('status', $processedStatuses)
            ->orWhere('status', null)
            ->get();

        if(!$loggedForms  || count($loggedForms) == 0){
            $this->error('No unprocessed logged form found.');
            return;
        }

        foreach($loggedForms as $loggedForm) {
            $finApiUser = $loggedForm->finapiUser;

            if(!$finApiUser){
                $this->error('FinApi user not found. Please check the form_id.');
                return;
            }

            try{
                $finApiUserAccessToken = FinAPIService::getAccessToken('user', $finApiUser->email);
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

            $formType = $loggedForm->type;

            switch($formType){
                case 'BANK_CONNECTION_IMPORT':
                    $this->verifyBankConnectionForm($accessToken, $loggedForm);
                    break;
                case 'BANK_CONNECTION_UPDATE':
                    $this->verifyBankConnectionForm($accessToken, $loggedForm);
                    break;
                case 'PAYMENT_WITH_ACCOUNT_ID':
                    $this->info('Could not verify this PAYMENT_WITH_ACCOUNT_ID forms.');
                    break;
                case 'STANDALONE_PAYMENT':
                    $this->verifyPaymentForm($accessToken,$loggedForm);
                    break;
                case 'DIRECT_DEBIT_WITH_ACCOUNT_ID':
                    $this->info('Could not verify this DIRECT_DEBIT_WITH_ACCOUNT_ID forms.');
                    break;
                case 'STANDING_ORDER':
                    $this->info('Could not verify this STANDING_ORDER forms.');
                    break;
                default:
                    $this->error('Invalid form type. Please check the form type.');
                    break;
            }

            // sleep for 10 seconds
            sleep(10);
        }
    }

    public function verifyBankConnectionForm($accessToken, $loggedForm){
        try{
            $formDetails = FinAPIService::getFromDetails($accessToken, $loggedForm->form_id);
        } catch (\Exception $e) {
            $this->error('Error while fetching access token. Please check the form_id.');
            return;
        }

        $this->updateFormStatus($loggedForm, $formDetails);

        if (!isset($formDetails->payload->bankConnectionId)) {
            $this->error('No Bank Connection Id Found.' . isset($formDetails->payload->errorMessage) ? isset($formDetails->payload->errorMessage) : 'Please make sure you connected your bank using this url : ' . $formDetails->url);
            return;
        }

        $this->info('form fetched: ' . json_encode($formDetails));

        $loggedForm->bank_connection_id = $formDetails->payload->bankConnectionId;
        $loggedForm->save();

        $this->info('Done verifying bank connection details!');
        $this->info('xxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    }

    public function verifyPaymentForm($accessToken, $loggedForm){
        // API Call 1 : Get form details
        try{
            $formDetails = FinAPIService::getFromDetails($accessToken, $loggedForm->finapi_id);
        } catch (\Exception $e) {
            $this->error('Error while fetching access token. Form detail API call failed.');
            \Log::info('er', ['er'=>$e]);
            return;
        }

        $updatedPayments = [];

        $this->updateFormStatus($loggedForm, $formDetails);

        if (!isset($formDetails->payload->paymentId)) {
            $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
            return;
        }

        $paymentId = $formDetails->payload->paymentId;
        $formPurpose = $loggedForm->form_purpose;

        // API Call 2: Get payment details using the payment ID from the form response
        try{
            $paymentDetails = FinAPIService::getPaymentDetails($accessToken, $paymentId);
        } catch (\Exception $e) {
            $this->error('Error while fetching payment details. Payment detail API call failed.');
            \Log::info('er', ['er'=>$e]);
            return;
        }

        if(!isset($paymentDetails->payments)) {
            $this->error('No Payment Found. Please make sure you pay using this url : ' . $formDetails->url);
            return;
        }

        foreach ($paymentDetails->payments as $payment) {
            $finapiUser = $loggedForm->finapiUser;

            $finapiPayment = FinapiPayment::where('finapi_form_id', $finapiUser->id)->first();

            if($finapiPayment) {
                $finapiPayment->status_v2 = $payment->status_v2;
                $finapiPayment->save();
            } else {
                $finapiPayment = FinapiPayment::create([
                    'finapi_id' => $payment->id,
                    'finapi_user_id' => $finapiUser->id,
                    'order_ref_number' => $loggedForm->order_ref_number,
                    'finapi_form_id' => $loggedForm->id,
                    'purpose' => $formPurpose == 'PAYMENT' ? 'ORDER' : 'DEPOSIT',
                    // 'deposit_id' => !
                    'currency' => 'EUR',
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
            $loggedForm->finapi_user_id = $finapiUser->id;

            $loggedForm->save();

            if($payment->statusV2 != 'SUCCESSFUL'){
                continue;
            }

            if($formPurpose == 'DEPOSIT' && auth()->user()){
                $userDeposit = Deposit::where('user_id', auth()->user()->id)->first();
                if($userDeposit){
                    $userDeposit->remaining_balance += $payment->amount;
                    $userDeposit->save();
                } else {
                    $userDeposit = Deposit::create([
                        'user_id' => auth()->user()->id,
                        'email' => auth()->user()->email,
                        'deposited_at' => now(),
                        'status' => $payment->status,
                        'remaining_balance' => $payment->amount
                    ]);

                    $userDeposit->save();
                }

                FinApiLoggerService::logUserAmount(auth()->user()->id, $payment->amount,'DEPOSIT', $finapiPayment, $loggedForm->order_ref_number);

                $finapiPayment->deposit_id = $userDeposit->id;
                $finapiPayment->save();
            } elseif($formPurpose == 'PAYMENT') {
                FinApiLoggerService::logUserAmount(auth()->user()?->id, $payment->amount,'DEPOSIT', $finapiPayment, $loggedForm->order_ref_number);
            }

            $updatedPayments[] = $finapiPayment;
        }

        $this->info('Updated Payment Details : ' . json_encode($updatedPayments));
        $this->info('Done verifying payment details!');
        $this->info('xxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    }

    public function updateFormStatus($loggedForm, $formDetails){
        $loggedForm->status = $formDetails ? $formDetails->status : null;

        if(isset($formDetails->payload->errorCode) && isset($formDetails->payload->errorMessage)){
            $loggedForm->error_code = $formDetails->payload->errorCode;
            $loggedForm->error_message = $formDetails->payload->errorMessage;
        }

        $loggedForm->save();

        $this->info('Form status of ID : '. $loggedForm->id .' has been updated successfully!');
    }
}
