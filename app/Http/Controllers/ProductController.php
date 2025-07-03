<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import model Product
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; // Untuk DataTables

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            // Pilih kolom yang ingin ditampilkan di DataTables
            $products = Product::select(['id', 'name', 'price', 'stock']);
            return DataTables::of($products)
                ->addColumn('action', function($row){
                    // Tombol Edit dan Delete untuk DataTables
                    $btn = '<a href="'.route('products.edit', $row->id).'" class="edit btn btn-warning btn-sm">Edit</a>';
                    // Gunakan class .delete-btn-product dan data-id untuk event delegation JavaScript
                    $btn .= ' <button class="btn btn-danger btn-sm delete-btn-product" data-id="'.$row->id.'">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action']) // Render kolom 'action' sebagai HTML
                ->make(true);
        }

        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        // Menggunakan flash message untuk SweetAlert2
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        // Menggunakan flash message untuk SweetAlert2
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        // Menggunakan flash message untuk SweetAlert2
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}