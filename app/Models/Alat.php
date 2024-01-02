<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    use HasFactory;
    protected $table = "alats";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable =[
        "kategori_id",
        "nama",
        "deskripsi",   
        "hargaperhari",
        "stok",
    ];
    public function penyewaanDetail() : HasMany {
        return $this->hasMany(Penyewaan_detail::class, "alat_id", "id");
    }

    public function kategori() : BelongsTo {
        return $this->belongsTo(Kategori::class, "kategori_id", "id");
    }
}
