@extends('layouts.app')

@section('content')
    <h1>Доставки</h1>

    <a href="{{ route('orders.create') }}" class="btn btn-primary">Добави нова поръчка</a>
    <a href="{{ url('/') }}" class="btn btn-primary">Склад</a>


    @if ($orders->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Статус</th>
                    <th>Клиент</th>
                    <th>Цялостна цена</th>
                    <th>Дата на създаване</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            @if ($order->status === 'Pending')
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary">Редакция</a>
                            @endif
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Преглед</a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="post" style="display: inline-block;">                    
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Сигурни ли сте, че искате да изтриете поръчката?')">Изтриване</button>
                            </form>
                            <a href="{{ route('orders.pdf', ['order' => $order->id]) }}" target="_blank">Изтегли като PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Няма налични поръчки.</p>
    @endif
@endsection