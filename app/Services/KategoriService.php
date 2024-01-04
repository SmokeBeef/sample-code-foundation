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

        try {

            $result = Kategori::createOrException($data);

            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            Redis::del($this->redisKey);
            Redis::del($this->redisKeyFull);

            $this->setData($result);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAll()
    {
        try {
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
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function findByIdFull($id)
    {
        try {
            $result = Kategori::with("alat")->find($id);
            if (!$result) {
                $this->setError("kategori id $id not found", 404);
                return false;
            }

            $this->setData($result->toArray());
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAllAlat()
    {
        try {
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
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function update(int|string $id, array $data)
    {
        try {
            $result = Kategori::updateOrException($id, $data);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }
            Redis::del($this->redisKey);
            Redis::del($this->redisKeyFull);

            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function destroy(int|string $id)
    {
        try {
            $result = Kategori::deleteOrException($id);
            // dd($kategori);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }
            Redis::del($this->redisKey);
            Redis::del($this->redisKeyFull);

            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
}