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

    public static function getOrderQueryObject() {
        return "
        id
        name
        email
        paymentGatewayNames
        processedAt
        test
        displayFinancialStatus
        note
        netPaymentSet {
            ". self::getMoneyBagQueryObject() ."
        }
        currentTotalPriceSet {
            ". self::getMoneyBagQueryObject() ."
        }
        totalOutstandingSet {
            ". self::getMoneyBagQueryObject() ."
        }
        transactions {
            ". self::getTransactionQueryObject() ."
        }
        unpaid
        createdAt
        updatedAt";
    }

    public static function getMoneyBagQueryObject() {
        return "
        shopMoney {
            amount
            currencyCode
        }
        presentmentMoney {
            amount
            currencyCode
        }";
    }

    public static function getTransactionQueryObject() {
        return "
        id
        paymentId
        amountSet {
            ". self::getMoneyBagQueryObject() ."
        }
        gateway
        kind
        status
        parentTransaction {
            id
        }";
    }

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
        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
        query {
            orders(first: 10) {
                edges {
                    node {
                        $orderQueryObject
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

            if (!empty($order['data']['orders']['userErrors'])) {
                return response()->json(['success'=>false, 'message' => 'User error happened','errors' => $order['data']['orders']['userErrors']], 400);
            }

            if (!empty($order['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API', 'errors' => $order['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order fetched successfully', 'data' => $order['data']['orders']['edges']]);
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
        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
        query {
            order(id: "$id") {
                $orderQueryObject
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

            if (!empty($order['data']['order']['userErrors'])) {
                return response()->json(['success'=>false, 'message' => 'User error happened','errors' => $order['data']['order']['userErrors']], 400);
            }

            if (!empty($order['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API', 'errors' => $order['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order fetched successfully', 'data' => $order['data']['order']]);
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
        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
        query {
            orders(first: 1, query: "confirmation_number:$confirmationNumber") {
                edges {
                    node {
                        $orderQueryObject
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

            if (!empty($order['data']['orders']['userErrors'])) {
                return response()->json(['success'=>false, 'message' => 'User error happened','errors' => $order['data']['orders']['userErrors']], 400);
            }

            if (!empty($order['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API', 'errors' => $order['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order fetched successfully', 'data' => $order['data']['orders']['edges'][0]['node']]);
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
        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
        query {
            orders(first: 1, query: "name:$name") {
                edges {
                    node {
                        $orderQueryObject
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

            if (!empty($order['data']['orders']['userErrors'])) {
                return response()->json(['success'=>false, 'message' => 'User error happened','errors' => $order['data']['orders']['userErrors']], 400);
            }

            if (!empty($order['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API', 'errors' => $order['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order fetched successfully', 'data' => $order['data']['orders']['edges'][0]['node']]);
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

        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
          mutation updateOrderFinancialStatus(\$input: OrderInput!) {
            orderUpdate(input: \$input) {
              order {
                $orderQueryObject
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
                return response()->json(['success'=>false, 'message' => 'User error happened','errors' => $responseBody['data']['orderUpdate']['userErrors']], 400);
            }

            if (!empty($responseBody['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API', 'errors' => $responseBody['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Payment status updated successfully', 'data' => $responseBody['data']['orderUpdate']['order']]);
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

        $orderQueryObject = self::getOrderQueryObject();

        $query = <<<QUERY
          mutation orderMarkAsPaid(\$input: OrderMarkAsPaidInput!) {
            orderMarkAsPaid(input: \$input) {
              order {
                $orderQueryObject
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
                return response()->json(['success'=>false, 'message' => 'User error happened. '. $responseBody['data']['orderMarkAsPaid']['userErrors'][0]['message'],'errors' => $responseBody['data']['orderMarkAsPaid']['userErrors']], 400);
            }

            if (!empty($responseBody['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API. '. $responseBody['errors'][0]['message'], 'errors' => $responseBody['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order successfully set as paid', 'data' => $responseBody['data']['orderMarkAsPaid']['order']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function refundOrder(Request $request)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/refundCreate

        $orderId = $request->input('order_id');

        try {
            // get order by id to get transactions
            $order = self::getShopifyOrderById($request->input('order_id'))->getData();

            if(!$order || !isset($order->data->id)){
                return response()->json(['error' => 'Order not found'], 404);
            }

            $order = $order->data;

            Log::info('request to mark payment as paid', ['requestBody', $request->all()]);

            // Check if valid
            if (!$orderId) {
                return response()->json(['error' => 'Invalid request. Please check if order_id id valid.'], 400);
            }

            $transactions = array_map(function($transaction) use ($orderId){
                return [
                    'amount' => $transaction->amountSet->shopMoney->amount,
                    'gateway' => $transaction->gateway,
                    'kind' => 'REFUND', //  $transaction->kind,
                    'orderId' => $orderId,
                    'parentId' => $transaction->parentTransaction ? $transaction->parentTransaction->id : $transaction->id,
                ];
            }, $order->transactions);

            $client = self::initRequestClient();

            $input = [
                'orderId' => $orderId,
                'note' => 'Refund initiated',
                'transactions' => $transactions,
            ];

            $orderQueryObject = self::getOrderQueryObject();
            $transactionQueryObject = self::getTransactionQueryObject();
            $moneyBagQueryObject = self::getMoneyBagQueryObject();

            $query = <<<QUERY
            mutation refundCreate(\$input: RefundInput!) {
                refundCreate(input: \$input) {
                    refund {
                        id
                        note
                        totalRefundedSet {
                            $moneyBagQueryObject
                        }
                    }
                    order {
                        $orderQueryObject
                    }
                    userErrors {
                        message
                        field
                    }
                }
            }
            QUERY;

            $response = $client->query([
                "query" => $query,
                "variables" => ["input" => $input]
            ]);

            $responseBody = $response->getDecodedBody();

            Log::info('order refunded', ['data' => $responseBody]);


            if (!empty($responseBody['data']['refundCreate']['userErrors'])) {
                return response()->json(['success'=>false, 'message' => 'User error happened. ' .$responseBody['data']['refundCreate']['userErrors'][0]['message'] ,'errors' => $responseBody['data']['refundCreate']['userErrors']], 400);
            }

            if (!empty($responseBody['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API. '. $responseBody['errors'][0]['message'], 'errors' => $responseBody['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Order successfully refunded', 'data' => ['order' => $responseBody['data']['refundCreate']['order'], 'refund' => $responseBody['data']['refundCreate']['refund']]]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function voidShopifyTransaction($parentTransactionId, $transaction = null)
    {
        // INFO https://shopify.dev/docs/api/admin-graphql/2024-10/mutations/transactionVoid

        // Check if payment_id is valid
        if (!$parentTransactionId) {
            return response()->json(['error' => 'Invalid request. Please check if parent transaction id is valid.'], 400);
        }

        $transactionQueryObject = self::getTransactionQueryObject();
        $client = self::initRequestClient();

        // Check transaction status before voiding
        $checkQuery = <<<QUERY
        query {
          transaction(id: "$parentTransactionId") {
            id
            status
            kind
          }
        }
        QUERY;

        try {
            $checkResponse = $client->query(['query' => $checkQuery]);
            $checkResponseBody = $checkResponse->getDecodedBody();

            $transaction = $checkResponseBody['data']['transaction'];

            if ($transaction['status'] !== 'voidable' || $transaction['kind'] !== 'authorization') {
                return response()->json(['error' => 'Transaction cannot be voided.'], 400);
            }

            $query = <<<QUERY
            mutation transactionVoid(\$parentTransactionId: ID!) {
              transactionVoid(parentTransactionId: \$parentTransactionId) {
                transaction {
                  $transactionQueryObject
                }
                userErrors {
                  field
                  message
                }
              }
            }
            QUERY;

            $variables = ['parentTransactionId' => $parentTransactionId];

            $response = $client->query(['query' => $query, 'variables' => $variables]);
            $responseBody = $response->getDecodedBody();

            if (!empty($responseBody['data']['transactionVoid']['userErrors'])) {
                return response()->json(['success' => false, 'message' => 'User error occurred. ' . $responseBody['data']['transactionVoid']['userErrors'][0]['message'], 'errors' => $responseBody['data']['transactionVoid']['userErrors']], 400);
            }

            if (!empty($responseBody['errors'])) {
                return response()->json(['success' => false, 'message' => 'Error with the API. '. $responseBody['errors'][0]['message'], 'errors' => $responseBody['errors']], 400);
            }

            return response()->json(['success' => true, 'message' => 'Transaction voided successfully', 'data' => $responseBody['data']['transactionVoid']['transaction']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
