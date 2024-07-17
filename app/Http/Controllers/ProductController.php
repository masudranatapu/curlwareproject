<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //
        $categories = Category::query()
            ->latest()
            ->get();
        $products = Product::query()
            ->when($request->keyword, fn ($q) => $q->where('name', 'LIKE', '%' . $request->keyword . '%'))
            ->when($request->category_id, fn ($q) => $q->where('category_id', $request->category_id))
            ->where('user_id', Auth::user()->id)
            ->with('category')
            ->get();
        return view('product.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $slug = strtolower(str_replace(' ', '_', $request->name)) . rand(1, 88888);
            $products = new Product();
            $products->category_id = $request->category_id;
            $products->user_id = Auth::user()->id;
            $products->name = $request->name;
            $products->price = $request->price;
            $products->slug = $slug;
            $products->save();
            DB::commit();
            return redirect()->back()->with('success', 'Product successfully Create');
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        //
        $categories = Category::query()
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();
        return view('product.edit', compact('categories'));
    }

    public function update(Request $request, string $id)
    {
        //
        try {
            DB::beginTransaction();
            $products = Product::query()
                ->findOrFail($id);
            $products->category_id = $request->category_id;
            $products->name = $request->name;
            $products->price = $request->price;
            $products->save();
            DB::commit();
            return redirect()->back()->with('success', 'Product successfully Update');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        //
        try {
            DB::beginTransaction();
            $products = Product::query()
                ->where('user_id', Auth::user()->id)
                ->findOrFail($id);
            $products->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Product successfully Delete');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new ProductsImport, $request->file('file'));
            DB::commit();
            return redirect()->back()->with('success', 'Products successfully imported');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
