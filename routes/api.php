<?php

use App\Models\Produit;
use App\Models\StockMovement;
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
    // 1. جلب البيانات المرسلة
    $items = $request->input('line_items');
    if (!$items) return response()->json(['message' => 'No items'], 400);

    DB::beginTransaction();
    try {
        foreach ($items as $item) {
            $sku = $item['sku'];
            $qtySold = (int)$item['quantity'];

            // 2. البحث عن المنتج (استخدام lockForUpdate لتفادي تضارب البيانات)
            $produit = Produit::where('reference', $sku)->lockForUpdate()->first();

            if ($produit) {
                $oldStock = $produit->quantite_stock;
                
                // 3. تحديث المخزون يدوياً لتفادي أي Hooks تعيق العملية
                $produit->quantite_stock = $oldStock - $qtySold;
                $produit->save();

                // 4. تسجيل الحركة (تأكدي أن user_id = 1 موجود في جدول users)
                StockMovement::create([
                    'produit_id'  => $produit->id,
                    'user_id'     => 1, 
                    'type'        => 'sortie',
                    'quantite'    => $qtySold,
                    'stock_avant' => $oldStock,
                    'stock_apres' => $produit->quantite_stock,
                    'motif'       => 'Vente WordPress: ' . ($request->input('number') ?? 'Web'),
                ]);
            }
        }
        DB::commit();
        return response()->json(['message' => 'Success'], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        \Illuminate\Support\Facades\Log::error("Webhook Sync Error: " . $e->getMessage());
        return response()->json(['error' => 'Internal Error', 'details' => $e->getMessage()], 500);
    }
});