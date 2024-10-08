<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Graphql;
use Shopify\Context;

class ShopifyApiServices {
    public function __construct() {
        //
    }

    public static $orderQueryObject = "
        id
        name
        email
        paymentGatewayNames
        processedAt
        test
        displayFinancialStatus
        note
        netPaymentSet {
            shopMoney {
                amount
                currencyCode
            }
            presentmentMoney {
                amount
                currencyCode
            }
            
        }
        currentTotalPriceSet {
            shopMoney {
                amount
                currencyCode
            }
            presentmentMoney {
                amount
                currencyCode
            }
        }
        totalOutstandingSet {
            shopMoney {
                amount
                currencyCode
            }
            presentmentMoney {
                amount
                currencyCode
            }
        }
        transactions {
            id
            paymentId
            amountSet {
                shopMoney {
                    amount
                    currencyCode
                }
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
        updatedAt";

    private static function initRequestClient(){
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

        return new Graphql($shopDomain, $accessToken);
    }


    public static function getShopifyOrders()
    {
        $client = self::initRequestClient();
        $queryObject = self::$orderQueryObject;

        $query = <<<QUERY
        query {
            orders(first: 10) {
                edges {
                    node {
                        $queryObject
                    }
                }
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);

            $order = $response->getDecodedBody();

            if(!isset($order['data']['orders']['edges'])){
                return response()->json(['error' => 'Orders not found'], 404);
            }

            Log::info('order fetched ', ['data'=>$order]);

            return $order['data']['orders']['edges'];

        } catch (\Exception $e) {
            Log::info(
                'Error fetching orders from Shopify',
                ['error' => $e->getMessage()]
            );
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getShopifyOrderById($id)
    {
        $client = self::initRequestClient();
        $queryObject = self::$orderQueryObject;

        $query = <<<QUERY
        query {
            order(id: "$id") {
                $queryObject
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);
            $order = $response->getDecodedBody();

            if(!isset($order['data']['order'])){
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('order fetched ', ['data'=>$order]);

            return $order['data']['order'];;
        } catch (\Exception $e) {
            Log::info(
                'Error fetching orders from Shopify',
                ['error' => $e->getMessage()]
            );
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getShopifyOrderByConfirmationNumber($confirmationNumber)
    {
        $client = self::initRequestClient();
        $queryObject = self::$orderQueryObject;

        $query = <<<QUERY
        query {
            orders(first: 1, query: "confirmation_number:$confirmationNumber") {
                edges {
                    node {
                        $queryObject
                    }
                }
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);
            $order = $response->getDecodedBody();

            if(!isset($order['data']['orders']['edges'][0])){
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('order fetched ', ['data'=>$order]);

            return $order['data']['orders']['edges'][0]['node'];

        } catch (\Exception $e) {
            Log::info(
                'Error fetching orders from Shopify',
                ['error' => $e->getMessage()]
            );
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getShopifyOrderByName($name)
    {
        $client = self::initRequestClient();
        $queryObject = self::$orderQueryObject;

        $query = <<<QUERY
        query {
            orders(first: 1, query: "name:$name") {
                edges {
                    node {
                        $queryObject
                    }
                }
            }
        }
        QUERY;

        try {
            $response = $client->query(["query" => $query]);
            $order = $response->getDecodedBody();

            if(!isset($order['data']['orders']['edges'][0])){
                return response()->json(['error' => 'Order not found'], 404);
            }

            Log::info('order fetched ', ['data'=>$order]);

            return $order['data']['orders']['edges'][0]['node'];

        } catch (\Exception $e) {
            Log::info(
                'Error fetching orders from Shopify',
                ['error' => $e->getMessage()]
            );
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function updateShopifyPaymentStatus(Request $request)
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
    
        $client = self::initRequestClient();
    
        // Construct the input for the mutation
        $input = [
            'id' => $orderId,
            // 'displayFinancialStatus' => strtoupper($paymentStatus) // ! WILL NOT WORK
            'note' => 'payment status is updated'
            // ! Order financial details .can not be edited!! Only customAttributes [key-values],  metafields, note, shippingAddress or tags can be edited; Ref : https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/orderUpdate 
        ];

        $queryObject = self::$orderQueryObject;
    
        $query = <<<QUERY
          mutation updateOrderFinancialStatus(\$input: OrderInput!) {
            orderUpdate(input: \$input) {
              order {
                $queryObject
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

    public static function markShopifyOrderAsPaid(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/orderMarkAsPaid

        Log::info('request to mark payment as paid', ['requestBody', $request->all()]);
        $orderId = $request->input('order_id');
    
        // Check if valid
        if (!$orderId) {
            return response()->json(['error' => 'Invalid request. Please check if order_id id valid.'], 400);
        }
    
        $client = self::initRequestClient();
    
        $input = [
            'id' => $orderId,
        ];
    
        $queryObject = self::$orderQueryObject;

        $query = <<<QUERY
          mutation orderMarkAsPaid(\$input: OrderMarkAsPaidInput!) {
            orderMarkAsPaid(input: \$input) {
              order {
                $queryObject 
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
    

    public static function voidShopifyTransaction(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/transactionVoid
        $paymentId = $request->input('payment_id');

        // Check if payment_id is valid
        if (!$paymentId) {
            return response()->json(['error' => 'Invalid request. Please check if payment_id is valid.'], 400);
        }

        $client = self::initRequestClient();

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


}