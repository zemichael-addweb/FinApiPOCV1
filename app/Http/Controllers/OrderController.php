<?php

namespace App\Http\Controllers;

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
        $shopifyOrders = ShopifyApiServices::getShopifyOrders()->getData();

        if(!isset($shopifyOrders->success) || empty($shopifyOrders->data)) {
            return view('order.order-index', ['orders' => []]);
        }
        return view('order.order-index', ['orders' => $shopifyOrders->data]);
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
        return ShopifyApiServices::markShopifyOrderAsPaid($request);
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
}
