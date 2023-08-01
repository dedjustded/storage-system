@extends('layouts.app')

@section('content')
    <h1>Детайли за продукт</h1>

    <ul>
        <li>ID: {{ $product->id }}</li>
        <li>Име: {{ $product->name }}</li>
        <li>Единична цена: {{ $product->unit_price }}</li>
        <li>Наличност: {{ $product->availability }}</li>
        <li>Цялостна цена: {{ $product->total_price }}</li>
    </ul>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">Обратно към списъка с продукти</a>
@endsection