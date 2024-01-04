<?php

namespace App\Services;

use App\Jobs\SendNotification;
use App\Models\Pelanggan;
use App\Models\Penyewaan;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenyewaanService
{
    use ServiceTrait;
    public function store(array $data, array $detailData): bool
    {
        try {
            $result = Penyewaan::createOrException($data, $detailData);

            // send Notification
            self::sendEmail($data["penyewaan_pelanggan_id"], $data["penyewaan_tglkembali"]);

            $this->setData($result);

            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function findAll(array $join = []): bool
    {
        try {
            $result = Penyewaan::findAllJoin(...$join);
            $this->setData($result);
            return true;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id, array $join = [])
    {
        try {
            $result = Penyewaan::findByIdJoin($id, ...$join);
            if (!$result) {
                $this->setError("penyewaan_id $id not found", 404);
                return false;
            }
            $this->setData($result);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function update($id, array $data)
    {
        try {
            $result = Penyewaan::updateOrException($id, $data);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            $this->setData($result);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function destroy($id)
    {
        try {
            $result = Penyewaan::deleteOrException($id);
            if ($result instanceof Exception) {
                $this->setError($result->getMessage(), $result->getCode());
                return false;
            }

            $this->setData($result);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    private static function sendEmail($id, $tglKembali)
    {
        $email = Pelanggan::find($id)->email;
        $notifyDate = new Carbon($tglKembali);
        $notifyDate->modify("-1 day");
        $diffTime = $notifyDate->diffInSeconds(Carbon::now());
        SendNotification::dispatch($email, $tglKembali)->delay(now()->addSeconds($diffTime));
    }

}