<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Поръчка № {{ $order->id }}</title>
    <style>
    </style>
</head>
<body>
    <h1>Детайли за поръчка</h1>
    <ul>
        <li>ID: {{ $order->id }}</li>
        <li>Статус: {{ $order->status }}</li>
        <li>Клиент: {{ $order->customer_name }}</li>
        <li>Цялостна цена: {{ $order->total_price }}</li>
        <li>Дата на създаване: {{ $order->created_at }}</li>
        <li>Дата на приключване: {{ $order->finished_at }}</li>
    </ul>

    <h2>Продукти в поръчката</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Поръчка ID</th>
                <th>Продукт</th>
                <th>Единична цена</th>
                <th>IMEI</th>
                <th>Дата на създаване</th>
                <th>Дата на обновяване</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->pivot->order_id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->pivot->unit_price }}</td>
                    <td>{{ $item->pivot->imei }}</td>
                    <td>{{ $item->pivot->created_at }}</td>
                    <td>{{ $item->pivot->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>