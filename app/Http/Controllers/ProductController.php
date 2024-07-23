<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
                // Ambil data penjualan
                $salesData = Sales::selectRaw('YEAR(sell_date) as year, MONTH(sell_date) as month, SUM(qty) as qty')
                ->groupByRaw('YEAR(sell_date), MONTH(sell_date)')
                ->orderByRaw('YEAR(sell_date)')
                ->orderByRaw('MONTH(sell_date)')
                ->get();
    
            $actualTotals = $salesData->pluck('qty')->toArray();
    
            $alphaBest = 0;
            $minMape = PHP_INT_MAX;
    
            // Coba nilai alpha dari 0.1 hingga 0.9
            for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
                $smoothedTotals = [];
                $lastTotal = $actualTotals[0];  // Inisialisasi dengan qty penjualan pertama
    
                foreach ($actualTotals as $actualTotal) {
                    $smoothedTotal = $alpha * $actualTotal + (1 - $alpha) * $lastTotal;
                    $smoothedTotals[] = $smoothedTotal;
                    $lastTotal = $smoothedTotal;
                }
    
                $mapes = [];
                foreach ($actualTotals as $index => $actualTotal) {
                    $mape = abs(($actualTotal - $smoothedTotals[$index]) / $actualTotal) * 100;
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
            $lastTotal = $actualTotals[count($actualTotals) - 1];
    
            for ($i = 0; $i < 1; $i++) {
                $lastTotal = $alphaBest * $lastTotal + (1 - $alphaBest) * $lastTotal;
    
                $currentYear = 2024;
                $currentMonth = 7 + $i;
                if ($currentMonth > 12) {
                    $currentMonth -= 12;
                    $currentYear++;
                }
    
                $predictions[] = [
                    'year' => $currentYear,
                    'month' => $currentMonth,
                    'predicted_qty' => $lastTotal,
                    'alpha' => $alphaBest,
                    'mape' => $this->calculateMape($actualTotals, $lastTotal),
                ];
            }
    
            // Temukan prediksi dengan MAPE terkecil
            $bestPrediction = collect($predictions)->sortBy('mape')->first();
            
            $products = Product::all();

            return view('products.index', [
                'salesData' => $salesData,
                'predictions' => $predictions,
                'bestPrediction' => $bestPrediction,
                'alphaBest' => $alphaBest,
                'minMape' => $minMape,
                'products' => $products,
            ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'buy_date' => 'required|date',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'buy_date' => 'required|date',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    
    private function calculateMape($actualTotals, $predictedTotal)
    {
        $mapeSum = 0;
        $count = count($actualTotals);

        foreach ($actualTotals as $actualTotal) {
            $mapeSum += abs(($actualTotal - $predictedTotal) / $actualTotal) * 100;
        }

        return $mapeSum / $count;
    }

    private function calculateTotal($productId, $qty)
    {
        $product = Product::find($productId);
        return $product->price * $qty;
    }

    public function showRopFromSales()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $rop = $this->calculateRopFromSales($product);
            $product->rop = $rop;
        }

        return view('products.rop-from-sales', [
            'products' => $products,
        ]);
    }

    private function calculateRopFromSales(Product $product)
    {
        // Example: Replace with actual lead time
        $leadTime = 7; // days
        
        // Get average daily usage
        $averageDailyUsage = $this->calculateAverageDailyUsage($product);

        // Get safety stock
        $safetyStock = $this->calculateSafetyStock($product);

        return ($averageDailyUsage * $leadTime) + $safetyStock;
    }

    private function calculateAverageDailyUsage(Product $product)
    {
        // Calculate the average daily usage of the product based on sales data
        $sales = Sales::where('product_id', $product->id)->get();
        $totalQuantity = $sales->sum('qty');
        $totalDays = $sales->count();

        return $totalDays ? $totalQuantity / $totalDays : 0;
    }

    private function calculateSafetyStock(Product $product)
    {
        // Example: Z-score for 95% service level
        $zScore = 1.65;
        $standardDeviation = $this->calculateDemandStandardDeviation($product);

        return $zScore * $standardDeviation;
    }

    private function calculateDemandStandardDeviation(Product $product)
    {
        // Calculate the standard deviation of demand for the product
        $sales = Sales::where('product_id', $product->id)->get();
        $totalQuantity = $sales->sum('qty');
        $mean = $totalQuantity / $sales->count();
        $sumOfSquares = $sales->reduce(function ($carry, $sale) use ($mean) {
            return $carry + pow($sale->qty - $mean, 2);
        }, 0);

        return sqrt($sumOfSquares / ($sales->count() - 1));
    }
}
