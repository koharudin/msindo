@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Sales</h5>
        @if (Auth::user()->role == 'admin')
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Create Sales</a>
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
            Sales Table
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Invoice Number</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Sell Date</th>
                        @if (Auth::user()->role == 'admin')
                        <th>Actions</th>         
                        @else
                        <th>Nota</th>         
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->name }}</td>
                        <td>{{ $sale->phone }}</td>
                        <td>{{ $sale->address }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->product->name }}</td>
                        <td>{{ $sale->qty }}</td>
                        <td>{{ $sale->total }}</td>
                        <td>{{ $sale->sell_date }}</td>
                        <td>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning">Print</a>
                            @if (Auth::user()->role == 'admin')
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
