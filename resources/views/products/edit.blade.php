@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Edit Product</h1>
    <div class="card">
        <div class="card-header">
            Edit Product
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group  mt-4">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                </div>
                <div class="form-group  mt-4">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                </div>
                <div class="form-group  mt-4">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                </div>
                <div class="form-group mt-4">
                    <label for="year">Buy Date</label>
                    <input type="date" class="form-control" id="buy_date" name="buy_date" value="{{ $product->buy_date }}" required>
                </div>
                <button type="submit" class="btn btn-success mt-4">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
