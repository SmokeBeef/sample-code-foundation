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
        return $this->hasMany(PenyewaanDetail::class, "penyewaan_detail_penyewaan_id", "penyewaan_id");
    }


    public static function create(array $data, array $dataDetail): ?array
    {
        $result = null;
        try {
            $payloadPenyewaan = [
                ...$data,
                "created_at" => now(),
                "updated_at" => now(),
            ];
            DB::beginTransaction();
            $query = DB::table("penyewaans")->insertGetId($payloadPenyewaan);
            $payloadDetail = array_map(function ($data) use ($query) {
                return [
                    ...$data,
                    "penyewaan_detail_penyewaan_id" => $query,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }, $dataDetail);

            DB::table("penyewaan_details")->insert($payloadDetail);
            DB::commit();

            $result = [...$payloadPenyewaan, "detailPenyewaan" => $payloadDetail];

        } catch (Exception $err) {
            DB::rollBack();
        }
        return $result;

    }

    public static function paginate(array $column, int $limit, int $offset, string $sortBy, string $sortOrder, string $search = ''): array
    {
        $query = DB::table('penyewaans')
            ->select($column)
            ->leftJoin("pelanggans", "penyewaan_pelanggan_id", "=", "pelanggan_id")
            ->leftJoin("penyewaan_details", "penyewaan_id", "=", "penyewaan_detail_penyewaan_id")
            ->where("penyewaan_sttspembayaran", "like", "%$search%")
            ->orWhere("penyewaan_sttskembali", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_nama", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_alamat", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_notelp", "like", "%$search%")
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder);

        $result = $query->get();

        return $result->toArray();

    }
    public static function countResult(string $search = ''): int
    {
        $query = DB::table('penyewaans')
            ->leftJoin("pelanggans", "penyewaan_pelanggan_id", "=", "pelanggan_id")
            ->leftJoin("penyewaan_details", "penyewaan_id", "=", "penyewaan_detail_penyewaan_id")
            ->where("penyewaan_sttspembayaran", "like", "%$search%")
            ->orWhere("penyewaan_sttskembali", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_nama", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_alamat", "like", "%$search%")
            ->orWhere("pelanggans.pelanggan_notelp", "like", "%$search%");



        $result = $query->count();

        return $result;

    }


    public static function getById($id): ?array
    {


        $query = DB::table("penyewaans")
            ->select([
                "penyewaans.*",
                "penyewaan_details.*",
                "alats.alat_nama",
                "alats.alat_hargaperhari"
            ])
            ->leftJoin("pelanggans", "penyewaan_pelanggan_id", "=", "pelanggan_id")
            ->leftJoin("penyewaan_details", "penyewaan_id", "=", "penyewaan_detail_penyewaan_id")
            ->leftJoin("alats", "penyewaan_details.penyewaan_detail_alat_id", "=", "alat_id")
            ->where("penyewaan_id", "=", $id);
        $result = $query->get();
        if (!$result) {
            return null;
        }
        return $result->toArray();

    }

    public static function updatePenyewaan(int|string $id, array $data): ?array
    {
        $query = DB::table("penyewaans")
            ->select()
            ->where("penyewaan_id", "=", $id);

        $query->update($data);

        return (array) $query->first();
    }
    public static function destroy($id): ?array
    {
        DB::beginTransaction();
        try {
            $query = DB::table("penyewaans")
                ->where("penyewaan_id", "=", $id);
            DB::table("penyewaan_details")
                ->where("penyewaan_detail_penyewaan_id", "=", $id)
                ->delete();

            $result = $query->get();
            if (!$result) {
                return null;
            }
            $query->delete();
            DB::commit();
        } catch (Exception $th) {
            DB::rollBack();
        }

        return $result->toArray();

    }
}
