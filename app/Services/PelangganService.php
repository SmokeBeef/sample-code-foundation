<?php
namespace App\Services;

use App\Models\Pelanggan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganService
{
    use ServiceTrait;

    protected $pathImg = "foto-pelanggan/";

    public function store(array $data, array $pelangganData): bool
    {
        try {
            // get file and set filename
            $file = $pelangganData["pelanggan_data_file"];
            $filename = self::getFileName($file);

            // set path to save image 
            $path = "foto-pelanggan/" . $filename;

            // set path to save in database
            $pelangganData["pelanggan_data_file"] = "storage/" . $path;

            $result = Pelanggan::createCheckEmail($data, $pelangganData);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
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
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function findAll(): bool
    {
        try {
            $result = Pelanggan::all()->toArray();
            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id): bool
    {
        try {
            $result = Pelanggan::with("pelangganData")->find($id);
            if (!$result) {
                $this->setError("pelanggan id $id not found", 404);
                return false;
            }
            $result->pelangganData->pelanggan_data_file = env("APP_URL", "http://localhost:8000") . "/" . $result->pelangganData->pelanggan_data_file;
            $this->setData($result->toArray());
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function update($id, array $data): bool
    {
        try {
            $result = Pelanggan::updateOrException($id, $data);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }
            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function destroy($id): bool
    {
        try {
            $result = Pelanggan::deleteOrException($id);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }
            $filePath = $result["pelanggan_data"]["pelanggan_data_file"];
            // make storage/ disappear from pelanggan_data_file
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