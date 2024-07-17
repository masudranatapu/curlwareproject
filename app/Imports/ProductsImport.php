<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $existCategory = Category::query()
            ->where('user_id', Auth::user()->id)
            ->where('name', $row['category'])
            ->first();

        if ($existCategory) {
            $category = $existCategory;
        } else {
            $category = Category::firstOrCreate([
                'user_id' => Auth::user()->id,
                'name' => $row['category'],
            ]);
        }

        return new Product([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'name' => $row['name'],
            'price' => $row['price'],
            'slug' => strtolower(str_replace(' ', '_', $row['name'])) . rand(1, 88888),
        ]);
    }
}
