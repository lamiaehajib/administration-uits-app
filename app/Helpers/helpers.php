<?php

if (!function_exists('user_home_route')) {
    function user_home_route() {
        if (!auth()->check()) {
            return url('/');
        }

        $user = auth()->user();

        if ($user->hasRole('Admin') || $user->hasRole('Admin2')) {
            return route('benefice-marge.dashboard');
        }

        if ($user->hasRole('Gérant_de_stock') || $user->hasRole('Vendeur')) {
            return route('dashboardstock');
        }

        return route('dashboard');
    }
}