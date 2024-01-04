<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlatRequest;
use App\Services\AlatService;
use Exception;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    
    public function store(AlatRequest $req)
    {
        $alatService = new AlatService();
        try {
            $payload = $req->validated();
            $result = $alatService->store($payload);
            
            if (!$result) {
                return $this->responseError("nama alat already use", 409);
            }
            return $this->responseSuccess("success add alat", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function findAll()
    {
        $alatService = new AlatService();
        try {

            $result = $alatService->findAll();

            return $this->responseManyData("success get all alat", $alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findById($id)
    {
        $alatService = new AlatService();
        try {
            $result = $alatService->findById($id);
            if(!$result){
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success get all alat", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function update(AlatRequest $req, $id)
    {
        $alatService = new AlatService();
        try {
            $payload = $req->validated();
            $result = $alatService->update($id, $payload);
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
        $alatService = new AlatService();
        try {
            $result = $alatService->destroy($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success delete kategori");
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
