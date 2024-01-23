<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = "pelanggans";
    protected $primaryKey = "pelanggan_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "pelanggan_nama",
        "pelanggan_alamat",
        "pelanggan_notelp",
        "pelanggan_email"
    ];
    public function pelangganData(): HasOne
    {
        return $this->hasOne(PelangganData::class, "pelanggan_data_pelanggan_id", "pelanggan_id");
    }
    public function penyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class, "penyewaan_pelanggan_id", "pelanggan_id");
    }

    public static function createNew($pelanggan, $pelangganData): array
    {
        DB::beginTransaction();
        $result = self::create($pelanggan);
        $result->pelangganData()->create($pelangganData);
        DB::commit();
        return $result->toArray();

    }

    public static function paginateFilter(int $take, int $skip, ?string $search = null): array
    {
        $query = self::query();
        if (!!$search) {
            $query->where("pelanggan_nama", "like", "%$search%")
                ->orWhere("pelanggan_alamat", "like", "%$search%")
                ->orWhere("pelanggan_notelp", "like", "%$search%")
                ->orWhere("pelanggan_emal", "like", "%$search%");
        }
        $query->skip($skip)->take($take);
        $result = $query->get();

        return $result->toArray();
    }

    public static function countFilter(?string $search): int
    {
        $query = self::query();
        if (!!$search) {
            $query->where("pelanggan_nama", "like", "%$search%")
                ->orWhere("pelanggan_alamat", "like", "%$search%")
                ->orWhere("pelanggan_notelp", "like", "%$search%")
                ->orWhere("pelanggan_emal", "like", "%$search%");
        }
        $result = $query->count();
        return $result;
    }

    public static function deleteIfExist($id): ?array
    {
        $pelanggan = self::with("pelangganData")->find($id);
        
        if (!$pelanggan) {
            return null;
        }
        try {
            DB::beginTransaction();
            $pelanggan->pelangganData()->delete();
            $pelanggan->delete();
            DB::commit();
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
        }

        return $pelanggan->toArray();
    }

    public static function updateIfExist($id, $data): ?array
    {
        $pelanggan = self::find($id);
        if (!$pelanggan) {
            return null;
        }
        $pelanggan->update($data);
        return $pelanggan->toArray();

    }
}
