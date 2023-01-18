<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return  Product::updateOrCreate([
            'sku'=>$row[0],
            ],[
            'name'=>$row[1],
            'unitPrice'=>$row[2],
            'minQty'=>$row[3],
            'multQty'=>$row[4],
            'barcode'=>$row[5],
            'longDesc'=>$row[6],
            'category'=>$row[7],
        ]);
    }
}
