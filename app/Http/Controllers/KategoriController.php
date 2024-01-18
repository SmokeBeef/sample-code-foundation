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
            $kategoriQueryDTO = KategoriQueryDTO::all($page, $perPage, $filter);
            $operation = KategoriService::findAll($kategoriQueryDTO);

            $meta = self::metaPagination($operation->getTotal(), $operation->getPerPage(), $operation->getPage());

            return $this->responseManyData("success get all kategori", $operation->getResult(), $meta);
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
