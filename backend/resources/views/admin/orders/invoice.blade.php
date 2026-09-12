<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans p-8">
    <div class="max-w-4xl mx-auto bg-white p-10 rounded-lg shadow-sm border border-gray-200">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">INVOICE</h1>
                <p class="text-gray-500 font-medium">Order #{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <!-- Replace with dynamic logo/store name if available -->
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Store Name</h2>
                <p class="text-gray-500 text-sm">
                    123 Store Address<br>
                    City, Country, ZIP<br>
                    support@example.com
                </p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-2 gap-10 mb-8">
            <!-- Billed To -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Billed To</h3>
                <div class="text-gray-800">
                    <p class="font-bold text-lg mb-1">{{ $order->customer->name ?? 'N/A' }}</p>
                    @if($order->shipping_address && is_array($order->shipping_address))
                        <p class="text-gray-600">
                            {{ $order->shipping_address['address_line_1'] ?? '' }}<br>
                            {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['state'] ?? '' }}<br>
                            {{ $order->shipping_address['postal_code'] ?? '' }}
                        </p>
                    @endif
                    <p class="text-gray-600 mt-2">{{ $order->customer->email ?? '' }}</p>
                    <p class="text-gray-600">{{ $order->customer->mobile ?? '' }}</p>
                </div>
            </div>

            <!-- Invoice Details -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Invoice Details</h3>
                <table class="w-full text-left text-sm">
                    <tbody>
                        <tr>
                            <td class="py-1 text-gray-500">Invoice Date:</td>
                            <td class="py-1 font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Order Status:</td>
                            <td class="py-1 font-medium text-gray-900">{{ ucfirst($order->status) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Payment Status:</td>
                            <td class="py-1 font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-500">Payment Method:</td>
                            <td class="py-1 font-medium text-gray-900">{{ $order->paymentMethod->name ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item Description</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Price</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                <div class="text-xs text-gray-500 mt-1">SKU: {{ $item->sku }}</div>
                                @if($item->attributes)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach(json_decode($item->attributes, true) as $key => $value)
                                            {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right text-gray-700">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-4 px-4 text-center text-gray-700">{{ $item->quantity }}</td>
                            <td class="py-4 px-4 text-right font-medium text-gray-900">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Totals -->
        <div class="flex justify-end">
            <div class="w-1/2">
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-gray-200 border-b border-gray-200">
                        <tr>
                            <td class="py-3 text-gray-600 font-medium">Subtotal</td>
                            <td class="py-3 text-right font-medium text-gray-900">{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if($order->tax_total > 0)
                            <tr>
                                <td class="py-3 text-gray-600 font-medium">Tax</td>
                                <td class="py-3 text-right font-medium text-gray-900">{{ number_format($order->tax_total, 2) }}</td>
                            </tr>
                        @endif
                        @if($order->shipping_total > 0)
                            <tr>
                                <td class="py-3 text-gray-600 font-medium">Shipping</td>
                                <td class="py-3 text-right font-medium text-gray-900">{{ number_format($order->shipping_total, 2) }}</td>
                            </tr>
                        @endif
                        @if($order->discount_total > 0)
                            <tr>
                                <td class="py-3 text-gray-600 font-medium">Discount</td>
                                <td class="py-3 text-right font-medium text-red-600">-{{ number_format($order->discount_total, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="py-4 text-lg font-bold text-gray-900">Grand Total</td>
                            <td class="py-4 text-right text-lg font-bold text-gray-900">{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Footer / Action Buttons -->
        <div class="mt-12 text-center no-print">
            <button onclick="window.print()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                Print Invoice
            </button>
        </div>

    </div>
</body>
</html>
