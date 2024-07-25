@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            tambah Produk
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Product</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group mt-4">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <button type="submit" class="btn btn-success mt-4">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
