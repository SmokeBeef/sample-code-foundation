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
    protected $kategori;

    public function __construct(Kategori $kategori)
    {
        $this->kategori = $kategori;
    }

    public function store(array $data)
    {
        try {

            $result = Kategori::store($data);

            if ($result) {
                $this->setError("admin username already taken", 409);
                return false;
            }

            $result = $this->kategori->create($data);
            Redis::del($this->redisKey);
            Redis::del($this->redisKeyFull);
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
                $result = $this->kategori->all();
                Redis::set($this->redisKey, $result);
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function findByIdFull($id)
    {
        try {
            $result = $this->kategori->with("alat")->find($id);
            if (!$result) {
                return false;
            }
            $result->with("alat")->get();
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
                $result = $this->kategori->with("alat")->get();
                Redis::set($this->redisKeyFull, $result);
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function update(int|string $id, array $data)
    {
        try {
            $kategori = $this->kategori->find($id);
            if (!$kategori) {
                return false;
            }
            $kategori->update($data);
            Redis::del($this->redisKey);
            Redis::del($this->redisKeyFull);
            return $kategori;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function destroy(int|string $id)
    {
        try {
            $kategori = $this->kategori->find($id);
            // dd($kategori);
            if (!$kategori) {
                return false;
            }
            $kategori->alat()->delete();
            $kategori->delete();
            Redis::del($this->redisKey);

            Redis::del($this->redisKeyFull);
            return $kategori;
        } catch (Exception $err) {
            dd($err);
            throw $err;
        }
    }
}