<?php

namespace App\Services;

use App\Models\Kategori;
use Exception;
use Illuminate\Support\Facades\Redis;

class KategoriService
{
    use ServiceTrait;
    protected $redisKey = "kategori";
    protected $redisKeyFull = "kategoriJoinAlat";

    public function store(array $data)
    {



        $result = Kategori::create($data);

        if (!$result) {
            $this->setError($result->getMessage(), $result->getCode());
            return false;
        }

        Redis::del($this->redisKey);
        Redis::del($this->redisKeyFull);

        $this->setData($result);
        return $result;

    }

    public function findAll()
    {

        $result = null;
        if (Redis::exists($this->redisKey)) {
            $result = json_decode(Redis::get($this->redisKey));
        } else {
            $result = kategori::all();
            Redis::set($this->redisKey, $result);
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

    public function findAllAlat()
    {

        $result = null;
        if (Redis::exists($this->redisKeyFull)) {
            $result = json_decode(Redis::get($this->redisKeyFull));
        } else {
            $result = Kategori::with("alat")->get();
            Redis::set($this->redisKeyFull, $result);
            $result = $result->toArray();
        }

        $this->setData($result);
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
        Redis::del($this->redisKeyFull);

        $this->setData($result);
        return true;

    }
    public function destroy(int|string $id)
    {
        $result = Kategori::destroy($id);
        
        if (!$result) {
            $this->setError("id $id not found", 404);
            return false;
        }
        Redis::del($this->redisKey);
        Redis::del($this->redisKeyFull);

        $this->setData(["id" => $id]);
        return true;
    }
}