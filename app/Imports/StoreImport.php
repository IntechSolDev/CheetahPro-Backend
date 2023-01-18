<?php

namespace App\Imports;

use App\Models\Store;
use Maatwebsite\Excel\Concerns\ToModel;

class StoreImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return Store::updateOrCreate([
            'store_name'=>$row[0]],[
            'salesman_name'=>$row[1],
            'address'=>$row[2],
            'phone_no'=>$row[3],
            'city'=>$row[4],
            'state'=>$row[5],
            'zip_code'=>$row[6],
        ]);
    }
}
