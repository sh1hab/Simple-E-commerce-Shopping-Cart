<?php

namespace App\Http\Controllers;

use App\Jobs\SendLowStockNotification;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_description' => $item->product->description,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'stock_available' => $item->product->stock_quantity,
                ];
            });

        $total = $cartItems->sum('subtotal');

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'total' => $total
                ]
            ]);
        }

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->stock_quantity < $quantity) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Insufficient stock available.'], 422);
            }
            return back()->with('error', 'Insufficient stock available.');
        }

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            
            if ($product->stock_quantity < $newQuantity) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Cannot add more items. Stock limit reached.'], 422);
                }
                return back()->with('error', 'Cannot add more items. Stock limit reached.');
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully.',
                'data' => $cartItem->load('product')
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->product->stock_quantity < $request->quantity) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Requested quantity exceeds available stock.'], 422);
            }
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
                'data' => $cartItem
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.'
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::transaction(function () use ($cartItems) {
                $total = 0;

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total_amount' => 0,
                    'status' => 'completed',
                ]);

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    if ($product->stock_quantity < $cartItem->quantity) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock_quantity', $cartItem->quantity);

                    if ($product->fresh()->isLowStock()) {
                        SendLowStockNotification::dispatch($product);
                    }

                    $total += $cartItem->subtotal;
                    $cartItem->delete();
                }

                $order->update(['total_amount' => $total]);
            });

            return back()->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}