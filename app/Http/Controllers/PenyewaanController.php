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

            $operation = $penyewaanService->store($payload, $detail);

            return $this->responseSuccess("success create new penyewaan", $penyewaanService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }

    public function index(Request $req)
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
            $operation = $penyewaanService->findAll($join);
            return $this->responseSuccess("success get all penyewaan", $penyewaanService->getData());
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("there is error in internal server");
        }
    }

    public function show($id, Request $req)
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

            $operation = $penyewaanService->findById($id, $join);

            if (!$operation) {
                return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
            }
            
            return $this->responseSuccess("success get penyewaan id " . $id, $penyewaanService->getData());
        } catch (Exception $err) {

            return $this->responseError("there is error in internal server");
        }
    }
    public function showFull($id)
    {
        $penyewaanService = new PenyewaanService();
        try {

            $operation = $penyewaanService->findFull($id);

            if (!$operation) {
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
            $operation = $penyewaanService->update($id, $payload);
            if (!$operation) {
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
            $operation = $penyewaanService->destroy($id);
            if (!$operation) {
                return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
            }

            return $this->responseSuccess("success delete penyewaan id " . $id, $penyewaanService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
}
