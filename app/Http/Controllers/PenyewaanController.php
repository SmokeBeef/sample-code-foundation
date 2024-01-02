<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenyewaanRequest;
use App\Services\PenyewaanService;
use Exception;
use Illuminate\Http\Request;

class PenyewaanController extends Controller
{
    protected $penyewaanService;
    public function __construct(PenyewaanService $penyewaanService)
    {
        $this->penyewaanService = $penyewaanService;
    }

    public function store(PenyewaanRequest $req)
    {
        try {
            $payload = $req->validated();
            $detail = $payload["detail"];

            unset($payload["detail"]);
            // dd($payload);

            $result = $this->penyewaanService->store($payload, $detail);

            return $this->responseSuccess("success create new penyewaan", $result, 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }

    public function findAll()
    {
        try {
            $result = $this->penyewaanService->findAll();
            return $this->responseSuccess("success get all penyewaan", $result);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }

    public function findById($id)
    {
        try {
            $result = $this->penyewaanService->findById($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success get penyewaan id " . $id, $result);
        } catch (Exception $err) {

            return $this->responseError("there is error in internal server");
        }
    }

    public function update(PenyewaanRequest $req, $id)
    {
        try {
            $payload = $req->validated();
            $result = $this->penyewaanService->update($id, $payload);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }

            return $this->responseSuccess("success update penyewaan id " . $id, $result, 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
    public function destroy($id)
    {
        try {
            $result = $this->penyewaanService->destroy($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }

            return $this->responseSuccess("success delete penyewaan id " . $id, $result, 201);
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
}
