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



Route::post('/wc/order-created', function (Illuminate\Http\Request $request) {
    $items = $request->input('line_items');

    if (!$items) {
        return response()->json(['message' => 'No items found'], 400);
    }

    foreach ($items as $item) {
        $sku = $item['sku'];
        $qtySold = $item['quantity'];

        $produit = App\Models\Produit::where('reference', $sku)->first();

        if ($produit) {
            // 1. كنقصو السلعة مباشرة
            $produit->quantite_stock = $produit->quantite_stock - $qtySold;
            $produit->save();

            // 2. كنسجلو الحركة بطريقة أضمن
            try {
                \App\Models\StockMovement::create([
                    'produit_id'  => $produit->id,
                    'type'        => 'sortie',
                    'quantite'    => $qtySold,
                    'stock_avant' => $produit->quantite_stock + $qtySold,
                    'stock_apres' => $produit->quantite_stock,
                    'motif'       => 'Vente WordPress Site',
                    'user_id'     => 1
                ]);
            } catch (\Exception $e) {
                // إيلا كان مشكل غير في تسجيل الحركة، السلعة غاتنقص والسيستم ما غايوقفش
                \Illuminate\Support\Facades\Log::error("Movement Error: " . $e->getMessage());
            }
        }
    }
    return response()->json(['message' => 'Success'], 200);
});