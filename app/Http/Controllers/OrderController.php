<?php

namespace App\Http\Controllers;

use App\Models\FinapiTransaction;
use App\Models\OrderTransactionLink;
use App\Models\ShopifyOrder;
use App\Services\LaravelSessionStorage;
use App\Services\ShopifyApiServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Graphql;
use Shopify\Context;

class OrderController extends Controller
{
    // resource controller
    public function index()
    {
        $orders = ShopifyOrder::orderBy('processed_at', 'desc')->get()->map(function ($order) {
            return (object) [
                'id' => $order->shopify_id,
                'name' => $order->name,
                'email' => $order->email,
                'processedAt' => $order->processed_at,
                'data' => json_decode($order->data)
            ];
        });

        return view('order.order-index', compact('orders'));
    }

    public function refreshOrders () {
        $shopifyOrders = ShopifyApiServices::getShopifyOrders()->getData();
        if(!isset($shopifyOrders->success) || empty($shopifyOrders->data)) {
            return response()->json([]);
        }
        return response()->json($shopifyOrders);
    }

    public function getLocalOrders () {
        $shopifyOrders = ShopifyOrder::orderBy('processed_at', 'desc')->get()->map(function ($order) {
            return (object) [
                'id' => $order->shopify_id,
                'name' => $order->name,
                'email' => $order->email,
                'processedAt' => $order->processed_at,
                'data' => json_decode($order->data)
            ];
        });

        return response()->json($shopifyOrders);
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

    public function getShopifyOrderById(Request $request)
    {
        $id = $request->order_id;
        return ShopifyApiServices::getShopifyOrderById($id);
    }

    public function getShopifyOrderByName(Request $request)
    {
        $name = $request->confirmation_number;
        return ShopifyApiServices::getShopifyOrderByName($name);
    }

    public function getShopifyOrderByConfirmationNumber(Request $request)
    {
        $confirmationNumber = $request->confirmation_number;
        return ShopifyApiServices::getShopifyOrderByConfirmationNumber($confirmationNumber);
    }

    public function updateShopifyPaymentStatus(Request $request)
    {
        return ShopifyApiServices::updateShopifyPaymentStatus($request);
    }

    public function markShopifyOrderAsPaid(Request $request)
    {
        $orderId = $request->order_id;
        return ShopifyApiServices::markShopifyOrderAsPaid($orderId);
    }

    public function refundOrder(Request $request)
    {
        return ShopifyApiServices::refundOrder($request);
    }

    public function getOrders(Request $request)
    {
        $financialStatus = $request->financial_status;

        $shopifyOrders = ShopifyApiServices::getShopifyOrders($financialStatus)->getData();

        if(!isset($shopifyOrders->success) || empty($shopifyOrders->data)) {
             return response()->json(['success' => false, 'message' => 'No orders found']);
        }
        return response()->json(['success' => true, 'data' => $shopifyOrders->data]);
    }

    public function compareOrders(Request $request)
    {
       return view('order.order-compare');
    }

    public function linkOrderToTransaction (Request $request)
    {

        $validated = $request->validate([
            'order_id' => 'required',
            'transaction_id' => 'required',
        ]);

        $shopifyOrder = ShopifyOrder::where('shopify_id', $validated['order_shopify_id'])->first();
        $finapiTransaction = FinapiTransaction::where('finapi_id', $validated['transaction_finapi_id'])->first();

        $existingLink = OrderTransactionLink::where('order_id', $shopifyOrder->id)
            ->where('transaction_id', $finapiTransaction->id)
            ->first();

        if ($existingLink) {
            return response()->json(['message' => 'This order and transaction are already linked.'], 409);
        }

        $order = ShopifyApiServices::markShopifyOrderAsPaid($validated['order_shopify_id'])->getData();

        if(!isset($shopifyOrders->success) || empty($shopifyOrders->data)) {
            return response()->json(['success' => false, 'message' => 'No orders found']);
        }

        $orderTransactionLink = OrderTransactionLink::create([
            'order_id' => $shopifyOrder->id,
            'transaction_id' => $finapiTransaction->id,
            'paid' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order successfully linked to transaction.',
            'data' => $orderTransactionLink,
            'markedOrder' => $order
        ], 201);
    }
}
