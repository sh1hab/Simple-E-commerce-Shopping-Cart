<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2 style="color: #ef4444;">Low Stock Alert</h2>
    
    <p>The following product is running low on stock:</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <p><strong>Product Name:</strong> {{ $product->name }}</p>
        <p><strong>Current Stock:</strong> {{ $product->stock_quantity }} units</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    </div>
    
    <p style="color: #dc2626;">Please restock this product as soon as possible.</p>
</body>
</html>