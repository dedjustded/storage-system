@extends('layouts.app')

@section('content')
    <h1>Продукти</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary">Добави нов продукт</a>
    <a href="{{ route('orders.index') }}" class="btn btn-primary">Преглед на поръчки</a>

    @if ($products->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Име</th>
                    <th>Единична цена</th>
                    <th>Наличност</th>
                    <th>Цялостна цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->unit_price }}</td>
                        <td>{{ $product->availability }}</td>
                        <td>{{ $product->total_price }}</td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Преглед</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">Редакция</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Сигурни ли сте, че искате да изтриете продукта?')">Изтриване</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Няма налични продукти.</p>
    @endif
@endsection