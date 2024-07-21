<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        return view('sales.index', compact('sales'));
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

    private function calculateTotal($productId, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            return $product->price * $quantity;
        }
        return 0;
    }


    public function calculateMape()
    {
        $sales = Sales::all();

        $actual = $sales->pluck('total')->toArray();
        $predicted = $this->getPredictedValues($sales);

        $mape = $this->getMape($actual, $predicted);

        $smallestMapes = $this->findSmallestMape($sales);

        return view('sales.mape', compact('mape', 'smallestMapes'));
    }

    private function getMape(array $actual, array $predicted): float
    {
        $n = count($actual);
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($actual[$i] != 0) {
                $sum += abs(($actual[$i] - $predicted[$i]) / $actual[$i]);
            }
        }
        return ($sum / $n) * 100;
    }

    private function findSmallestMape($sales)
    {
        $predictions = $this->getPredictedSets($sales);

        $mapes = [];
        foreach ($predictions as $pred) {
            $mapes[] = $this->getMape($sales->pluck('total')->toArray(), $pred);
        }

        $smallestMapeIndex = array_search(min($mapes), $mapes);
        return [
            'value' => $mapes[$smallestMapeIndex],
            'set' => $smallestMapeIndex + 1 // Index is 0-based
        ];
    }
}


