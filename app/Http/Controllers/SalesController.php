<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\Normalization;

class SalesController extends Controller
{
    public function index()
    {
                // Ambil data penjualan
                $salesData = Sales::selectRaw('YEAR(sell_date) as year, MONTH(sell_date) as month, SUM(qty) as qty')
                ->groupByRaw('YEAR(sell_date), MONTH(sell_date)')
                ->orderByRaw('YEAR(sell_date)')
                ->orderByRaw('MONTH(sell_date)')
                ->get();
    
            $actualQtys = $salesData->pluck('qty')->toArray();
    
            $alphaBest = 0;
            $minMape = PHP_INT_MAX;
    
            // Coba nilai alpha dari 0.1 hingga 0.9
            for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
                $smoothedQtys = [];
                $lastQty = $actualQtys[0];  // Inisialisasi dengan qty penjualan pertama
    
                foreach ($actualQtys as $actualQty) {
                    $smoothedQty = $alpha * $actualQty + (1 - $alpha) * $lastQty;
                    $smoothedQtys[] = $smoothedQty;
                    $lastQty = $smoothedQty;
                }
    
                $mapes = [];
                foreach ($actualQtys as $index => $actualQty) {
                    $mape = abs(($actualQty - $smoothedQtys[$index]) / $actualQty) * 100;
                    $mapes[] = $mape;
                }
    
                $averageMape = array_sum($mapes) / count($mapes);
    
                if ($averageMape < $minMape) {
                    $minMape = $averageMape;
                    $alphaBest = $alpha;
                }
            }
    
            // Prediksi dengan alpha terbaik
            $predictions = [];
            $lastQty = $actualQtys[count($actualQtys) - 1];
    
            for ($i = 0; $i < 1; $i++) {
                $lastQty = $alphaBest * $lastQty + (1 - $alphaBest) * $lastQty;
    
                $currentYear = 2024;
                $currentMonth = 8 + $i;
                if ($currentMonth > 12) {
                    $currentMonth -= 12;
                    $currentYear++;
                }
    
                $predictions[] = [
                    'year' => $currentYear,
                    'month' => $currentMonth,
                    'predicted_qty' => $lastQty,
                    'alpha' => $alphaBest,
                    'mape' => $this->calculateMape($actualQtys, $lastQty),
                ];
            }
    
            // Temukan prediksi dengan MAPE terkecil
            $bestPrediction = collect($predictions)->sortBy('mape')->first();
    
            $sales = Sales::all();

            return view('sales.index', [
                'salesData' => $salesData,
                'predictions' => $predictions,
                'bestPrediction' => $bestPrediction,
                'alphaBest' => $alphaBest,
                'minMape' => $minMape,
                'sales' => $sales,
            ]);
    }

    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function show(Sales $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'sell_date' => 'required|date',
        ]);

        // Generate invoice number
        $invoiceNumber = 'INV-' . strtoupper(uniqid());

        // Create the sale with the generated invoice number
        Sales::create(array_merge($request->all(), [
            'invoice_number' => $invoiceNumber,
            'total' => $this->calculateTotal($request->product_id, $request->qty),
            'year' => date('Y', strtotime($request->sell_date)),
            'month' => date('m', strtotime($request->sell_date))
        ]));

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function calculateTotal($productId, $quantity)
    {
        // Retrieve the product price
        $product = Product::find($productId);

        if (!$product) {
            return 0;
        }

        // Calculate and return the total
        return $product->price * $quantity;
    }

    public function edit(Sales $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sales $sales)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'sell_date' => 'required|date',
        ]);

        $sales->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'total' => $this->calculateTotal($request->product_id, $request->qty),
            'sell_date' => $request->sell_date,
            'year' => date('Y', strtotime($request->sell_date)),
            'month' => date('m', strtotime($request->sell_date))
        ]);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sales $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function predict()
    {
        // Ambil data penjualan
        $salesData = Sales::selectRaw('YEAR(sell_date) as year, MONTH(sell_date) as month, SUM(qty) as qty')
            ->groupByRaw('YEAR(sell_date), MONTH(sell_date)')
            ->orderByRaw('YEAR(sell_date)')
            ->orderByRaw('MONTH(sell_date)')
            ->get();

        $actualQtys = $salesData->pluck('qty')->toArray();

        $alphaBest = 0;
        $minMape = PHP_INT_MAX;

        // Coba nilai alpha dari 0.1 hingga 0.9
        for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
            $smoothedQtys = [];
            $lastQty = $actualQtys[0];  // Inisialisasi dengan qty penjualan pertama

            foreach ($actualQtys as $actualQty) {
                $smoothedQty = $alpha * $actualQty + (1 - $alpha) * $lastQty;
                $smoothedQtys[] = $smoothedQty;
                $lastQty = $smoothedQty;
            }

            $mapes = [];
            foreach ($actualQtys as $index => $actualQty) {
                $mape = abs(($actualQty - $smoothedQtys[$index]) / $actualQty) * 100;
                $mapes[] = $mape;
            }

            $averageMape = array_sum($mapes) / count($mapes);

            if ($averageMape < $minMape) {
                $minMape = $averageMape;
                $alphaBest = $alpha;
            }
        }

        // Prediksi dengan alpha terbaik
        $predictions = [];
        $lastQty = $actualQtys[count($actualQtys) - 1];

        for ($i = 0; $i < 1; $i++) {
            $lastQty = $alphaBest * $lastQty + (1 - $alphaBest) * $lastQty;

            $currentYear = 2024;
            $currentMonth = 8 + $i;
            if ($currentMonth > 12) {
                $currentMonth -= 12;
                $currentYear++;
            }

            $predictions[] = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'predicted_qty' => $lastQty,
                'alpha' => $alphaBest,
                'mape' => $this->calculateMape($actualQtys, $lastQty),
            ];
        }

        // Temukan prediksi dengan MAPE terkecil
        $bestPrediction = collect($predictions)->sortBy('mape')->first();

        return view('sales.predict', [
            'salesData' => $salesData,
            'predictions' => $predictions,
            'bestPrediction' => $bestPrediction,
            'alphaBest' => $alphaBest,
            'minMape' => $minMape,
        ]);
    }

    private function calculateMape($actualQtys, $predictedQty)
    {
        $mapeSum = 0;
        $count = count($actualQtys);

        foreach ($actualQtys as $actualQty) {
            $mapeSum += abs(($actualQty - $predictedQty) / $actualQty) * 100;
        }

        return $mapeSum / $count;
    }

    private function calculateQty($productId, $qty)
    {
        $product = Product::find($productId);
        return $product->price * $qty;
    }
}



