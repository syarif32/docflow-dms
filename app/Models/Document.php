<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Wajib dipanggil

class Document extends Model
{
    use SoftDeletes, HasUuids; // Aktifkan UUID dan SoftDeletes

    protected $fillable = [
        'title',
        'category_id',
        'uploaded_by',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'year'
    ];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke User pengupload
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}