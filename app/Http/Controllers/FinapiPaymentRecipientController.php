<?php

namespace App\Http\Controllers;

use App\Models\FinapiPaymentRecipient;
use App\Models\Recipient;
use Illuminate\Http\Request;

class FinapiPaymentRecipientController extends Controller
{
    // Display a listing of recipients (in case you need it)
    public function index()
    {
        $recipients = FinapiPaymentRecipient::all();
        return view('finapiPaymnetRecipients.index', compact('recipients'));
    }

    // Show form to create a new recipient
    public function create()
    {
        return view('finapiPaymnetRecipients.create');
    }

    // Store a newly created recipient in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'iban' => 'required|string|max:34',
            'bic' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:50',
            'post_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
        ]);

        FinapiPaymentRecipient::create($validatedData);

        return redirect()->route('finapiPaymnetRecipients.index')->with('success', 'Recipient created successfully.');
    }

    // Show form to edit an existing recipient
    public function edit(FinapiPaymentRecipient $recipient)
    {
        return view('finapiPaymnetRecipients.edit', compact('recipient'));
    }

    // Update the recipient in the database
    public function update(Request $request, FinapiPaymentRecipient $recipient)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'iban' => 'required|string|max:34',
            'bic' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:50',
            'post_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
        ]);

        $recipient->update($validatedData);

        return redirect()->route('finapiPaymnetRecipients.index')->with('success', 'Recipient updated successfully.');
    }
}
