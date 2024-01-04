<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    use HasFactory;
    protected $table = "alats";
    protected $primaryKey = "alat_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "alat_kategori_id",
        "alat_nama",
        "alat_deskripsi",
        "alat_hargaperhari",
        "alat_stok",
    ];
    public function penyewaanDetail(): HasMany
    {
        return $this->hasMany(Penyewaan_detail::class, "penyewaan_detail_alat_id", "alat_id");
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, "alat_kategori_id", "kategori_id");
    }

    public static function createOrException(array $data): Exception|array
    {
        try {
            $isNameTaken = self::isNameUsed($data["alat_nama"]);
            if ($isNameTaken) {
                return new Exception("alat nama already used", 409);
            }
            $result = self::create($data)->toArray();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public static function updateOrException($id, array $data): Exception|array
    {
        try {
            $isNameTaken = self::isNameUsed($data, $id);
            if ($isNameTaken) {
                return new Exception("alat nama already used", 409);
            }
            $alat = self::find($id);
            if (!$alat) {
                return new Exception("alat id $id not found", 404);
            }
            $alat->update($data);
            return $alat->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }

    public static function deleteOrException($id): Exception|array
    {

        try {
            $alat = self::find($id);
            if (!$alat) {

                return new Exception("alat id $id not found", 404);
            }
            $alat->delete();

            return $alat->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }


    public static function isNameUsed($alatNama, $id = null): bool
    {
        try {
            if (!$id) {
                $result = self::where("alat_nama", $alatNama)->first();
                if ($result)
                    return true;


            } else {
                $result = self::where("alat_nama", $alatNama)->where("alat_id", "<>", $id)->first();
                if ($result)
                    return true;
            }
            return false;
        } catch (Exception $err) {
            throw $err;
        }
    }
}
