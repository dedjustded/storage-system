@extends('layouts.app')

@section('content')
    <h1>Създаване на нов продукт</h1>

    <form action="{{ route('products.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Име</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Единична цена</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="availability">Наличност</label>
            <input type="number" name="availability" id="availability" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Запази</button>
    </form>
@endsection