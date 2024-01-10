<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Services\KategoriService;
use Exception;
use Illuminate\Http\Request;

class KategoriController extends Controller
{


    public function create(KategoriRequest $req)
    {
        $kategoriService = new KategoriService();
        try {
            $payload = $req->validated();

            $operation = $kategoriService->create($payload);
            
            if (!$operation) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }

            return $this->responseSuccess("success add new kategori", $payload, 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }

    }

    public function index()
    {
        $kategoriService = new KategoriService();
        try {
            $kategoriService->findAll();

            return $this->responseManyData("success get all kategori", $kategoriService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function show($id)
    {
        $kategoriService = new KategoriService();
        try {
            $operation = $kategoriService->findByIdFull($id);

            if (!$operation) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }
            return $this->responseManyData("success get all kategori", $operation);
            
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }


    public function update(KategoriRequest $req, $id)
    {
        $kategoriService = new KategoriService();
        try {
            $payload = $req->validated();
            $operation = $kategoriService->update($id, $payload);
            if (!$operation) {
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
            $operation = $kategoriService->destroy($id);
            if (!$operation) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }
            return $this->responseSuccess("success delete kategori", $kategoriService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
