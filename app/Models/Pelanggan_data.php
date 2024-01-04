<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan_data extends Model
{
    use HasFactory;
    protected $table = "pelanggan_datas";
    protected $primaryKey = "pelanggan_data_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "pelanggan_data_pelanggan_id",
        "pelanggan_data_jenis",
        "pelanggan_data_file",
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, "pelanggan_id", "pelanggan_data_id");
    }
}
