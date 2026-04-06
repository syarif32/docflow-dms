<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Menggunakan UUID untuk keamanan
            $table->string('title');
            
            // Relasi ke Kategori & User
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('restrict');
            
            // Metadata File
            $table->string('file_path'); // Lokasi di storage internal
            $table->string('original_filename'); // Nama file asli saat user upload
            $table->string('mime_type'); // application/pdf, dll
            $table->unsignedBigInteger('file_size'); // Ukuran dalam bytes
            $table->year('year'); // Tahun dokumen, disendirikan untuk optimasi index pencarian
            
            $table->timestamps();
            $table->softDeletes(); // Wajib untuk DMS
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};