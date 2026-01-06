<?php

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/wc/order-created', function (Request $request) {
    $items = $request->input('line_items');

    foreach ($items as $item) {
        $sku = $item['sku']; // الـ SKU في WordPress
        $qtySold = $item['quantity'];

        // كنقلبو على المنتج في Laravel باستعمال الـ reference
        $produit = Produit::where('reference', $sku)->first();

        if ($produit) {
            // تنقيص المخزون
            $produit->decrement('quantite_stock', $qtySold);

            // تسجيل الحركة في الـ Movements اللي عندك في الموديل
            $produit->stockMovements()->create([
                'type' => 'sortie',
                'quantite' => $qtySold,
                'stock_avant' => $produit->quantite_stock + $qtySold,
                'stock_apres' => $produit->quantite_stock,
                'motif' => 'Vente WordPress Site',
                'user_id' => 1 
            ]);
        }
    }
    return response()->json(['message' => 'Success'], 200);
});