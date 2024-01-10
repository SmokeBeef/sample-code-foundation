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


    public static function createNew(array $data, array $dataDetail): array
    {
        DB::beginTransaction();
        $result = self::create($data);
        $result->penyewaanDetail()->createMany($dataDetail);
        DB::commit();
        return $result->toArray();

    }

    public static function findAllJoin(string ...$relations): array
    {


        $query = Penyewaan::query();
        foreach ($relations as $relation) {
            $query->with($relation);
        }
        $result = $query->get()->toArray();

        return $result;

    }
    public static function findByIdJoin($id, string ...$relations): ?array
    {


        $query = Penyewaan::query();
        foreach ($relations as $relation) {
            $query->with($relation);
        }
        $result = $query->find($id);
        if (!$result) {
            return null;
        }
        return $result->toArray();

    }

    public static function updateIfExist($id, array $data): ?array
    {
        $result = self::find($id);
        if (!$result) {
            return null;
        }

        $result->update($data);

        return $result->toArray();
    }
    public static function deleteIfExist($id): ?array
    {
        $result = self::find($id);
        if (!$result) {
            return null;
        }
        try {
            DB::beginTransaction();
            $result->penyewaanDetail()->delete();
            $result->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return $result->toArray();

    }
}
