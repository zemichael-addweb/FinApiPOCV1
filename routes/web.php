<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FinapiPaymentRecipientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\ShopifyController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\VerifyShopifyRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    if (request()->has('hmac')) {
        return redirect()->route('shopify.auth.install', request()->all());
    }
    return view('home');
})->name('home');

Route::resource( '/payments', PaymentController::class);

// Route::prefix('payments')->group(function () {
//         Route::get( '/create-direct-debit', [PaymentController::class, 'createDirectDebit'])->name('payment.create-direct-debit');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware'=>['auth', 'verified']], function () {
    Route::resource('/profile', ProfileController::class);
    Route::resource( '/deposits', DepositController::class);
    Route::post('/deposits/redirect-to-deposit-form', [DepositController::class, 'redirectToFinAPIPaymentForm'])->name('shopify.deposit.redirect-to-fin');
});

Route::middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::resource('/settings/finapi-payment-recipient', FinapiPaymentRecipientController::class);
        Route::resource( '/orders', OrderController::class);
        Route::resource( '/bank', BankController::class);
        Route::get( '/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/store-b2b-user', [AdminController::class, 'storeUser'])->name('admin.user.store');
        Route::get( '/users/register-b2b-user', [AdminController::class, 'registerUser'] )->name('admin.user.register');
        Route::get( '/settings', [AdminController::class, 'settings'] );
        Route::prefix('bank')->group(function () {
            Route::get( '/transactions', [BankController::class, 'transactions'] )->name('admin.bank.transactions');
            Route::get( '/get-transactions', [BankController::class, 'getTransactions'] )->name('admin.bank.get-transactions');
            Route::get( '/import-bank-connection', [BankController::class, 'importBankConnection'] )->name('admin.bank.import-bank-connection');
            Route::post( '/redirect-to-import-bank-connection-form', [BankController::class, 'redirectToImportBankConnectionForm'] )->name('admin.bank.redirect-to-import-bank-connection-form');
        });
});

Route::prefix('shopify')
    // ->middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::prefix('order')
        // ->middleware(EnsureUserIsAdmin::class)
        ->group(function () {
            Route::get('get-order-by-id', [OrderController::class, 'getShopifyOrderById'])->name('shopify.order.getOrderById');
            Route::get('get-order-by-name', [OrderController::class, 'getShopifyOrderByName'])->name('shopify.order.getOrderByName');
            Route::get('get-order-by-confirmation-number', [OrderController::class, 'getShopifyOrderByConfirmationNumber'])->name('shopify.order.getOrderByConfirmationNumber');
            Route::post('update-order-status', [OrderController::class, 'updateShopifyPaymentStatus'])->name('shopify.order.updateStatus');
            Route::post('mark-order-paid', [OrderController::class, 'markShopifyOrderAsPaid'])->name('shopify.order.markAsPaid');
            Route::post('refund-order', [OrderController::class, 'refundOrder'])->name('shopify.order.refund');
            Route::post('void-transaction', [OrderController::class, 'voidShopifyTransaction'])->name('shopify.order.voidTransaction');
        });
    Route::prefix('payment')
        // ->middleware(VerifyShopifyRequest::class)
        ->group(function () {
            Route::post('make-direct-debit-with-api', [PaymentController::class, 'makeDirectDebitWithApi'])->name('shopify.payment.make-direct-debit-with-api');
            Route::post('make-direct-debit-with-webform', [PaymentController::class, 'makeDirectDebitWithWebform'])->name('shopify.payment.make-direct-debit-with-webform');
            Route::post('make-direct-debit', [PaymentController::class, 'createDirectDebit'])->name('payments.directDebit');
            Route::post('redirect-to-payment-form', [PaymentController::class, 'redirectToFinAPIPaymentForm'])->name('shopify.payment.redirect-to-fin');
            Route::post('form-callback', [PaymentController::class, 'fromCallback'])->name('shopify.payment.form.callback');
    });
    Route::prefix('auth')
        ->middleware(VerifyShopifyRequest::class)
        ->group(function () {
            Route::get('install', [ShopifyAuthController::class, 'install'])->name('shopify.auth.install');
            Route::get('callback', [ShopifyAuthController::class, 'callback'])->name('shopify.auth.callback');
    });
});
