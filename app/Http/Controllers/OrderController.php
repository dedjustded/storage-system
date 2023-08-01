<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
{
    $requestData = $request->all();
    $productsData = json_decode($requestData['products'], true);
    unset($requestData['products']);
    $requestData['total_price'] = $requestData['total_price'];
    $requestData['order_id'] = uniqid();
    $order = new Order();
    $order->customer_name = $requestData['customer_name'];
    $order->status = $requestData['status'];
    $order->order_id = $requestData['order_id'];
    $order->total_price = $requestData['total_price'];
    $order->created_at = $requestData['created_at'];
    $order->save();

    foreach ($productsData as $productData) {
        $product = Product::find($productData['product_id']);
        $order->products()->attach($product, [
            'quantity' => $productData['quantity'],
            'imei' => $productData['imei'],
            'unit_price' => $product->unit_price
        ]);
        $product->availability -= $productData['quantity'];
        $product->save();
    }

    return response()->json(['message' => 'Поръчката беше добавена успешно!']);
}
public function storeAjax(Request $request)
{
    $requestData = $request->all();
    $productsData = json_decode($requestData['products'], true);
    unset($requestData['products']);
    $requestData['total_price'] = $requestData['total_price'];
    $requestData['order_id'] = uniqid();
    $order = new Order();
    $order->customer_name = $requestData['customer_name'];
    $order->status = $requestData['status'];
    $order->order_id = $requestData['order_id'];
    $order->total_price = $requestData['total_price'];
    $order->created_at = $requestData['created_at'];
    $order->save();

    foreach ($productsData as $productData) {
        $product = Product::find($productData['product_id']);
        $quantity = $productData['quantity'];
        if ($product->availability >= $quantity) {
            $product->availability -= $quantity;
            $product->save();

            $order->products()->attach($product, [
                'quantity' => $quantity,
                'imei' => $productData['imei'],
                'unit_price' => $product->unit_price
            ]);
        } else {
            throw new Exception('Няма достатъчно количество продукт в склада.');
        }
    }

    return response()->json(['message' => 'Поръчката беше добавена успешно!']);
}

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
    public function edit(Order $order)
{
    $products = Product::all();
    
    $order->load('products');
    
    return view('orders.edit', compact('order', 'products'));
}
    
    public function update(Request $request, Order $order)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'status' => 'required|in:Pending,Finished',
    ]);
    foreach ($order->products as $product) {
        $quantity = $product->pivot->quantity;
        $product->availability += $quantity;
        $product->save();
    }

    $order->customer_name = $request->input('customer_name');
    $order->status = $request->input('status');
    $order->save();

    if ($request->has('products')) {
        $productsData = json_decode($request->input('products'), true);

        $orderProducts = [];
        foreach ($productsData as $productData) {
            if (isset($productData['product_id']) && isset($productData['imei'])) {
                $orderProducts[$productData['product_id']] = ['imei' => $productData['imei']];
            }
        }

        $order->products()->sync($orderProducts);

        foreach ($orderProducts as $productId => $productData) {
            $product = Product::find($productId);
            $quantity = $productData['quantity'] ?? 0;
            $product->availability -= $quantity;
            $product->save();
        }
    }

    return redirect()->route('orders.show', ['order' => $order->id])->with('success', 'Поръчката беше актуализирана успешно!');
}
    public function destroy(Order $order)
{
    foreach ($order->products as $product) {
        $quantity = $product->pivot->quantity;
        $product->availability += $quantity;
        $product->save();
    }

    $order->delete();

    return redirect()->route('orders.index')->with('success', 'Поръчката беше изтрита успешно!');
}
    public function generatePDF(Order $order)
{
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $pdf = PDF::setOptions($pdfOptions)->loadView('pdf', compact('order'));
    return $pdf->download('order_' . $order->id . '.pdf');
}
}