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
        return $this->hasOne(Pelanggan_data::class, "pelanggan_data_pelanggan_id", "pelanggan_id");
    }
    public function penyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class, "penyewaan_pelanggan_id", "pelanggan_id");
    }

    public static function createOrException($pelanggan, $pelangganData): Exception|array
    {
        DB::beginTransaction();
        try {
            $isEmailTaken = self::where("pelanggan_email", $pelanggan["pelanggan_email"])->first();
            if ($isEmailTaken) {
                DB::rollBack();
                return new Exception("email already use", 409);
            }
            $result = self::create($pelanggan);
            $result->pelangganData()->create($pelangganData);
            DB::commit();
            return $result->toArray();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

    public static function deleteOrException($id): Exception|array
    {
        DB::beginTransaction();
        try {
            $pelanggan = self::with("pelangganData")->find($id);
            if (!$pelanggan) {
                DB::rollBack();
                return new Exception("id $id not found", 404);
            }
            $pelanggan->pelangganData()->delete();
            $pelanggan->delete();
            DB::commit();
            return $pelanggan->toArray();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

    public static function updateOrException($id, $data): Exception|array
    {
        try {
            $isEmailTaken = self
                ::where("pelanggan_email", $data["pelanggan_email"])
                ->where("pelanggan_id", "<>", $id)
                ->first();

            if ($isEmailTaken) {
                return new Exception("email already used", 409);
            }
            $pelanggan = self::find($id);
            if (!$pelanggan) {
                return new Exception("pelanggan_id $id not found", 404);
            }
            $pelanggan->update($data);
            return $pelanggan->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }
}
