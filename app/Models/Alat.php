<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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

    public static function paginate(array $column, int $limit, int $offset, string $columnSort, string $sortDirection, ?string $search = null): array
    {
        $query = DB::table('alats')
            ->select($column);
        if ($search) {
            $query
                ->where("alat_nama", "like", "%$search%")
                ->orWhere("alat_deskripsi", "like", "%$search%")
                ->orWhere("alat_hargaperhari", "like", "%$search%")
                ->orWhere("alat_stok", "like", "%$search%");
        }

        $query->limit($limit)
            ->offset($offset)
            ->orderBy($columnSort, $sortDirection);

        $result = $query->get();

        return $result->toArray();
    }

    public static function countResult(?string $search): int
    {
        $query = DB::table('alats')
            ->where("alat_nama", "like", "%$search%")
            ->orWhere("alat_deskripsi", "like", "%$search%")
            ->orWhere("alat_hargaperhari", "like", "%$search%")
            ->orWhere("alat_stok", "like", "%$search%");

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
