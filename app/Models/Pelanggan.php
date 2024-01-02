<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = "pelanggans";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "nama",
        "alamat",
        "notelp",
        "email"
    ];
    public function pelangganData(): HasOne
    {
        return $this->hasOne(Pelanggan_data::class, "pelanggan_id", "id");
    }
    public function penyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class, "pelanggan_id", "id");
    }
}
