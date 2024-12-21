<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FinApiController;
use App\Http\Controllers\FinapiPaymentRecipientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WebformController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\VerifyShopifyRequest;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\TwoFactorVerified;
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
Route::get( '/users/get-finapi-user', [AdminController::class, 'getFinapiUser'])->name('users.get-finapi-user');

// Route::prefix('payments')->group(function () {
//         Route::get( '/create-direct-debit', [PaymentController::class, 'createDirectDebit'])->name('payment.create-direct-debit');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware'=>['auth', 'verified']], function () {
    Route::resource('/profile', ProfileController::class);
    Route::resource( '/deposits', DepositController::class);
    Route::prefix('deposit')->group(function () {
        Route::get( '/get-deposit', [DepositController::class, 'getDeposit'] )->name('deposit.getdeposit');
        Route::post( '/pay-from-deposit', [DepositController::class, 'makePaymentFromDeposit'] )->name('deposit.pay-from-deposit');
    });
    Route::post('/deposits/redirect-to-deposit-form', [DepositController::class, 'redirectToFinAPIPaymentForm'])->name('shopify.deposit.redirect-to-fin');

    Route::get('/setup-2fa', [TwoFactorController::class, 'index'])->name('setup-2fa');
    Route::post('/verify-2fa', [TwoFactorController::class, 'verify'])->name('verify-2fa');
});

Route::middleware([EnsureUserIsAdmin::class, TwoFactorVerified::class])
    ->group(function () {
        Route::resource('/settings/finapi-payment-recipient', FinapiPaymentRecipientController::class);
        Route::resource( '/orders', OrderController::class);
        Route::get( '/order/compare-orders', [OrderController::class, 'compareOrders'])->name('admin.order.compare-orders');
        Route::get( '/order/get-orders', [OrderController::class, 'getOrders'])->name('admin.order.get-orders');

        Route::get( '/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/store-b2b-user', [AdminController::class, 'storeUser'])->name('admin.user.store');
        Route::get( '/users/register-b2b-user', [AdminController::class, 'registerUser'] )->name('admin.user.register');
        Route::get( '/settings', [AdminController::class, 'settings'] );
        Route::prefix('payment')->group(function () {
            Route::get( '/get-finapi-payment', [PaymentController::class, 'getFinapiPayment'] )->name('admin.payment.get-finapi-payment');
            Route::get( '/get-payment', [PaymentController::class, 'getPayment'] )->name('admin.payment.getpayment');
            Route::get( '/get-payments', [PaymentController::class, 'getPayments'] )->name('admin.payment.getpayments');
        });
        Route::prefix('deposit')->group(function () {
            Route::get( '/admin-get-deposit', [DepositController::class, 'getDeposits'] )->name('admin.deposit.getdeposit');
        });
        Route::resource( '/bank', BankController::class);
        Route::prefix('bank')->group(function () {
            Route::get( '/import-bank-connection', [BankController::class, 'importBankConnection'] )->name('admin.bank.import-bank-connection');
            Route::get( '/get-bank-connections', [BankController::class, 'getBankConnections'] )->name('admin.bank.get-bank-connections');
            Route::post( '/redirect-to-import-bank-connection-form', [BankController::class, 'redirectToImportBankConnectionForm'] )->name('admin.bank.redirect-to-import-bank-connection-form');
        });
        Route::get( '/search-shopify-order', [TransactionController::class, 'searchOrders'] )->name('admin.search-orders');
        Route::resource( '/transaction', TransactionController::class);
        Route::prefix('transaction')->group(function () {
            Route::get( '/transactions', [TransactionController::class, 'transactions'] )->name('admin.transactions');
            Route::get( '/get-transactions', [TransactionController::class, 'getTransactions'] )->name('admin.transactions.get-transactions');

        });
        Route::resource( '/webforms', WebformController::class);
        Route::prefix('webform')->group(function () {
            Route::get( '/get-webforms', [WebformController::class, 'getWebforms'] )->name('admin.webform.get-webforms');
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
            Route::get('get-local-orders', [OrderController::class, 'getLocalOrders'])->name('shopify.order.get-local-orders');
            Route::post('update-order-status', [OrderController::class, 'updateShopifyPaymentStatus'])->name('shopify.order.updateStatus');
            Route::post('mark-order-paid', [OrderController::class, 'markShopifyOrderAsPaid'])->name('shopify.order.markAsPaid');
            Route::post('refund-order', [OrderController::class, 'refundOrder'])->name('shopify.order.refund');
            Route::post('void-transaction', [OrderController::class, 'voidShopifyTransaction'])->name('shopify.order.voidTransaction');
            Route::post('link-order-to-transaction', [OrderController::class, 'linkOrderToTransaction'])->name('shopify.order.linkOrderToTransaction');
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
