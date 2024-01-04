<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Services\KategoriService;
use Exception;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $kategoriService;
    public function __construct(KategoriService $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    public function store(KategoriRequest $req)
    {
        try {
            $payload = $req->validated();

            $result = $this->kategoriService->store($payload);
            if (!$result) {
                return $this->responseError($result->errorMessage, $result->errorCode);
            }

            return $this->responseSuccess("success add new kategori", $payload, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }

    }

    public function findAll()
    {
        try {
            $result = $this->kategoriService->findAll();

            return $this->responseManyData("success get all kategori", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findByIdFull($id)
    {
        try {
            $result = $this->kategoriService->findByIdFull($id);

            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseManyData("success get all kategori", $result);
            
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function findAllAlat()
    {
        try {
            $result = $this->kategoriService->findAllAlat();
            
            return $this->responseManyData("success get all kategori and alat", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function update(KategoriRequest $req, $id)
    {
        try {
            $payload = $req->validated();
            $result = $this->kategoriService->update($id, $payload);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }

            return $this->responseSuccess("success update new kategori", $payload, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function destroy($id)
    {
        try {
            $result = $this->kategoriService->destroy($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success delete kategori");
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
