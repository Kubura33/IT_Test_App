<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Department;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //product_number,category_name,deparment_name,manufacturer_name,upc,sku,regular_price,sale_price,description
        $filePath = base_path('product_categories.csv');
        $data = [];
        $handle = fopen($filePath, "r");
        fgetcsv($handle); //Preskacem header
        while(($productData = fgetcsv($handle)) !== false) {
            $data[] =[
                'product_number' => $productData[0],
                'category_id' => $this->getCategory($productData[1]),
                'department_id' => $this->getDepartment($productData[2]),
                'manufacturer_id' => $this->getManufacturer($productData[3]),
                'upc' => $productData[4],
                'sku' => $productData[5],
                'regular_price_sale' => $productData[6],
                'sale_price' => $productData[7],
                'description' => $productData[8],
            ];
            if(count($data) === 1000){
                Product::insert($data);
                $data = [];
            }
        }
        if(! empty($data)){
            Product::insert($data);
        }

    }

    private function getCategory($categoryName): int
    {
        return Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])
            ->firstOrCreate(['name' => $categoryName])
            ->id;
    }

    private function getManufacturer($manufacturerName): int
    {
        return Manufacturer::whereRaw('LOWER(name) = ?', [strtolower($manufacturerName)])
            ->firstOrCreate(['name' => $manufacturerName])
            ->id;
    }

    private function getDepartment($departmentName): int
    {
        return Department::whereRaw('LOWER(name) = ?', [strtolower($departmentName)])
            ->firstOrCreate(['name' => $departmentName])
            ->id;
    }

}
