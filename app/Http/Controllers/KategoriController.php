<?php

namespace App\Http\Controllers;

use App\DTO\Kategori\KategoriQueryDTO;
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

            $operation = $kategoriService->create($payload);

            if (!$operation) {
                return $this->responseError($kategoriService->getErrorMessage(), $kategoriService->getCode());
            }

            return $this->responseSuccess("success add new kategori", $payload, 201);
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
                'sortOrder' => $sortOrder = $request->query("sort", "alat_id"),
                'search' => $search = $request->query("search", '')
            ];
            $kategoriQueryDTO = new KategoriQueryDTO($configs);
            $operation = KategoriService::getAll($kategoriQueryDTO);

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
