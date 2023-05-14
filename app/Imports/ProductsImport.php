<?php

namespace App\Imports;

use Modules\Products\Entities\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\Categories\Entities\Categories;

class ProductsImport implements ToModel, WithHeadingRow
{
    use Importable;    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $rows)
    {
        $importarrays = array_chunk($rows, 3000);

        foreach ($importarrays as $row) 
        { 
            if($row[1]!=0){
                if (Categories::where('id', $row[1])->exists()) {
                    $filename = 'noimage.jpg';
                    if ($row[3] != '') {
                        $url = $row[3];
                        $name = substr($url, strrpos($url, '/') + 1);
                        $ext  = str_replace('?dl=0','',last(explode('.',$name)));
                        $filename = time().'.'.$ext;
                        $filePath = 'images/products/' . $filename;
                        \Storage::disk('s3')->put($filePath, file_get_contents($url),'public');
                    }
                    Products::create([
                        'name' => $row[0],
                        'category_id' => $row[1],
                        'coupon_code' => $row[2],
                        'image' => $filename,
                        'price' => $row[4],
                        'off_on_product' => $row[5],
                        'expiry_date' => date("Y-m-d", strtotime($row[6])),
                        'item_purchase_link' => $row[7],
                        'description' => $row[8],
                        //'delete_status' => $row[9],
                        //'deal_of_the_day' => $row[10],
                        //'status' => $row[11],
                    ]); 
                }
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
