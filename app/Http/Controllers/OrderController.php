<?php

namespace App\Http\Controllers;

use App\Services\LaravelSessionStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Graphql;
use Shopify\Context;

class OrderController extends Controller
{
    // resource controller
    public function index()
    {
        $accessToken = config('shopify.access_token');
        $shopDomain = config('shopify.test_shop_domain');
        $clientSecret = config('shopify.client_secret');
        $scopes = config('shopify.scopes');
        $host = config('shopify.host');
        $sessionStorage = new LaravelSessionStorage();

        // Initialize Shopify API context
        Context::initialize($accessToken, $clientSecret, $scopes, $host, $sessionStorage);

        if (!$accessToken || !$shopDomain) {
            return response()->json(['error' => 'Shopify configuration is missing'], 500);
        }

        $client = new Graphql($shopDomain, $accessToken);
        $query = <<<QUERY
        query {
            orders(first: 10) {
                edges {
                    node {
                        id
                        name
                        email
                        paymentGatewayNames
                        processedAt
                        test
                        displayFinancialStatus
                        netPaymentSet {
                            presentmentMoney {
                                amount
                                currencyCode
                            }
                        }
                        currentTotalPriceSet {
                            presentmentMoney {
                                amount
                                currencyCode
                            }
                        }
                        totalOutstandingSet {
                            presentmentMoney {
                                amount
                                currencyCode
                            }
                        }
                        transactions {
                            id
                            paymentId
                            amountSet {
                                presentmentMoney {
                                    amount
                                    currencyCode
                                }
                            }
                            gateway
                            kind
                            status
                        }
                        unpaid
                        createdAt
                        updatedAt
                    }
                }
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);
            $orders = $response->getDecodedBody()['data']['orders']['edges'];

            Log::info('orders fetched ', ['data'=>$orders]);

            return view('order.order-index', compact('orders'));
        } catch (\Exception $e) {
            Log::info(
                'Error fetching orders from Shopify',
                ['error' => $e->getMessage()]
            );
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateShopifyPaymentStatus(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/orderUpdate

        Log::info('request to update payment', ['requestBody', $request->all()]);
        $paymentStatus = $request->input('payment_status');
        $orderId = $request->input('order_id');
    
        // Check if valid
        if (!$paymentStatus || !$orderId) {
            return response()->json(['error' => 'Invalid request. Please check if order_id and payment_status are valid.'], 400);
        }
        if (!in_array($paymentStatus, ['pending', 'authorized', 'partially_paid', 'paid', 'partially_refunded', 'refunded', 'voided'])) {
            return response()->json(['error' => 'Invalid payment status'], 400);
        }
    
        $accessToken = config('shopify.access_token');
        $shopDomain = config('shopify.test_shop_domain');
        $clientSecret = config('shopify.client_secret');
        $scopes = config('shopify.scopes');
        $host = config('shopify.host');
        $sessionStorage = new LaravelSessionStorage();
    
        // Initialize Shopify API context
        Context::initialize($accessToken, $clientSecret, $scopes, $host, $sessionStorage);
    
        if (!$accessToken || !$shopDomain) {
            return response()->json(['error' => 'Shopify configuration is missing'], 500);
        }
    
        $client = new Graphql($shopDomain, $accessToken);
    
        // Construct the input for the mutation
        $input = [
            'id' => $orderId,
            // 'displayFinancialStatus' => strtoupper($paymentStatus) // ! WILL NOT WORK
            'note' => 'payment status is updated'
            // ! Order financial details can not be edited!! Only customAttributes [key-values],  metafields, note, shippingAddress or tags can be edited; Ref : https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/orderUpdate 
        ];
    
        $query = <<<QUERY
          mutation updateOrderFinancialStatus(\$input: OrderInput!) {
            orderUpdate(input: \$input) {
              order {
                id
                note
              }
              userErrors {
                message
                field
              }
            }
          }
        QUERY;
    
        try {
            $response = $client->query([
                "query" => $query,
                "variables" => ["input" => $input]
            ]);

            $responseBody = $response->getDecodedBody();
    
            Log::info('payment status update', ['data' => $responseBody]);
    
            if (!empty($responseBody['data']['orderUpdate']['userErrors'])) {
                return response()->json(['error' => $responseBody['data']['orderUpdate']['userErrors']], 400);
            }
    
            return response()->json(['message' => 'Payment status updated successfully', 'order' => $responseBody['data']['orderUpdate']['order']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function markShopifyOrderAsPaid(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/orderMarkAsPaid

        Log::info('request to mark payment as paid', ['requestBody', $request->all()]);
        $orderId = $request->input('order_id');
    
        // Check if valid
        if (!$orderId) {
            return response()->json(['error' => 'Invalid request. Please check if order_id id valid.'], 400);
        }
    
        $accessToken = config('shopify.access_token');
        $shopDomain = config('shopify.test_shop_domain');
        $clientSecret = config('shopify.client_secret');
        $scopes = config('shopify.scopes');
        $host = config('shopify.host');
        $sessionStorage = new LaravelSessionStorage();
    
        // Initialize Shopify API context
        Context::initialize($accessToken, $clientSecret, $scopes, $host, $sessionStorage);
    
        if (!$accessToken || !$shopDomain) {
            return response()->json(['error' => 'Shopify configuration is missing'], 500);
        }
    
        $client = new Graphql($shopDomain, $accessToken);
    
        // Construct the input for the mutation
        $input = [
            'id' => $orderId,
        ];
    
        $query = <<<QUERY
          mutation orderMarkAsPaid(\$input: OrderMarkAsPaidInput!) {
            orderMarkAsPaid(input: \$input) {
              order {
                id
              }
              userErrors {
                message
                field
              }
            }
          }
        QUERY;
    
        try {
            $response = $client->query([
                "query" => $query,
                "variables" => ["input" => $input]
            ]);

            $responseBody = $response->getDecodedBody();
    
            Log::info('order marked as paid', ['data' => $responseBody]);
    
            if (!empty($responseBody['data']['orderMarkAsPaid']['userErrors'])) {
                return response()->json(['error' => $responseBody['data']['orderMarkAsPaid']['userErrors']], 400);
            }
    
            return response()->json(['message' => 'Order successfully marked as paid', 'order' => $responseBody['data']['orderMarkAsPaid']['order']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function voidShopifyTransaction(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/transactionVoid
        $paymentId = $request->input('payment_id');

        // Check if payment_id is valid
        if (!$paymentId) {
            return response()->json(['error' => 'Invalid request. Please check if payment_id is valid.'], 400);
        }

        $accessToken = config('shopify.access_token');
        $shopDomain = config('shopify.test_shop_domain');
        $clientSecret = config('shopify.client_secret');
        $scopes = config('shopify.scopes');
        $host = config('shopify.host');
        $sessionStorage = new LaravelSessionStorage();

        // Initialize Shopify API context
        Context::initialize($accessToken, $clientSecret, $scopes, $host, $sessionStorage);

        if (!$accessToken || !$shopDomain) {
            return response()->json(['error' => 'Shopify configuration is missing'], 500);
        }

        $client = new Graphql($shopDomain, $accessToken);

        $query = <<<QUERY
        mutation {
            transactionVoid(parentTransactionId: "$paymentId") {
                transaction {
                    id
                    amountSet {
                        presentmentMoney {
                            amount
                            currencyCode
                        }
                    }
                    status
                    kind
                }
                    field
                    message
                }
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);
            $responseBody = $response->getDecodedBody(); 

            if (!empty($responseBody['data']['transactionVoid']['userErrors'])) {
                return response()->json(['error' => $responseBody['data']['transactionVoid']['userErrors']], 400);
            }

            return response()->json(['message' => 'Transaction voided successfully', 'transaction' => $responseBody['data']['transactionVoid']['transaction']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return 'create';
    }

    public function store(Request $request)
    {
        return 'store';
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
