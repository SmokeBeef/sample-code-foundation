<?php

namespace App\Http\Controllers;

use App\DTO\Alat\AlatQueryDTO;
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
            $operation = $alatService->store($payload);

            if (!$operation) {
                return $this->responseError($alatService->getMessage(), $alatService->getCode());
            }
            return $this->responseSuccess("success add alat", $alatService->getResult());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function index(Request $request)
    {
        try {


            $configs = [
                'page' => $request->query('page', 1),
                'perpage' => $request->query("perpage", $this->defaultPerPage),
                'sortBy' => $sortBy = $request->query("sortBy", "asc"),
                'sortOrder' => $sortOrder = $request->query("sortOrder", "alat_id"),
                'search' => $search = $request->query("search", '')
            ];

            $alatQueryDTO = new AlatQueryDTO($configs);

            $operation = AlatService::getAlat($alatQueryDTO);

            if ($operation->isSuccess) {
                $page = $operation->getPage();
                $perPage = $operation->getPerPage();
                $totalData = $operation->getTotal();
                $totalPage = ceil($totalData / $perPage);
                $pagination = [
                    "page" => $page,
                    "perPage" => $perPage,
                    "totalData" => $totalData,
                    "totalPage" => $totalPage,
                    "links" => [
                        "first" => url()->current() . "?page=" . 1 . "&perpage=$perPage&sortBy=$sortBy&sortOrder=$sortOrder&search=$search",
                        "prev" => url()->current() . "?page=" . ($page - 1) . "&perpage=$perPage&sortBy=$sortBy&sortOrder=$sortOrder&search=$search",
                        "next" => url()->current() . "?page=" . ($page + 1) . "&perpage=$perPage&sortBy=$sortBy&sortOrder=$sortOrder&search=$search",
                        "last" => url()->current() . "?page=" . $totalPage . "&perpage=$perPage&sortBy=$sortBy&sortOrder=$sortOrder&search=$search",

                    ]
                ];
                return $this->responseSuccess($operation->getMessage(), $operation->getResult(), 200, $pagination);
            }

            return $this->responseError($operation->getMessage(), $operation->getCode());
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("There is Error in Server");
        }
    }
    public function show($id, Request $req)
    {
        try {
            $join = ["kategori" => false];
            if ($req->has("kategori")) {
                $join = ["kategori" => true];
            }

            $operation = AlatService::findById($id);
            if (!$operation->isSuccess) {
                return $this->responseError($operation->getMessage(), $operation->getCode());
            }
            return $this->responseSuccess($operation->getMessage(), $operation->getResult(), $operation->getCode());
        } catch (Exception $err) {
            dd($err);
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
                return $this->responseError($alatService->getMessage(), $alatService->getCode());
            }

            return $this->responseSuccess("success update alat", $alatService->getResult(), 201);
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
                return $this->responseError($alatService->getMessage(), $alatService->getCode());
            }
            return $this->responseSuccess("success delete kategori", $alatService->getResult());
        } catch (Exception $err) {
            return $this->responseError("There is Error in Server");
        }
    }
}
