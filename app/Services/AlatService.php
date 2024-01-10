<?php
namespace App\Services;

use App\Models\Alat;
use App\Models\Penyewaan_detail;

class AlatService extends Service
{
    public function store(array $data): bool
    {
        $result = Alat::create($data);

        if (!$result) {
            $this->setError("conflict", 409);
            return false;
        }

        $this->setData($result->toArray());
        return true;

    }

    public function findAll($page, $perPage, ?string $search): bool
    {
        $paginate = $this->calcTakeSkip($page, $perPage);

        $result = Alat::paginateFilter($paginate["take"], $paginate["skip"], $search);
        $totalData = Alat::countFilter($search);

        $this->setData($result);
        $this->setTotalData($totalData);
        return true;
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
        if($checkRelation > 0) {
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