<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Tambahkan di dalam class DocumentController

public function index(Request $request)
    {
        // Ambil kategori untuk ditampilkan di dropdown filter
        $categories = Category::all();

        // Mulai Query dengan Eager Loading
        $query = Document::with(['category', 'uploader']);

        // 1. Filter Nama Dokumen
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Filter Berdasarkan Tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Ambil data, urutkan dari terbaru. 
        // withQueryString() SANGAT PENTING agar saat pindah halaman (pagination), filter tidak reset!
        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('documents', 'categories'));
    }
    // 1. Menampilkan Halaman Upload
   public function create()
    {
    
        $categories = Category::whereNull('parent_id')
                              ->with('children.children')
                              ->get();
                              
        return view('documents.create', compact('categories'));
    }

    // 2. Proses Menyimpan Dokumen Baru
    public function store(Request $request)
    {
        // Validasi ketat untuk keamanan server
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            // Batasi tipe file dan ukuran maksimal (contoh: 20MB)
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg|max:20480', 
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        // GENERATE NAMA FILE ACAK (Anti-Timpa & Anti-Tebak)
        $hashedName = md5(time() . $originalName) . '.' . $file->getClientOriginalExtension();

        // SIMPAN KE FOLDER PRIVATE ('local' disk, bukan 'public')
        $path = $file->storeAs('documents', $hashedName, 'local');

        // Simpan metadata ke database
        Document::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'uploaded_by' => auth()->id(), // Ambil ID user yang sedang login
            'file_path' => $path,
            'original_filename' => $originalName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'year' => $request->year,
        ]);

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil diunggah dengan aman.');
    }

    // 3. Proses Download Aman (Hanya untuk yang punya hak akses)
    public function download(Document $document)
    {
        // Pengecekan keamanan ekstra: pastikan file fisik benar-benar ada
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File fisik tidak ditemukan di server.');
        }

        // Return response download, tapi dengan nama aslinya agar user tidak bingung
        return Storage::disk('local')->download(
            $document->file_path, 
            $document->original_filename
        );
    }
    public function destroy(Document $document)
    {
        // Keamanan: Hanya Admin atau Uploader asli yang boleh menghapus
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->uploaded_by) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus dokumen ini.');
        }

        // Karena kita pakai SoftDeletes, file fisiknya TIDAK kita hapus dari Storage.
        // Dokumen hanya ditandai terhapus di database.
        $document->delete();

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil dihapus.');
    }
}