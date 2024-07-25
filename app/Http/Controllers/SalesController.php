<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Phpml\Dataset\ArrayDataset;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use Phpml\FeatureExtraction\Normalization;

class SalesController extends Controller
{
    public function index()
    {
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
            $lastQty = $actualQtys[0];
    
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
    
        $predictions = [];
        $lastQty = $actualQtys[count($actualQtys) - 1];
    
        $lastYear = $salesData->last()->year;
        $lastMonth = $salesData->last()->month;
    
        $currentMonth = $lastMonth + 1;
        $currentYear = $lastYear;
    
        if ($currentMonth > 12) {
            $currentMonth -= 12;
            $currentYear++;
        }
    
        for ($i = 0; $i < 1; $i++) {
            $lastQty = $alphaBest * $lastQty + (1 - $alphaBest) * $lastQty;
    
            $predictions[] = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'predicted_qty' => $lastQty,
                'alpha' => $alphaBest,
                'mape' => $this->calculateMape($actualQtys, $lastQty),
            ];
        }
    
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
        $categories = Category::all();
        return view('sales.create', compact('categories'));
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
            'category_id' => 'required|exists:categories,id',
            'qty' => 'required|integer|min:1',
            'sell_date' => 'required|date',
        ]);
    
        $category = Category::find($request->category_id);
    
        if (!$category) {
            return redirect()->back()->withErrors(['category_id' => 'Category not found']);
        }
    
        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        $total = $category->price * $request->qty;
    
        Sales::create(array_merge($request->all(), [
            'invoice_number' => $invoiceNumber,
            'total' => $total,
            'year' => date('Y', strtotime($request->sell_date)),
            'month' => date('m', strtotime($request->sell_date))
        ]));
    
        $category->stock -= $request->qty;
        $category->save();
    
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

    public function print(Sales $sale)
    {
        return view('sales.print', compact('sale'));
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
        $salesData = Sales::selectRaw('YEAR(sell_date) as year, MONTH(sell_date) as month, SUM(qty) as qty')
            ->groupByRaw('YEAR(sell_date), MONTH(sell_date)')
            ->orderByRaw('YEAR(sell_date)')
            ->orderByRaw('MONTH(sell_date)')
            ->get();
    
        $actualQtys = $salesData->pluck('qty')->toArray();
    
        $alphaBest = 0;
        $minMape = PHP_INT_MAX;
    
        for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
            $smoothedQtys = [];
            $lastQty = $actualQtys[0];
    
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
    
        $predictions = [];
        $lastQty = $actualQtys[count($actualQtys) - 1];
    
        $lastYear = $salesData->last()->year;
        $lastMonth = $salesData->last()->month;
    
        $currentMonth = $lastMonth + 1;
        $currentYear = $lastYear;
    
        if ($currentMonth > 12) {
            $currentMonth -= 12;
            $currentYear++;
        }
    
        $safetyStock = $this->calculateSafetyStock($actualQtys);
    
        for ($i = 0; $i < 1; $i++) {
            $lastQty = $alphaBest * $lastQty + (1 - $alphaBest) * $lastQty;
    
            $ROP = $lastQty + $safetyStock;
    
            $predictions[] = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'predicted_qty' => $lastQty,
                'alpha' => $alphaBest,
                'mape' => $this->calculateMape($actualQtys, $lastQty),
                'safety_stock' => $safetyStock,
                'rop' => $ROP,
            ];
            
            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth -= 12;
                $currentYear++;
            }
        }
    
        $bestPrediction = collect($predictions)->sortBy('mape')->first();
    
        $sales = Sales::all();
    
        return view('sales.predict', [
            'salesData' => $salesData,
            'predictions' => $predictions,
            'bestPrediction' => $bestPrediction,
            'alphaBest' => $alphaBest,
            'minMape' => $minMape,
            'sales' => $sales,
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
    
    private function calculateSafetyStock($data)
    {
        $meanDemand = array_sum($data) / count($data);
        $variance = 0;
        
        foreach ($data as $value) {
            $variance += pow($value - $meanDemand, 2);
        }
        
        $stdDeviation = sqrt($variance / count($data));
        $serviceLevel = 1.65; // Contoh service level 95% (z-score)
    
        return $serviceLevel * $stdDeviation;
    }
    
}



