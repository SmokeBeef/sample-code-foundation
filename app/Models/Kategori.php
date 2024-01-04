<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;
    protected $table = "kategoris";
    protected $primaryKey = "kategori_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'kategori_nama'
    ];

    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class, "alat_kategori_id", "kategori_id");
    }

    public static function store(array $data)
    {
        try {
            $isNameTaken = self::where("kategori_nama", $data["kategori_nama"])->first();
            if ($isNameTaken) {
                return null;
            }
            $result = self::create($data);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
}
