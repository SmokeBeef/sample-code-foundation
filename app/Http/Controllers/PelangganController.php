<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelangganRequest;
use App\Services\PelangganService;
use Exception;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    protected $pelangganService;
    public function __construct(PelangganService $pelangganService)
    {
        $this->pelangganService = $pelangganService;
    }

    public function store(PelangganRequest $req)
    {
        try {
            $payload = $req->validated();
            $pelangganData = [
                "jenis" => $payload["jenis"],
                "file" => $payload["photo"]
            ];
            unset($payload["jenis"]);
            unset($payload["photo"]);

            $result = $this->pelangganService->store($payload, $pelangganData);

            if (!$result) {
                return $this->responseError("Email already taken", 409);
            }
            return $this->responseSuccess("success add new pelanggan", $result, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findAll()
    {
        try {
            $result = $this->pelangganService->findAll();
            return $this->responseManyData("succes get all pelanggan", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findById($id)
    {
        try {
            $result = $this->pelangganService->findById($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success get pelanggan id " . $id, $result, 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function update(PelangganRequest $req, $id)
    {
        try {
            $payload = $req->validated();
            $result = $this->pelangganService->update($id, $payload);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success update pelanggan id " . $id, $result, 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function destroy($id)
    {
        try {
            $result = $this->pelangganService->destroy($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success delete pelanggan id " . $id, $result, 200);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
