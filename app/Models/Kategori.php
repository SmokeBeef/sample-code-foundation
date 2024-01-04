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

    public static function createOrException(array $data): Exception|array
    {
        try {
            $isNameTaken = self::where("kategori_nama", $data["kategori_nama"])->first();
            if ($isNameTaken) {
                return new Exception("kategori nama already used", 409);
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
            $isNameTaken = self
                ::where("kategori_nama", $data["kategori_nama"])
                ->where("kategori_id", "<>", $id)
                ->first();
            if ($isNameTaken) {
                return new Exception("kategori nama already used", 409);
            }
            $kategori = self::find($id);
            if (!$kategori) {
                return new Exception("Kategori id $id not found", 404);
            }
            $kategori->update($data);
            return $kategori->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }

    public static function deleteOrException($id): Exception|array
    {
        DB::beginTransaction();
        try {
            $kategori = self::find($id);
            if (!$kategori) {
                DB::rollBack();
                return new Exception("kategori id $id not found", 404);
            }
            $kategori->alat()->delete();
            $kategori->delete();
            DB::commit();

            return $kategori->toArray();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
}
