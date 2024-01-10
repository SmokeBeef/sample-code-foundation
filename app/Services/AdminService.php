<?php
namespace App\Services;

use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Redis;

class AdminService extends Service
{
    protected $redisKey = "admin";

    public function store(array $data): bool
    {
        $result = Admin::create($data);

        if (!$result) {
            $this->setError("username already taken", 409);
            return false;
        }


        Redis::del($this->redisKey);
        $this->setData($result);
        return true;

    }

    public function findAll($page, $perPage, ?string $search): bool
    {
        $paginate = $this->calcTakeSkip($page, $perPage);
  
        $result = Admin::paginateFilter($paginate["take"], $paginate["skip"], $search);
        $totalAdmin = Admin::countFilter($search);

        $this->setTotalData($totalAdmin);
        $this->setData($result);

        return true;

    }

    public function destroy($id): bool
    {
        $result = Admin::destroy($id);

        if (!$result) {
            $this->setError("id $id not found", 404);
            return false;
        }

        Redis::del($this->redisKey);

        $this->setData(["id" => $id]);
        return true;

    }
}