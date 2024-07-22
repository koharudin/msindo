@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card">
      <div class="card-body">
        <h2>Prediksi Penjualan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Prediksi Total</th>
                    <th>Alpha</th>
                    <th>MAPE</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        @if ($bestPrediction)
            <h2>Prediksi Terbaik</h2>
            <p>Tahun: {{ $bestPrediction['year'] }}</p>
            <p>Bulan: {{ $bestPrediction['month'] }}</p>
            <p>Prediksi Total: {{ round($bestPrediction['predicted_qty']) }}</p>
            <p>Alpha Terbaik: {{ round($alphaBest, 2) }}</p>
            <p>MAPE Terbaik: {{ round($minMape, 2) }}%</p>
        @endif
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
