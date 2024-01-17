<?php
namespace App\Services;

use App\DTO\Alat\AlatQueryDTO;
use App\Models\Alat;
use App\Models\Penyewaan_detail;
use App\Operation\Operation;

class AlatService extends Operation
{
    public function store(array $data): bool
    {
        $result = Alat::create($data);

        if (!$result) {
            $this->setMessageCode("conflict", 409);
            return false;
        }

        $this->setResult($result->toArray());
        $this->setMessageCode("Success create new Alat", 201);
        return true;

    }

    public static function findAll(AlatQueryDTO $alatDTO): Operation
    {

        $column = $alatDTO->getColumn();
        $limit = $alatDTO->getLimit();
        $offset = $alatDTO->getOffset();
        $search = $alatDTO->getSearch();
        $sort = $alatDTO->getSort();
        $sortBy = $alatDTO->getSortBy();

        $result = Alat::paginate($column, $limit, $offset, $sortBy, $sort, $search);
        $totalData = Alat::countResult($search);


        $operation = new Operation();
        $operation->setIsSuccess(true);
        $operation->setResult($result);
        $operation->setTotal($totalData);
        $operation->setMessageCode("Success get Alat",200);
        $operation->setPaginate($alatDTO->getPage(), $limit);

        return $operation;
    }

    public function findById($id): bool
    {

        $result = Alat::find($id);
        if (!$result) {
            $this->setError("alat id $id not found", 404);
            return false;
        }
        $this->setData($result->toArray());
        return true;
    }

    public function update($id, $data): bool
    {

        $result = Alat::updateIfFound($id, $data);
        if (!$result) {
            $this->setError("alat id $id not found", 404);
            return false;
        }

        $this->setData($result);
        return true;

    }

    public function destroy($id)
    {
        $checkRelation = Penyewaan_detail::where("penyewaan_detail_alat_id", "=", $id)->count();
        if ($checkRelation > 0) {
            $this->setError("alat that have been ordered cannot be deleted", 409);
            return false;
        }
        $result = Alat::destroy($id);
        if (!$result) {
            $this->setError("alat id $id not found", 404);
            return false;
        }

        $this->setData(["alat_id" => $id]);
        return true;

    }



}