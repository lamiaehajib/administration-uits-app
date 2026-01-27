<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UitsMgmtApiService
{
    protected $baseUrl;
    protected $timeout = 30;

    public function __construct()
    {
        // ✅ ضع الرابط في .env
        $this->baseUrl = config('services.uits_mgmt.api_url', 'https://uits-mgmt.ma/api');
    }

    /**
     * ✅ 1. الحصول على مجموع الرواتب
     */
    public function getTotalSalaires()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/salaires/total");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erreur API getTotalSalaires', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Exception API getTotalSalaires: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ 2. الحصول على قائمة الموظفين
     */
    public function getEmployees()
    {
        try {
            // Cache لمدة 5 دقائق
            return Cache::remember('uits_mgmt_employees', 300, function () {
                $response = Http::timeout($this->timeout)
                    ->get("{$this->baseUrl}/employees");

                if ($response->successful()) {
                    return $response->json('employees', []);
                }

                return [];
            });

        } catch (\Exception $e) {
            Log::error('Exception API getEmployees: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ 3. الحصول على موظف محدد
     */
    public function getEmployee($id)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/employees/{$id}");

            if ($response->successful()) {
                return $response->json('employee');
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Exception API getEmployee({$id}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ 4. الحصول على رواتب الشهر الحالي (مفصلة)
     */
    public function getSalairesDetailsMois($annee = null, $mois = null)
    {
        try {
            $annee = $annee ?? now()->year;
            $mois = $mois ?? now()->month;

            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/salaires/mois-actuel");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'annee' => $data['annee'],
                    'mois' => $data['mois'],
                    'salaires' => $data['salaires'],
                    'total' => $data['total'],
                    'count' => $data['count'],
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Exception API getSalairesDetailsMois: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ 5. الحصول على رواتب حسب المنصب
     */
    public function getSalairesParPoste()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/salaires/par-poste");

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Exception API getSalairesParPoste: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ 6. التحقق من الاتصال بالـ API
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->baseUrl}/employees");

            return $response->successful();

        } catch (\Exception $e) {
            return false;
        }
    }
}