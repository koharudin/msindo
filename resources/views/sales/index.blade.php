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
            <button onclick="printTable()" class="btn btn-secondary mb-3">Print Table</button>
            <div id="printableArea">
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
                            <th class="action-column">Actions</th>         
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
                            <td>{{ $sale->category['name'] }}</td>
                            <td>{{ $sale->qty }}</td>
                            <td>{{ 'Rp ' . number_format($sale->total, 0, ',', '.') }}</td>
                            <td>{{ $sale->sell_date }}</td>
                            <td class="action-column">
                                @if (Auth::user()->role == 'admin')
                                <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-warning">Print</a>
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
</div>
@endsection

@push('js')
<script>
function printTable() {
    var originalContents = document.body.innerHTML;
    var printContents = document.getElementById('printableArea').innerHTML;
    var actionColumns = document.querySelectorAll('.action-column');
    
    // Hide action columns for print
    actionColumns.forEach(function(column) {
        column.style.display = 'none';
    });

    // Replace body content with printable area content and trigger print
    document.body.innerHTML = printContents;
    window.print();

    // Restore original body content and show action columns again
    document.body.innerHTML = originalContents;
}
</script>
@endpush

@push('css')
<style>
@media print {
    .action-column {
        display: none;
    }
}
</style>
@endpush
