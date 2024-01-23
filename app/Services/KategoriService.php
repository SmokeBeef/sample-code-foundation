<?php

namespace App\Services;

use App\DTO\Kategori\KategoriQueryDTO;
use App\Models\Alat;
use App\Models\Kategori;
use App\Operation\Operation;
use Exception;
use Illuminate\Support\Facades\Redis;

class KategoriService
{
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

    public static function getAll(KategoriQueryDTO $kategoriQueryDTO): Operation
    {

        $column = $kategoriQueryDTO->getField();
        $limit = $kategoriQueryDTO->getLimit();
        $offset = $kategoriQueryDTO->getOffset();
        $search = $kategoriQueryDTO->getSearch();
        $sort = $kategoriQueryDTO->getSortOrder();
        $sortBy = $kategoriQueryDTO->getSortBy();

        $page = $kategoriQueryDTO->getPage();

        $result = Kategori::paginate($column, $limit, $offset, $sort, $sortBy, $search);
        $total = Kategori::countResult($search);

        $operation = Operation::onPaginate("Success get kategori", $result, $total, $page, $limit);

        return $operation;

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
        if ($checkHasRelation > 0) {
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
        if (Redis::exists($key)) {
            return json_decode(Redis::get($key));
        }
        return null;
    }
}