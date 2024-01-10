<?php

namespace App\Services;

use App\Models\Alat;
use App\Models\Kategori;
use Exception;
use Illuminate\Support\Facades\Redis;

class KategoriService
{
    use ServiceTrait;
    protected $redisKey = "kategori";

    public function create(array $data)
    {
        $result = Kategori::create($data);

        if (!$result) {
            $this->setError("kategori_nama already use", "409");
            return false;
        }

        Redis::del($this->redisKey);

        $this->setData($result->toArray());
        return $result;

    }

    public function findAll()
    {

        $result = null;
        $fromRedis = self::getFromRedis($this->redisKey);
        if ($fromRedis) {
            $result = $fromRedis;
        } else {
            $result = kategori::all();
            Redis::set($this->redisKey, json_encode($result));
            $result = $result->toArray();
        }

        $this->setData($result);
        return $result;

    }
    public function findByIdFull($id)
    {

        $result = Kategori::with("alat")->find($id);
        if (!$result) {
            $this->setError("kategori id $id not found", 404);
            return false;
        }

        $this->setData($result->toArray());
        return $result;

    }


    public function update($id, array $data)
    {

        $result = Kategori::updateIfFound($id, $data);
        if (!$result) {
            $this->setError("id $id not found", 404);
            return false;
        }
        Redis::del($this->redisKey);

        $this->setData($result);
        return true;

    }
    public function destroy(int|string $id)
    {
        $checkHasRelation = Alat::where("alat_kategori_id", "=", $id)->count();
        if($checkHasRelation > 0){
            $this->setError("this kategori has relation to alat", 409);
            return false;
        }

        $result = Kategori::destroy($id);
        
        if (!$result) {
            $this->setError("id $id not found", 404);
            return false;
        }
        Redis::del($this->redisKey);

        $this->setData(["id" => $id]);
        return true;
    }

    ////////////////////
    // local function
    //
    private static function getFromRedis(string $key): ?array
    {
        if(Redis::exists($key)){
            return json_decode(Redis::get($key));
        }
        return null;
    }
}