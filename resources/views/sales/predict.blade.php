@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Prediksi
        </div>
        <div class="card-body">
            <!-- Print Button -->
            <button class="btn btn-primary mb-3" onclick="printTable()">Print Card</button>

            <!-- Prediction Table -->
            <div  id="printableArea">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Prediksi Total</th>
                            <th>Alpha</th>
                            <th>MAPE</th>
                            <th>Safety Stock</th>
                            <th>ROP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($predictions as $prediction)
                            <tr>
                                <td>{{ $prediction['year'] }}</td>
                                <td>{{ $prediction['month'] }}</td>
                                <td>{{ round($prediction['predicted_qty']) }}</td>
                                <td>{{ round($prediction['alpha'], 2) }}</td>
                                <td>{{ round($prediction['mape'], 2) }}%</td>
                                <td>{{ round($prediction['safety_stock']) }}</td>
                                <td>{{ round($prediction['rop']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <!-- Sales Data Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Total Penjualan</th>
                            <th>Prediksi Total</th>
                            <th>APE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesData as $data)
                            <tr>
                                <td>{{ $data->year }}</td>
                                <td>{{ $data->month }}</td>
                                <td>{{ round($data->qty) }}</td>
                                <td>{{ round($data->predicted_qty ?? 0) }}</td>
                                <td>{{ isset($data->ape) ? number_format($data->ape, 2) . '%' : 'N/A' }}</td>
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