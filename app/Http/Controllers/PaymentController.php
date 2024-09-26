<?php

namespace App\Http\Controllers;

use App\Models\FinAPIAccessToken;
use App\Services\OpenApiEnumModelService;
use Exception;
use FinAPI\Client\Api\PaymentsApi;
use FinAPI\Client\Configuration;
use FinAPI\Client\Model\CreateDirectDebitParams;
use FinAPI\Client\Model\CreateMoneyTransferParams;
use FinAPI\Client\Model\Currency;
use FinAPI\Client\Model\ISO3166Alpha2Codes;
use FinAPI\Client\Model\MoneyTransferOrderParams;
use FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // resource controller
    public function index()
    {
        return view('payment.payment-index');
    }
    
    public function create()
    {
        return view('payment.payment-create');
    }

    public function store(Request $request)
    {
        $structuredRemittanceInformation = $request->input('structured_remittance_information');
        $accessToken = FinAPIAccessToken::getAccessToken()->access_token;

        $config = Configuration::getDefaultConfiguration()->setAccessToken($accessToken);

        Log::info('Config', ['conf'=>$config]);
        
        $apiInstance = new PaymentsApi(
            new Client(),
            $config
        );

        $money_transfer_params = [];
       
        foreach ($request->input('counterpart_name') as $key => $counterpartName) {
            $money_transfer_params[] = new MoneyTransferOrderParams([
                'counterpart_name' => $request->input('counterpart_name')[$key],
                'counterpart_iban' => $request->input('counterpart_iban')[$key],
                'counterpart_bic' => $request->input('counterpart_bic')[$key],
                'counterpart_bank_name' => $request->input('counterpart_bank_name')[$key],
                'amount' => $request->input('amount')[$key],
                'currency' => OpenApiEnumModelService::getEnumValue(Currency::class, $request->input('currency')[$key], Currency::USD),
                'purpose' => $request->input('purpose')[$key],
                'sepa_purpose_code' => $request->input('sepa_purpose_code')[$key],
                'counterpart_address' => new MoneyTransferOrderParamsCounterpartAddress([
                    'street' => $request->input('counterpart_address.street')[$key],
                    'postCode' => $request->input('counterpart_address.post_code')[$key],
                    'city' => $request->input('counterpart_address.city')[$key],
                    'houseNumber' => $request->input('counterpart_address.house_number')[$key],
                    'country' => OpenApiEnumModelService::getEnumValue(ISO3166Alpha2Codes::class, $request->input('counterpart_address.country')[$key], ISO3166Alpha2Codes::DE)
                ]),
                'end_to_end_id' => $request->input('end_to_end_id')[$key],
                'structured_remittance_information' => [$structuredRemittanceInformation]
            ]);
        } 

        $create_money_transfer_params = new CreateMoneyTransferParams([
            'account_id' => $request->input('account_id'),
            'iban' => $request->input('iban'),
            'bank_id' => $request->input('bank_id'),
            'execution_date' => $request->input('execution_date'),
            'money_transfers' => $money_transfer_params, // ! check here
            'instant_payment' => $request->input('instant_payment'),
            'single_booking' => $request->input('single_booking'),
            'msg_id' => $request->input('msg_id')
        ]);

        Log::info('Body', ['bod'=>$create_money_transfer_params]);
        // dd($request->all(), $config, FinAPIAccessToken::getAccessToken()->access_token, $create_money_transfer_params);

        $x_request_id = null;
        try {
            $result = $apiInstance->createMoneyTransfer($create_money_transfer_params, $x_request_id);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling PaymentsApi->createDirectDebit: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function show($id)
    {
        return 'show';
    }

    public function edit($id)
    {
        return 'edit';
    }

    public function update(Request $request, $id)
    {
        return 'update';
    }

    public function destroy($id)
    {
        return 'destroy';
    }
}
