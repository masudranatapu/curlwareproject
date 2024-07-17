<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        //
        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();
        $subCategoryList = Category::query()
            ->whereNotNull('parent_id')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();

        $parentCategory = Category::query()
            ->whereNull('parent_id')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();
        return view('category.index', compact('categories', 'parentCategory', 'subCategoryList'));
    }

    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->parent_id = $request->parent_id ? $request->parent_id : null;
            $category->user_id = Auth::user()->id;
            $category->name = $request->name;
            $category->save();
            DB::commit();
            return redirect()->back()->with('success', 'Category successfully create');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::query()
                ->where('user_id', Auth::user()->id)
                ->findOrFail($id);
            $category->parent_id = $request->parent_id ? $request->parent_id : null;
            $category->name = $request->name;
            $category->save();
            DB::commit();
            return redirect()->back()->with('success', 'Category successfully update');
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
            $category = Category::query()
                ->where('user_id', Auth::user()->id)
                ->findOrFail($id);

            $isCategory = Category::query()
                ->where('parent_id', $category->id)
                ->where('user_id', Auth::user()->id)
                ->get();
            if ($isCategory->count() > 0) {
                return redirect()->back()->with('success', 'Category has some sub category. can not delete');
            } else {

                $category->delete();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Category successfully delete');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('success', $e->getMessage());
        }
    }
}
