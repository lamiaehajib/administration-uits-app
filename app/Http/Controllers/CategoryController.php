<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Dans votre contrôleur, par exemple CategoryController.php
public function index(Request $request)
{
    $search = $request->input('search'); // Récupérer le terme de recherche

    // Rechercher les catégories par nom et paginer les résultats
    $categories = Category::when($search, function ($query) use ($search) {
        return $query->where('nom', 'like', '%' . $search . '%');
    })->paginate(10); // Ajustez le nombre selon vos besoins

    return view('categories.index', compact('categories'));
}


    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Catégorie ajoutée.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
    }
}
