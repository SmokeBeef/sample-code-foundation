<?php
namespace App\Services;

use App\DTO\Alat\AlatQueryDTO;
use App\Models\Alat;
use App\Models\PenyewaanDetail;
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

    public static function getAlat(AlatQueryDTO $alatQueryDTO): Operation
    {
        $column = $alatQueryDTO->getField();
        $limit = $alatQueryDTO->getLimit();
        $offset = $alatQueryDTO->getOffset();
        $search = $alatQueryDTO->getSearch();
        $sort = $alatQueryDTO->getSortOrder();
        $sortBy = $alatQueryDTO->getSortBy();

        $result = Alat::paginate($column, $limit, $offset, $sortBy, $sort, $search);
        $totalData = Alat::countResult($search);

        $operation = new Operation();
        $operation->setIsSuccess(true);
        $operation->setResult($result);
        $operation->setTotal($totalData);
        $operation->setMessage("Success get Alat");
        $operation->setCode(200);
        $operation->setPage($alatQueryDTO->getPage());
        $operation->setPerPage($limit);


        return $operation;
    }

    public static function findById(AlatQueryDTO $alatQueryDTO): Operation
    {
        $operation = new Operation();

        $id = $alatQueryDTO->getId();

        $join = $alatQueryDTO->getJoin();
        $column = $alatQueryDTO->getColumn();

        if ($join["kategori"]) {
            array_push($column, ...$alatQueryDTO->getKategoriColumn());
        }

        $result = Alat::findJoin($id, $join, $column);
        if (!$result) {
            $operation->setOnError("alat id $id not found", 404);
            return $operation;
        }
        $operation->setOnSuccess("Success get alat id $id", 200, $result);
        return $operation;
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
        $checkRelation = PenyewaanDetail::where("penyewaan_detail_alat_id", "=", $id)->count();
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