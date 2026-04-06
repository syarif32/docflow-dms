<?php
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// Biarkan route bawaan Breeze (seperti /)
Route::get('/', function () {
    return view('welcome');
});
// Grup Route Profil bawaan Breeze (INI YANG HILANG TADI)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group route yang WAJIB LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route Dokumen
    Route::get('/documents/upload', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    
    // Route Download (Penting: Gunakan UUID dokumen di URL)
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    // Cari bagian ini dan ubah:
Route::get('/dashboard', [DocumentController::class, 'index'])->name('dashboard');


});

require __DIR__.'/auth.php';