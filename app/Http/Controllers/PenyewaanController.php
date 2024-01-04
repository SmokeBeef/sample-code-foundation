<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenyewaanRequest;
use App\Services\PenyewaanService;
use Exception;
use Illuminate\Http\Request;

class PenyewaanController extends Controller
{

    public function store(PenyewaanRequest $req)
    {
        $penyewaanService = new PenyewaanService();
        try {
            $payload = $req->validated();
            $detail = $payload["detail"];

            unset($payload["detail"]);

            $result = $penyewaanService->store($payload, $detail);

            return $this->responseSuccess("success create new penyewaan", $penyewaanService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }

    public function findAll(Request $req)
    {
        $penyewaanService = new PenyewaanService();
        try {
            $join = [];
            if ($req->has("pelanggan")) {
                array_push($join, "pelanggan");
            }
            if ($req->has("detail")) {
                array_push($join, "penyewaanDetail");
            }
            $result = $penyewaanService->findAll($join);
            return $this->responseSuccess("success get all penyewaan", $penyewaanService->getData());
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("there is error in internal server");
        }
    }

    public function findById($id, Request $req)
    {
        $penyewaanService = new PenyewaanService();
        try {
            $join = [];
            if ($req->has("pelanggan")) {
                array_push($join, "pelanggan");
            }
            if ($req->has("detail")) {
                array_push($join, "penyewaanDetail");
            }

            $result = $penyewaanService->findById($id, $join);

            if (!$result) {
                return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
            }
            
            return $this->responseSuccess("success get penyewaan id " . $id, $penyewaanService->getData());
        } catch (Exception $err) {

            return $this->responseError("there is error in internal server");
        }
    }

    public function update(PenyewaanRequest $req, $id)
    {
        $penyewaanService = new PenyewaanService();
        try {
            $payload = $req->validated();
            $result = $penyewaanService->update($id, $payload);
            if (!$result) {
                return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
            }
            return $this->responseSuccess("success update penyewaan id " . $id, $penyewaanService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
    public function destroy($id)
    {
        $penyewaanService = new PenyewaanService();
        try {
            $result = $penyewaanService->destroy($id);
            if (!$result) {
                return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
            }

            return $this->responseSuccess("success delete penyewaan id " . $id, $penyewaanService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
}
