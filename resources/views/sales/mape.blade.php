@extends('layouts.main')

@section('content')
<div class="container">
    <h1>MAPE Analysis</h1>
    <div class="card">
        <div class="card-header">
            MAPE Results
        </div>
        <div class="card-body">
            <p><strong>Calculated MAPE:</strong> {{ number_format($mape, 2) }}%</p>
            <p><strong>Smallest MAPE:</strong> {{ number_format($smallestMapes['value'], 2) }}% (Set {{ $smallestMapes['set'] }})</p>
        </div>
    </div>
</div>
@endsection