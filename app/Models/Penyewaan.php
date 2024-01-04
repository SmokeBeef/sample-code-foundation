<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyewaan extends Model
{
    use HasFactory;
    protected $table = "penyewaans";
    protected $primaryKey = "penyewaan_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable =[
        "penyewaan_pelanggan_id", 
        "penyewaan_tglsewa",
        "penyewaan_tglkembali",
        "penyewaan_sttspembayaran",
        "penyewaan_sttskembali",
        "penyewaan_totalharga",
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, "penyewaan_pelanggan_id", "pelanggan_id");
    }
    public function penyewaanDetail(): HasMany
    {
        return $this->hasMany(Penyewaan_detail::class, "penyewaan_detail_penyewaan_id", "penyewaan_id");
    }


}
