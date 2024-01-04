<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Services\KategoriService;
use Exception;
use Illuminate\Http\Request;

class KategoriController extends Controller
{


    public function store(KategoriRequest $req)
    {
        $kategoriService = new KategoriService();
        try {
            $payload = $req->validated();

            $result = $kategoriService->store($payload);
            
            if (!$result) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }

            return $this->responseSuccess("success add new kategori", $payload, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }

    }

    public function findAll()
    {
        $kategoriService = new KategoriService();
        try {
            $kategoriService->findAll();

            return $this->responseManyData("success get all kategori", $kategoriService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findByIdFull($id)
    {
        $kategoriService = new KategoriService();
        try {
            $result = $kategoriService->findByIdFull($id);

            if (!$result) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }
            return $this->responseManyData("success get all kategori", $result);
            
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function findAllAlat()
    {
        $kategoriService = new KategoriService();
        try {
            $kategoriService->findAllAlat();
            
            return $this->responseManyData("success get all kategori and alat", $kategoriService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function update(KategoriRequest $req, $id)
    {
        $kategoriService = new KategoriService();
        try {
            $payload = $req->validated();
            $result = $kategoriService->update($id, $payload);
            if (!$result) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }

            return $this->responseSuccess("success update new kategori", $payload, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function destroy($id)
    {
        $kategoriService = new KategoriService();
        try {
            $result = $kategoriService->destroy($id);
            if (!$result) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }
            return $this->responseSuccess("success delete kategori", $kategoriService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
