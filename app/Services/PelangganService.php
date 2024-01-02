<?php
namespace App\Services;

use App\Models\Pelanggan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganService
{
    protected $pelanggan;
    public function __construct(Pelanggan $pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    public function store(array $data, $photo)
    {
        DB::beginTransaction();
        try {
            $isEmailTaken = $this->pelanggan->where("email", $data["email"])->first();
            if ($isEmailTaken) {
                DB::rollBack();
                return false;
            }
            $file = $photo["file"];
            $filename = Str::uuid() . "-" . $file->getClientOriginalName();

            $path = "foto-pelanggan/" . $filename;
            Storage::disk("public")->put($path, file_get_contents($file));
            $photo["file"] = "storage/" . $path;
            
            $result = $this->pelanggan->create($data);
            $result->pelangganData()->create($photo);
            DB::commit();
            return $result;
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
    public function findAll()
    {
        try {
            $result = $this->pelanggan->all();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id)
    {
        try {
            $result = $this->pelanggan->with("pelangganData")->find($id);
            if (!$result) {
                return false;
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function update($id, array $data)
    {
        try {
            $result = $this->pelanggan->find($id);
            if (!$result) {
                return false;
            }
            $result->update($data);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function destroy($id)
    {
        try {
            $result = $this->pelanggan->find($id);
            if (!$result) {
                return false;
            }
            $result->pelangganData()->delete();
            $result->delete();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
}