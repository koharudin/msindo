@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Produk List</h5>
        @if (Auth::user()->role == 'admin')
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Produk</a>
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
                        @if (Auth::user()->role == 'admin')
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->stock }}</td>
                        <td>{{ $category->price }}</td>
                        @if (Auth::user()->role == 'admin')  
                        <td>
                            <a href="{{ route('categories.stock', $category->id) }}" class="btn btn-warning">Stock</a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
