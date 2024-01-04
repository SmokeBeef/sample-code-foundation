<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Penyewaan extends Model
{
    use HasFactory;
    protected $table = "penyewaans";
    protected $primaryKey = "penyewaan_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
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


    public static function createOrException(array $data, array $dataDetail): Exception|array
    {
        DB::beginTransaction();
        try {
            $result = self::create($data);
            $result->penyewaanDetail()->createMany($dataDetail);
            DB::commit();
            return $result->toArray();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

    public static function findAllJoin(string ...$relations): array
    {
        try {

            $query = Penyewaan::query();
            foreach ($relations as $relation) {
                $query->with($relation);
            }
            $result = $query->get()->toArray();

            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public static function findByIdJoin($id, string ...$relations): array
    {
        try {

            $query = Penyewaan::query();
            foreach ($relations as $relation) {
                $query->with($relation);
            }
            $result = $query->find($id);
            if (!$result) {
                return [];
            }
            $result = $query->get()->toArray();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public static function updateOrException($id, array $data): Exception|array
    {
        try {
            $result = self::find($id);
            if (!$result) {
                return new Exception("id $id Not Found", 404);
            }

            $result->update($data);

            return $result->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }
    public static function deleteOrException($id): Exception|array
    {
        DB::beginTransaction();
        try {
            $result = self::find($id);
            if (!$result) {
                DB::rollBack();
                return new Exception("id $id Not Found", 404);
            }

            $result->penyewaanDetail()->delete();
            $result->delete();
            DB::commit();
            return $result->toArray();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
}
