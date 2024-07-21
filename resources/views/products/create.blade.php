@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Add New Product</h1>
    <div class="card">
        <div class="card-header">
            Create Product
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group mt-4">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" required>
                </div>
                <div class="form-group mt-4">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <div class="form-group mt-4">
                    <label for="year">Buy Date</label>
                    <input type="date" class="form-control" id="buy_date" name="buy_date" required>
                </div>
                <button type="submit" class="btn btn-success mt-4">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
