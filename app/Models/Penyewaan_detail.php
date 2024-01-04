<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penyewaan_detail extends Model
{
    use HasFactory;
    protected $table = "penyewaan_details";
    protected $primaryKey = "penyewaan_detail_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "penyewaan_detail_penyewaan_id",
        "penyewaan_detail_alat_id",
        "penyewaan_detail_jumlah",
        "penyewaan_detail_subharga",
    ];

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class, "penyewaan_detail_penyewaan_id", "penyewaan_id");
    }
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, "penyewaan_detail_alat_id", "alat_id");
    }
}
