<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => 'int',
            'iban' => 'string',
            'bank_id' => 'int',
            'execution_date' => '\DateTime',
            'money_transfers' => '\FinAPI\Client\Model\MoneyTransferOrderParams[]',
            'instant_payment' => 'bool',
            'single_booking' => 'bool',
            'msg_id' => 'string',
        ];
    }
}
