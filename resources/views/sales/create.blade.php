@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Add New Sale</h1>
    <div class="card">
        <div class="card-header">
            Create Sale
        </div>
        <div class="card-body">
            <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="form-group mt-4">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group mt-4">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group mt-4">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group mt-4">
                    <label for="product_id">Product</label>
                    <select class="form-control" id="product_id" name="product_id" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->name }} - ${{ $product->price }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label for="qty">Quantity</label>
                    <input type="number" class="form-control" id="qty" name="qty" required>
                </div>
                <div class="form-group mt-4">
                    <label for="sell_date">Sell Date</label>
                    <input type="date" class="form-control" id="sell_date" name="sell_date" required>
                </div>
                <button type="submit" class="btn btn-success mt-4">Submit</button>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('sale-form');
            const productSelect = document.getElementById('product_id');
            const qtyInput = document.getElementById('qty');

            form.addEventListener('submit', function (event) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const stock = parseInt(selectedOption.getAttribute('data-stock'), 10);
                const qty = parseInt(qtyInput.value, 10);

                if (qty > stock) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Sisa stock telah mencapai Safety Stock. Apakah mau melanjutkan transaksi?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed',
                        cancelButtonText: 'No, cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form if confirmed
                        }
                    });
                }
            });
        });
    </script>
@endpush
@endsection
