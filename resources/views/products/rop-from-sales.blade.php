@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Reorder Point (ROP) Based on Sales Data</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Reorder Point (ROP)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ round($product->rop) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection