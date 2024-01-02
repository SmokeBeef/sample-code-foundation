<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlatRequest;
use App\Services\AlatService;
use Exception;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    protected $alatService;
    public function __construct(AlatService $alatService)
    {
        $this->alatService = $alatService;
    }

    public function store(AlatRequest $req)
    {
        try {
            $payload = $req->validated();
            $result = $this->alatService->store($payload);
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
        try {
            $result = $this->alatService->findAll();

            return $this->responseSuccess("success get all alat", $result);
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
    public function findById($id)
    {
        try {
            $result = $this->alatService->findById($id);
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
        try {
            $payload = $req->validated();
            $result = $this->alatService->update($id, $payload);
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
            $result = $this->alatService->destroy($id);
            if (!$result) {
                return $this->responseError("id " . $id . " not found", 404);
            }
            return $this->responseSuccess("success delete kategori");
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
