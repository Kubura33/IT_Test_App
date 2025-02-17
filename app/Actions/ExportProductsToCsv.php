<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportProductsToCsv
{
    public function execute(Collection $data): string {
        //Pod pretpostavkom da svi producti imaju istu kategoriju, jer je filter odradjen po parcijalnom matchu
        //product_number,category_name,deparment_name,manufacturer_name,upc,sku,regular_price,sale_price,description
        $fileName = $this->generateFileName($data->first()->category->name);
        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['product_number', 'category_name', 'manufacturer_name', 'department_name', 'upc', 'sku', 'regular_price', 'sale_price', 'description']);
        $data->each(function ($item) use ($csv) {
            fputcsv($csv, [
                $item->product_number,
                $item->category->name,
                $item->manufacturer->name,
                $item->department->name,
                $item->upc,
                $item->sku,
                $item->regular_price,
                $item->sale_price,
                $item->description
            ]);
        });
        rewind($csv);
        Storage::disk('public')->put($fileName, stream_get_contents($csv));
        fclose($csv);
        return Storage::disk('public')->path($fileName);
    }

    private function generateFileName($categoryName): string {
        return 'exports/' . Str::slug($categoryName, '_') . '_' . Carbon::now()->format('Y_m_d-H_i') . '.csv';
    }

}
