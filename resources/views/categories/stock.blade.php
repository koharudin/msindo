@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Stock List</h5>
        @if (Auth::user()->role == 'admin')
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Stock</a>
        @endif
    </div>
    <div class="card">
        <div class="card-header">
           Prediksi
        </div>
        <div class="card-body">
            @if ($bestPrediction)
            <p>Hasil Prediksi untuk bulan {{ $bestPrediction['month'] }} - {{ $bestPrediction['year'] }} adalah {{ round($bestPrediction['predicted_qty']) }} dengan alpha {{ round($alphaBest, 2) }}</p>
            @endif
     </div>
        <div class="card-header">
            Product Table
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Tanggal Pembelian</th>
                        @if (Auth::user()->role == 'admin')
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->category['name'] }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->buy_date }}</td>
                        @if (Auth::user()->role == 'admin')  
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
