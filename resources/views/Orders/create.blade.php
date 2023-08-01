@extends('layouts.app')

@section('content')
    <h2>Създайте нова поръчка</h2>

    <form id="order-form">
        @csrf

        <div class="mb-3">
            <label for="customer_name" class="form-label">Клиент</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>

        <div class="mb-3">
            <label for="product" class="form-label">Изберете продукт</label>
            <select class="form-select" id="product" name="product_id" required>
                <option value="">Изберете продукт</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-unit-price="{{ $product->unit_price }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Количество на продукта</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>

        <button type="button" class="btn btn-primary" onclick="addProductRow()">Добави Продукт</button>

        <div class="mb-3">
            <label for="status" class="form-label">Статус на поръчката</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Finished">Finished</option>
            </select>
        </div>
        <input type="hidden" name="total_price" id="total_price">
        <input type="hidden" name="created_at" value="{{ now() }}">

        <h3>Добавени продукти</h3>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Продукт</th>
                        <th>Единична цена</th>
                        <th>IMEI</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody id="product-list">
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success">Запази Поръчката</button>
    </form>

    <script>
        function addProductRow() {
            var productId = document.getElementById("product").value;
            var productName = document.getElementById("product").options[document.getElementById("product").selectedIndex].text;
            var unitPrice = parseFloat(document.getElementById("product").options[document.getElementById("product").selectedIndex].getAttribute('data-unit-price'));
            var quantity = document.getElementById("quantity").value;

            if (productId && quantity) {
                var tableBody = document.getElementById("product-list");

                for (var i = 0; i < quantity; i++) {
                    var newRow = tableBody.insertRow();

                    var cell1 = newRow.insertCell(0);
                    var cell2 = newRow.insertCell(1);
                    var cell3 = newRow.insertCell(2);
                    var cell4 = newRow.insertCell(3);
                    var cell5 = newRow.insertCell(4);

                    cell1.innerHTML = productId;
                    cell2.innerHTML = productName;
                    cell3.innerHTML = unitPrice.toFixed(2);
                    cell4.innerHTML = '<input type="text" class="form-control" name="imei[]" required>';
                    cell5.innerHTML = '<button type="button" class="btn btn-danger" onclick="removeProductRow(this)">Изтрий</button>';
                }
            }
        }

        function removeProductRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        document.getElementById('order-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            var tableRows = document.querySelectorAll('#product-list tr');
            var products = [];
            var total_price = 0;

            tableRows.forEach(function (row) {
                var productId = row.cells[0].innerText;
                var imeiInput = row.querySelector('input[name="imei[]"]');
                var imei = imeiInput.value.trim();
                var quantity = 1;
                products.push({ product_id: productId, quantity: quantity, imei: imei });
                var unitPrice = parseFloat(row.cells[2].innerText);

                if (!isNaN(unitPrice)) {
                    total_price += unitPrice;
                }
            });

            formData.append('products', JSON.stringify(products));
            formData.append('total_price', total_price.toFixed(2));

            fetch('{{ route("orders.storeAjax") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(response) {
                if (response.ok) {
                    alert('Поръчката беше добавена успешно!');
                    document.getElementById('order-form').reset();
                    document.getElementById('product-list').innerHTML = '';
                } else {
                    alert('Възникна грешка при запазване на поръчката. Моля, опитайте отново.');
                }
            })
            .catch(function(error) {
                console.error('Грешка при изпращане на формата:', error);
                alert('Възникна грешка при запазване на поръчката. Моля, опитайте отново.');
            });
        });
    </script>
@endsection