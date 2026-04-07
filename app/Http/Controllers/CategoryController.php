<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar folder dan form tambah
    public function index()
    {
        // Ambil semua folder dengan susunan hierarki
        $categories = Category::whereNull('parent_id')->with('children.children')->get();
        
        // Ambil data flat (rata) untuk pilihan dropdown di form tambah
        $allCategories = Category::orderBy('name')->get();

        return view('categories.index', compact('categories', 'allCategories'));
    }

    // Menyimpan folder baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id' // Boleh kosong jika folder utama
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Folder kategori baru berhasil ditambahkan.');
    }

    // Menghapus folder
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->back()->with('success', 'Folder berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Ini Senior Tip: Karena di migration kita set onDelete('restrict'), 
            // MySQL akan menolak jika folder ini masih berisi dokumen. Kita tangkap errornya!
            return redirect()->back()->with('error', 'Gagal! Folder ini tidak bisa dihapus karena masih berisi dokumen atau sub-folder di dalamnya.');
        }
    }
}