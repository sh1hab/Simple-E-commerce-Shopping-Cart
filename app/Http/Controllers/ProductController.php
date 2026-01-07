<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}