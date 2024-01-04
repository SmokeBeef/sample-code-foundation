<?php
namespace App\Services;

use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Redis;

class AdminService
{
    use ServiceTrait;
    protected $redisKey = "admin";

    public function store(array $data): bool
    {
        try {

            $result = Admin::createOrNull($data);

            if (!$result) {
                $this->setError("username already taken", 409);
                return false;
            }

            Redis::del($this->redisKey);

            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findAll(): bool
    {
        try {
            $result = null;
            if (Redis::exists($this->redisKey)) {
                $result = Redis::get($this->redisKey);
            } else {
                $result = Admin::all()->toArray();
                Redis::set($this->redisKey, $result);
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
            $result = Admin::deleteOrNull($id);

            if (!$result) {
                $this->setError("id $id not found", 404);
                return false;
            }
            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
}