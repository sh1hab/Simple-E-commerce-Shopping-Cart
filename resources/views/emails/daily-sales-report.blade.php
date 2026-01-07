<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2 style="color: #059669;">Daily Sales Report</h2>
    <p style="color: #6b7280;">{{ today()->format('F d, Y') }}</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3>Summary</h3>
        <p><strong>Total Orders:</strong> {{ $totalOrders }}</p>
        <p><strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}</p>
    </div>
    
    <h3>Products Sold</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #e5e7eb;">
                <th style="padding: 10px; border: 1px solid #d1d5db; text-align: left;">Product Name</th>
                <th style="padding: 10px; border: 1px solid #d1d5db; text-align: right;">Quantity Sold</th>
                <th style="padding: 10px; border: 1px solid #d1d5db; text-align: right;">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productsSold as $product)
            <tr>
                <td style="padding: 10px; border: 1px solid #d1d5db;">{{ $product['product_name'] }}</td>
                <td style="padding: 10px; border: 1px solid #d1d5db; text-align: right;">{{ $product['quantity_sold'] }}</td>
                <td style="padding: 10px; border: 1px solid #d1d5db; text-align: right;">${{ number_format($product['revenue'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>