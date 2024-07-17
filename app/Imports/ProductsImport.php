<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'category_id' => null,
            'user_id' => Auth::user()->id,
            'name' => $row['name'],
            'price' => $row['price'],
            'slug' => strtolower(str_replace(' ', '_', $row['name'])) . rand(1, 88888),
        ]);
    }
}
