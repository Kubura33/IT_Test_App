<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Department;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $filePath = base_path('product_categories.csv');
        $skippedProducts = [];
        $data = [];
        $now = now();

        $handle = fopen($filePath, "r");
        fgetcsv($handle); // Preskacem prvi red sto je header

        while (($productData = fgetcsv($handle)) !== false) {
            $productNumber = $productData[0];
            $upc = $productData[4];
            $sku = $productData[5];

            $existingProduct = Product::where('product_number', $productNumber)
                ->orWhere('upc', $upc)
                ->orWhere('sku', $sku)
                ->exists();

            if ($existingProduct) {
                $skippedProducts[] = [
                    'product_number' => $productNumber,
                    'upc' => $upc,
                    'sku' => $sku,
                ];
                continue; // Preskacemo duplicate
            }

            $data[] = [
                'product_number' => $productNumber,
                'category_id' => $this->getCategory($productData[1]),
                'department_id' => $this->getDepartment($productData[2]),
                'manufacturer_id' => $this->getManufacturer($productData[3]),
                'upc' => $upc,
                'sku' => $sku,
                'regular_price' => $productData[6],
                'sale_price' => $productData[7],
                'description' => $productData[8],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($data) === 150) {
                Product::insert($data);
                $data = [];
            }
        }

        fclose($handle);

        if (!empty($data)) {
            Product::insert($data);
        }

        if (!empty($skippedProducts)) {
            Log::info("Skipped: ", $skippedProducts);
            $this->warn("Skipped " . count($skippedProducts) . " duplicate products. Check logs for details.");
        } else {
            $this->info("All products imported successfully without duplicates.");
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
