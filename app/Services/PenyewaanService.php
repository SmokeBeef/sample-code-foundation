<?php

namespace App\Services;

use App\Jobs\SendNotification;
use App\Models\Pelanggan;
use App\Models\Penyewaan;

use DateTime;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenyewaanService
{
    protected $penyewaan;
    public function __construct(Penyewaan $penyewaan)
    {
        $this->penyewaan = $penyewaan;
    }

    public function store(array $data, array $detailData)
    {
        DB::beginTransaction();
        try {
            $result = $this->penyewaan->create($data);
            $result->penyewaanDetail()->createMany($detailData);

            DB::commit();
            // send Notification
            $email = Pelanggan::find($data["pelanggan_id"])->email;
            // dd($email);
            $notifyDate = new Carbon($data["tglkembali"]);
            $notifyDate->modify("-1 day");
            $diffTime = $notifyDate->diffInSeconds(Carbon::now());
            SendNotification::dispatch($email, $data["tglkembali"])->delay(now()->addSeconds($diffTime));

            return $result;
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
    public function findAll()
    {
        try {
            $result = $this->penyewaan->all();
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function findById($id)
    {
        try {
            $result = $this->penyewaan->with("penyewaanDetail")->find($id);
            if (!$result) {
                return false;
            }
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }
    public function update($id, array $data)
    {
        try {
            $result = $this->penyewaan->find($id);
            if (!$result) {
                return false;
            }
            $result->update($data);
            return $result;
        } catch (Exception $err) {
            throw $err;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->penyewaan->find($id);
            if (!$result) {
                DB::rollBack();
                return false;
            }

            $result->penyewaanDetail()->delete();
            $result->delete();
            DB::commit();
            return $result;
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

}