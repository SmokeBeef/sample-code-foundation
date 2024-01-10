<?php

namespace App\Services;

use App\Jobs\SendNotification;
use App\Models\Pelanggan;
use App\Models\Penyewaan;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenyewaanService extends Service
{
    public function store(array $data, array $detailData): bool
    {
        $result = Penyewaan::createNew($data, $detailData);

        // send Notification
        self::sendEmail($data["penyewaan_pelanggan_id"], $data["penyewaan_tglkembali"]);

        $this->setData($result);

        return true;

    }
    public function findAll(array $join = []): bool
    {
        $result = Penyewaan::findAllJoin(...$join);
        $this->setData($result);
        return true;
    }

    public function findById($id, array $join = [])
    {
        $result = Penyewaan::findByIdJoin($id, ...$join);
        if (!$result) {
            $this->setError("penyewaan_id $id not found", 404);
            return false;
        }
        $this->setData($result);
        return true;

    }

    public function findFull($id): bool
    {
        $result = Penyewaan::with("pelanggan")->with("penyewaanDetail")->with("penyewaanDetail.alat")->find($id);
        if (!$result) {
            $this->setError("penyewaan_id $id not found", 404);
            return false;
        }

        $this->setData($result->toArray());
        return true;
    }
    public function update($id, array $data)
    {

        $result = Penyewaan::updateIfExist($id, $data);
        if (!$result) {
            $this->setError("penyewaan id $id not found", 404);
            return false;
        }

        $this->setData($result);
        return $result;

    }

    public function destroy($id)
    {

        $result = Penyewaan::deleteIfExist($id);
        if (!$result) {
            $this->setError("penyewaan id $id not found", 404);
            return false;
        }

        $this->setData($result);
        return $result;

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