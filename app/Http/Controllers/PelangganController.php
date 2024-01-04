<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelangganRequest;
use App\Services\PelangganService;
use Exception;
use Illuminate\Http\Request;

class PelangganController extends Controller
{

    public function store(PelangganRequest $req)
    {
        $pelangganService = new PelangganService();
        try {
            $payload = $req->validated();
            $pelangganData = [
                "pelanggan_data_jenis" => $payload["pelanggan_data_jenis"],
                "pelanggan_data_file" => $payload["pelanggan_data_photo"]
            ];
            unset($payload["pelanggan_data_jenis"], $payload["pelanggan_data_photo"]);

            $result = $pelangganService->store($payload, $pelangganData);

            if (!$result) {
                return $this->responseError($pelangganService->errorMessage, $pelangganService->errorCode);
            }
            return $this->responseSuccess("success add new pelanggan", $pelangganService->data, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findAll()
    {
        $pelangganService = new PelangganService();
        try {
            $result = $pelangganService->findAll();
            return $this->responseManyData("succes get all pelanggan", $pelangganService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findById($id)
    {
        $pelangganService = new PelangganService();
        try {
            $result = $pelangganService->findById($id);
            if (!$result) {
                return $this->responseError($pelangganService->getErrorMessage(), $pelangganService->getCode());
            }
            return $this->responseSuccess("success get pelanggan id " . $id, $pelangganService->getData(), 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function update(PelangganRequest $req, $id)
    {
        $pelangganService = new PelangganService();
        try {
            $payload = $req->validated();
            $result = $pelangganService->update($id, $payload);
            if (!$result) {
                return $this->responseError($pelangganService->getErrorMessage(), $pelangganService->getCode());
            }
            return $this->responseSuccess("success update pelanggan id " . $id, $pelangganService->getData(), 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function destroy($id)
    {
        $pelangganService = new PelangganService();
        try {
            $result = $pelangganService->destroy($id);
            if (!$result) {
                return $this->responseError($pelangganService->getErrorMessage(), $pelangganService->getCode());
            }
            return $this->responseSuccess("success delete pelanggan id " . $id, $pelangganService->getData(), 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
