<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class PermissionseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ 1. إنشاء جميع الصلاحيات
        $permissions = [
            // Gestion des rôles
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Gestion des utilisateurs
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Gestion des produits
            'produit-list',
            'produit-create',
            'produit-edit',
            'produit-delete',
            'produit-rapport',
            'produit-export',

            // Gestion des catégories
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            // Gestion des achats
            'achat-list',
            'achat-create',
            'achat-edit',
            'achat-delete',

            // Gestion des reçus (ventes)
            'recu-list',
            'recu-create',
            'recu-edit',
            'recu-delete',
            'recu-print',
            'recu-statut-change',
            'recu-statistiques',

            // Gestion des paiements
            'paiement-list',
            'paiement-create',
            'paiement-delete',
            'paiement-rapport',

            // Gestion du stock
            'stock-view',
            'stock-movement-list',
            'stock-adjustment',

            // Rapports et statistiques
            'dashboard-view',
            'rapport-ventes',
            'rapport-achats',
            'rapport-global',
            //dashboard
            'benefice-ucgs-view',
            'benefice-brut-uits-view',

            //depense uits
            'depense-dashboard-uits',
            'depense-fix',
            'depense-variable',
            'budget-view',    // <--- Bach ichouf l-budgets
    'salaire-view',
        ];

        // Créer toutes les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ✅ 2. Créer les rôles avec leurs permissions

        // ADMIN - Accès complet à tout
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // GÉRANT - Gestion complète sauf rôles/users
        $gerantRole = Role::firstOrCreate(['name' => 'Gérant']);
        $gerantRole->syncPermissions([
            'produit-list', 'produit-create', 'produit-edit', 'produit-delete', 'produit-rapport', 'produit-export',
            'category-list', 'category-create', 'category-edit', 'category-delete',
            'achat-list', 'achat-create', 'achat-edit', 'achat-delete',
            'recu-list', 'recu-create', 'recu-edit', 'recu-delete', 'recu-print', 'recu-statut-change', 'recu-statistiques',
            'paiement-list', 'paiement-create', 'paiement-delete', 'paiement-rapport',
            'stock-view', 'stock-movement-list', 'stock-adjustment',
            'dashboard-view', 'rapport-ventes', 'rapport-achats', 'rapport-global',
        ]);

        // VENDEUR - Création ventes + consultation stock
        $vendeurRole = Role::firstOrCreate(['name' => 'Vendeur']);
        $vendeurRole->syncPermissions([
            'produit-list',
            'recu-list', 'recu-create', 'recu-print',
            'paiement-create',
            'stock-view',
            'dashboard-view',
        ]);

        



        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole('Admin');

        // Gérant
        $gerant = User::firstOrCreate(
            ['email' => 'gerant@stock.com'],
            [
                'name' => 'Gérant',
                'password' => Hash::make('gerant123'),
            ]
        );
        $gerant->assignRole('Gérant');

        // Vendeur
        $vendeur = User::firstOrCreate(
            ['email' => 'vendeur@stock.com'],
            [
                'name' => 'Vendeur',
                'password' => Hash::make('vendeur123'),
            ]
        );
        $vendeur->assignRole('Vendeur');

        

        

        $this->command->info('✅ Permissions et rôles créés avec succès!');
        $this->command->info('👤 Users créés:');
        $this->command->table(
            ['Email', 'Password', 'Rôle'],
            [
                ['admin@stock.com', 'admin123', 'Admin'],
                ['gerant@stock.com', 'gerant123', 'Gérant'],
                ['vendeur@stock.com', 'vendeur123', 'Vendeur'],
                
            ]
        );
    }
}