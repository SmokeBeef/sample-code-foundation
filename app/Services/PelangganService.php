<?php
namespace App\Services;

use App\Models\Pelanggan;
use App\Models\Penyewaan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganService extends Service
{


    public function store(array $data, array $pelangganData): bool
    {
        // get file and set filename
        $file = $pelangganData["pelanggan_data_file"];
        $filename = self::getFileName($file);

        // set path to save image 
        $path = "foto-pelanggan/" . $filename;

        // set path to save in database
        $pelangganData["pelanggan_data_file"] = "storage/" . $path;

        $result = Pelanggan::createNew($data, $pelangganData);
        if (!$result) {
            $this->setError("Conflict when Create new pelanggan", 409);
            return false;
        }

        // save image
        $saveImage = self::saveImage($path, $file);
        if (!$saveImage) {
            $this->setError("fail to save image", 500);
            return false;
        }

        $this->setData($result);
        return true;

    }
    public function findAll($page, $perPage, ?string $search): bool
    {
        $paginate = $this->calcTakeSkip($page, $perPage);

        $result = Pelanggan::paginateFilter($paginate["take"], $paginate["skip"], $search);
        $totalData = Pelanggan::countFilter($search);

        $this->setTotalData($totalData);
        $this->setData($result);
        return true;

    }

    public function findById($id): bool
    {
        $result = Pelanggan::with("pelangganData")->find($id);
        if (!$result) {
            $this->setError("pelanggan id $id not found", 404);
            return false;
        }
        $result->pelangganData->pelanggan_data_file = self::setUrlImg($result->pelangganData->pelanggan_data_file);
        $this->setData($result->toArray());
        return true;

    }
    public function update($id, array $data): bool
    {
        $result = Pelanggan::updateIfExist($id, $data);
        if (!$result) {
            $this->setError("pelanggan id $id not found", 404);
            return false;
        }
        $this->setData($result);
        return true;

    }
    public function destroy($id): bool
    {
        try {

            $checkRelation = Penyewaan::where("penyewaan_pelanggan_id", "=", $id)->count();
            if($checkRelation > 0){
                $this->setError("pelanggan that have been ordered cannot be deleted", 409);
                return false;
            }

            $result = Pelanggan::deleteIfExist($id);

            if (!$result) {
                $this->setError("pelanggan id $id not found", 404);
                return false;
            }


            // deleting file
            $filePath = $result["pelanggan_data"]["pelanggan_data_file"];
            // make "storage/" disappear from pelanggan_data_file
            $path = self::pathDelete($filePath);

            if (Storage::disk("public")->exists($path)) {
                Storage::disk("public")->delete($path);
            }
            
            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    ////////////////////////
    // private funtction
    //

    private static function setUrlImg($path): string
    {
        return env("APP_URL", "http://localhost:8000") . "/" . $path;
    }

    private static function saveImage($path, $file): bool
    {
        try {
            Storage::disk("public")->put($path, file_get_contents($file));
            return true;
        } catch (Exception $err) {
            return false;
        }
    }

    private static function getFileName($file): string
    {
        $filename = Str::uuid() . "-" . $file->getClientOriginalName();
        return $filename;
    }

    private static function pathDelete($pelangganDataFile)
    {
        $words = explode("/", $pelangganDataFile);

        // Remove the first 1 words
        $remainingWords = array_slice($words, 1);

        // Join the remaining words back into a string
        $newString = implode("/", $remainingWords);
        return $newString;
    }
}