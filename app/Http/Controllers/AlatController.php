<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlatRequest;
use App\Services\AlatService;
use Exception;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    
    public function create(AlatRequest $req)
    {
        $alatService = new AlatService();
        try {
            $payload = $req->validated();
            $operation = $alatService->store($payload);
            
            if (!$operation) {
                return $this->responseError($alatService->getErrorMessage(), $alatService->getCode());
            }
            return $this->responseSuccess("success add alat", $alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function index()
    {
        $alatService = new AlatService();
        try {

            $operation = $alatService->findAll();

            return $this->responseManyData("success get all alat", $alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function show($id)
    {
        $alatService = new AlatService();
        try {
            $operation = $alatService->findById($id);
            if (!$operation) {
                return $this->responseError($alatService->getErrorMessage(), $alatService->getCode());
            }
            return $this->responseSuccess("success get all alat", $alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function update(AlatRequest $req, $id)
    {
        $alatService = new AlatService();
        try {
            $payload = $req->validated();
            $operation = $alatService->update($id, $payload);
            if (!$operation) {
                return $this->responseError($alatService->getErrorMessage(), $alatService->getCode());
            }

            return $this->responseSuccess("success update new kategori", $alatService->getData(), 201);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function destroy($id)
    {
        $alatService = new AlatService();
        try {
            $operation = $alatService->destroy($id);
            if (!$operation) {
                return $this->responseError($alatService->getErrorMessage(), $alatService->getCode());
            }
            return $this->responseSuccess("success delete kategori",$alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
