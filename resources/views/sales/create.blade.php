@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Add New Sale</h1>
    <div class="card">
        <div class="card-header">
            Create Sale
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST">
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
                            <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
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
@endsection
