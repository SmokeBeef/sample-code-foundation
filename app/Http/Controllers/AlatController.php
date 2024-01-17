<?php

namespace App\Http\Controllers;

use App\DTO\Alat\AlatQueryDTO;
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

    public function index(Request $req)
    {
        try {

            $page = $req->query("page", "1");
            $perPage = $req->query("perpage", $this->defaultTake);
            $search = $req->query("search");
            $sort = $req->query("sort");
            $sortBy = $req->query("sortBy");

            $filter = [
                "search" => $search,
                "sort" => $sort,
                "sortBy" => $sortBy
            ];

            $alatQueryDTO = new AlatQueryDTO($page, $perPage, $filter);

            $operation = AlatService::findAll($alatQueryDTO);

            if (!$operation->isSuccess) {
                return $this->responseError($operation->getMessage(), $operation->getCode());
            }

            $meta = $this->metaPagination($operation->getTotal(), $operation->getPerPage(), $operation->getPage());

            return $this->responseManyData("success get all alat", $operation->getResult(), $meta);
        } catch (Exception $err) {
            dd($err);
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

            return $this->responseSuccess("success update alat", $alatService->getData(), 201);
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
            return $this->responseSuccess("success delete kategori", $alatService->getData());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
