<?php

namespace App\Services;

use App\DTO\Penyewaan\PenyewaanMutationDTO;
use App\DTO\Penyewaan\PenyewaanQueryDTO;
use App\Jobs\SendNotification;
use App\Models\Pelanggan;
use App\Models\Penyewaan;

use App\Operation\Operation;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenyewaanService extends Operation
{
    public static function store(PenyewaanMutationDTO $penyewaanMutationDTO): Operation
    {
        $operation = new Operation();

        $data = $penyewaanMutationDTO->getData();
        $detailData = $penyewaanMutationDTO->getDataDetailPenyewaan();

        $result = Penyewaan::create($data, $detailData);

        if (!$result) {
            $operation->setIsSuccess(false);
            $operation->setCode(409);
            $operation->setMessage("Failed create new Penyewaan, conflict when create");
            return $operation;
        }

        // send Notification
        self::sendEmail($data["penyewaan_pelanggan_id"], $data["penyewaan_tglkembali"]);

        $operation->setIsSuccess(true);
        $operation->setCode(201);
        $operation->setMessage("Success create new penyewaan");
        $operation->setResult($result);
        return $operation;

    }
    public static function getPenyewaan(PenyewaanQueryDTO $penyewaanQueryDTO): Operation
    {
        $column = $penyewaanQueryDTO->getField();
        $limit = $penyewaanQueryDTO->getLimit();
        $offset = $penyewaanQueryDTO->getOffset();
        $search = $penyewaanQueryDTO->getSearch();
        $sortOrder = $penyewaanQueryDTO->getSortOrder();
        $sortBy = $penyewaanQueryDTO->getSortBy();

        $result = Penyewaan::paginate($column, $limit, $offset, $sortBy, $sortOrder, $search);
        $totalData = Penyewaan::countResult($search);

        $operation = new Operation();
        $operation->setIsSuccess(true);
        $operation->setResult($result);
        $operation->setTotal($totalData);
        $operation->setMessage("Success get Alat");
        $operation->setCode(200);
        $operation->setPage($penyewaanQueryDTO->getPage());
        $operation->setPerPage($limit);

        return $operation;
    }

    public static function getById($id): Operation
    {
        $operation = new Operation();

        if (!is_numeric($id)) {
            $operation->setIsSuccess(false);
            $operation->setMessage("Failed get penyewaan by id, params $id is not numeric");
            $operation->setCode(400);
            return $operation;
        }

        $result = Penyewaan::getById($id);
        if (!$result) {
            $operation->setIsSuccess(false);
            $operation->setMessage("Failed get penyewaan_id $id not found");
            $operation->setCode(404);
            return $operation;
        }
        $operation->setIsSuccess(true);
        $operation->setMessage("Success get penyewaan_id $id");
        $operation->setCode(200);
        $operation->setResult($result);
        return $operation;

    }


    public static function update(PenyewaanMutationDTO $penyewaanMutationDTO): Operation
    {
        $operation = new Operation();

        $id = $penyewaanMutationDTO->getId();
        $data = $penyewaanMutationDTO->getData();

        $checkData = DB::table("penyewaans")->where("penyewaan_id", "=", $id)->first();

        if (!$checkData) {
            $operation->setIsSuccess(false);
            $operation->setMessage("Failed updating penyewaan, penyewaan_id $id Not found");
            $operation->setCode(404);
            return $operation;
        }

        $result = Penyewaan::updatePenyewaan($id, $data);

        if (!$result) {
            $operation->setIsSuccess(true);
            $operation->setMessage("Data is not Modified");
            $operation->setCode(304);
            return $operation;
        }

        $operation->setIsSuccess(true);
        $operation->setMessage("Success updating penyewaan");
        $operation->setCode(201);
        $operation->setResult($result);
        return $operation;

    }

    public static function destroy($id): Operation
    {
        $operation = new Operation();

        $result = Penyewaan::destroy($id);
        if (!$result) {
            $operation->setIsSuccess(false);
            $operation->setMessage("Failed delete penyewaan, penyewaan_id $id not found");
            $operation->setCode(404);
            return $operation;
        }

        $operation->setIsSuccess(true);
        $operation->setMessage("Success delete penyewaan_id $id");
        $operation->setCode(200);
        $operation->setResult($result);
        return $operation;

    }

    /////////////////////
    // private function 
    //
    private static function sendEmail($idPelanggan, $tglKembali)
    {
        $email = Pelanggan::find($idPelanggan)->email;
        $notifyDate = new Carbon($tglKembali);
        $notifyDate->modify("-1 day");
        $diffTime = $notifyDate->diffInSeconds(Carbon::now());
        SendNotification::dispatch($email, $tglKembali)->delay(now()->addSeconds($diffTime));
    }

}