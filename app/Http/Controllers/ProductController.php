<?php

namespace App\Http\Controllers;

use App\Actions\ExportProductsToCsv;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return response()->json(ProductResource::collection(Product::filter($request->all())->get()));
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json(new ProductResource($product));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return response()->json(new ProductResource($product));
    }

    public function exportByCategory(Request $request, string $category_name){
        $products = Product::with(['department:id,name','category:id,name','manufacturer:id,name'])->filter(['category_name' => $category_name])->get();
        if($products->isEmpty()){
            return response()->json(['message' => 'No products found for this category'], 404);
        }
        $filePath = app(ExportProductsToCsv::class)->execute($products);


       return response()->download($filePath)->deleteFileAfterSend(true);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([],204);
    }
}
