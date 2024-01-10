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

    public static function paginateFilter(int $take, int $skip, ?string $search = null): array
    {
        $query = self::query();
        if (!!$search) {
            $query->where("alat_nama", "like", "%$search%")
                ->orWhere("alat_deskripsi", "like", "%$search%");
        }
        $query->skip($skip)->take($take);
        $result = $query->get();

        return $result->toArray();
    }

    public static function countFilter(?string $search): int
    {
        $query = self::query();
        if (!!$search) {
            $query->where("alat_nama", "like", "%$search%", "or")
                ->orWhere("alat_deskripsi", "like", "%$search%");
        }
        $result = $query->count();
        return $result;
    }

    public static function updateIfFound($id, array $data): ?array
    {
        $alat = self::find($id);
        if (!$alat) {
            return null;
        }
        $alat->update($data);
        return $alat->toArray();
    }

}
