<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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


    public static function updateIfFound($id, array $data): ?array
    {
            $kategori = self::find($id);
            if (!$kategori) {
                return null;
            }
            $kategori->update($data);
            return $kategori->toArray();

    }

    // public static function destroy($id): array
    // {
    //     DB::beginTransaction();
    //     try {
    //         DB::commit();

    //         return 
    //     } catch (Exception $err) {
    //         DB::rollBack();
    //         throw $err;
    //     }
    // }
}
