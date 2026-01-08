<?php

use App\Models\Produit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // ✅ ضروري جداً لتجنب خطأ 500
use Illuminate\Support\Facades\Log;





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/wc/order-created', function (Request $request) {
    // 1. استقبال البيانات وتجاهل "طلب الاختبار" من ووردبريس لتجنب خطأ 400
    $webhookId = $request->header('X-WC-Webhook-ID');
    $items = $request->input('line_items');

    if (!$items) {
        return response()->json(['message' => 'Webhook received successfully (Ping)'], 200);
    }

    DB::beginTransaction();
    try {
        foreach ($items as $item) {
            $sku = $item['sku'];
            $qtySold = (int)$item['quantity'];

            // البحث عن المنتج مع قفل التحديث
            $produit = Produit::where('reference', $sku)->lockForUpdate()->first();

            if ($produit) {
                $oldStock = $produit->quantite_stock;
                
                // تحديث المخزون
                $produit->quantite_stock = $oldStock - $qtySold;
                $produit->save();

                // تسجيل الحركة (تأكدي أن ID المستخدم 1 موجود فعلياً في جدول users)
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
        return response()->json(['message' => 'Stock updated successfully'], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("🚨 Webhook Sync Error: " . $e->getMessage());
        return response()->json(['error' => 'Internal Error', 'details' => $e->getMessage()], 500);
    }
});