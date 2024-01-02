<?php
namespace App\Services;

use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Redis;

class AdminService
{
    protected $redisKey = "admin";
    protected $adminModel;
    public function __construct(Admin $admin)
    {
        $this->adminModel = $admin;
    }
    public function store(array $data): bool|Admin
    {
        try {

            $checkUsername = $this->adminModel->where("username", $data["username"])->first();

            if ($checkUsername) {
                return false;
            }

            $result = $this->adminModel->create($data);
            Redis::del($this->redisKey);
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
                $result = Redis::get($this->redisKey);
            } else {
                $result = $this->adminModel->all();
                Redis::set($this->redisKey, $result);
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->adminModel->find($id);
            if(!$result){
                return false;
            }
            $result->delete();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }   
    }
}