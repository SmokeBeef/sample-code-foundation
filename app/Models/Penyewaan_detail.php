<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penyewaan_detail extends Model
{
    use HasFactory;
    protected $table = "penyewaan_details";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "penyewaan_id",
        "alat_id",
        "jumlah",
        "subharga",
    ];

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class, "penyewaan_id", "id");
    }
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, "alat_id", "id");
    }
}
