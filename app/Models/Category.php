<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    // Otomatis membuat slug saat kategori baru dibuat (Mencegah error URL)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            // Menghasilkan slug, misalnya: "Site/Device" -> "site-device"
            $category->slug = Str::slug($category->name) . '-' . uniqid(); 
        });
    }

    // Relasi ke Induk (Kategori di atasnya)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi ke Anak (Sub-kategori)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relasi ke Dokumen
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}