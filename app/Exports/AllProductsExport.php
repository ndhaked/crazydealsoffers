<?php

namespace App\Exports;

use Modules\Products\Entities\Products;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllProductsExport implements FromCollection, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct()
    {
        //
    }

    public function collection() {

        $product = Products::all();
        return $product;
    }

    public function headings(): array {
        return [
           "S.No","Slug","Product Name","Category Id","Coupon Code","Image","Price","Off On Product","Expiry Data","Item Purchase Link","Description","Delete Status","Deal Of The Day","Status","Created Date","Updated Date"
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->slug,
            $product->name,
            $product->category_id,
            $product->coupon_code,
            $product->S3Url,
            $product->price,
            $product->off_on_product,
            $product->expiry_date,
            $product->item_purchase_link,
            $product->description,
            $product->delete_status,
            $product->deal_of_the_day,
            $product->status,
            $product->created_at,
            $product->updated_at,
        ];
    }
}
