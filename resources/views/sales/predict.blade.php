@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Prediksi
        </div>
        <div class="card-body">
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
    
            {{-- Uncomment this section if you want to display the best prediction --}}
            {{-- @if ($bestPrediction)
                <div class="card-header">
                    Prediksi Terbaik
                </div>
                <p>Tahun: {{ $bestPrediction['year'] }}</p>
                <p>Bulan: {{ $bestPrediction['month'] }}</p>
                <p>Prediksi Total: {{ round($bestPrediction['predicted_qty']) }}</p>
                <p>Alpha : {{ round($alphaBest, 2) }}</p>
                <p>MAPE : {{ round($minMape, 2) }}%</p>
                <p>Safety Stock: {{ round($bestPrediction['safety_stock'], 2) }}</p>
                <p>ROP: {{ round($bestPrediction['rop'], 2) }}</p>
            @endif --}}
    
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesData as $data)
                        <tr>
                            <td>{{ $data->year }}</td>
                            <td>{{ $data->month }}</td>
                            <td>{{ round($data->qty) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
