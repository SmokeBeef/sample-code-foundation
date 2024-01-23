<?php

namespace App\Http\Controllers;

use App\DTO\Penyewaan\PenyewaanMutationDTO;
use App\DTO\Penyewaan\PenyewaanQueryDTO;
use App\Http\Requests\PenyewaanRequest;
use App\Services\PenyewaanService;
use Exception;
use Illuminate\Http\Request;

class PenyewaanController extends Controller
{

    public function store(PenyewaanRequest $req)
    {
        try {
            $payload = $req->validated();

            $penyewaanMutationDTO = new PenyewaanMutationDTO($payload);

            $operation = PenyewaanService::store($penyewaanMutationDTO);
            if ($operation->isSuccess) {
                return $this->responseSuccess($operation->getMessage(), $operation->getResult(), $operation->getCode());
            }

            return $this->responseError($operation->getMessage(), $operation->getCode());
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }

    public function index(Request $request)
    {
        try {
            $sortBy = $request->query("sortBy", "penyewaan_id");
            $sortOrder = $request->query("sortOrder", "asc");
            $search = $request->query("search", "");
            $configs = [
                "page" => $request->query("page", 1),
                "perpage" => $request->query("perpage", 25),
                "search" => $search,
                "sortBy" => $sortBy,
                "sortOrder" => $sortOrder,
            ];
            $penyewaanQueryDTO = new PenyewaanQueryDTO($configs);



            $operation = PenyewaanService::getPenyewaan($penyewaanQueryDTO);
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
            return $this->responseError("there is error in internal server");
        }
    }

    public function show($id, Request $req)
    {

        try {
            $join = [];
            if ($req->has("pelanggan")) {
                array_push($join, "pelanggan");
            }
            if ($req->has("detail")) {
                array_push($join, "penyewaanDetail");
            }

            $operation = PenyewaanService::getById($id);

            if ($operation->isSuccess) {
                return $this->responseSuccess($operation->getMessage(), $operation->getResult(), $operation->getCode());
            }

            return $this->responseError($operation->getMessage(), $operation->getCode());
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("there is error in internal server");
        }
    }
    // public function showFull($id)
    // {
    //     $penyewaanService = new PenyewaanService();
    //     try {

    //         $operation = $penyewaanService->findFull($id);

    //         if (!$operation) {
    //             return $this->responseError($penyewaanService->getErrorMessage(), $penyewaanService->getCode());
    //         }

    //         return $this->responseSuccess("success get penyewaan id " . $id, $penyewaanService->getData());
    //     } catch (Exception $err) {

    //         return $this->responseError("there is error in internal server");
    //     }
    // }

    public function update(PenyewaanRequest $req, $id)
    {

        try {
            $payload = $req->validated();
            $penyewaanMutationDTO = new PenyewaanMutationDTO($payload, $id);
            $operation = PenyewaanService::update($penyewaanMutationDTO);
            if ($operation->isSuccess) {
                return $this->responseSuccess($operation->getMessage(), $operation->getResult(), $operation->getCode());
            }
            return $this->responseError($operation->getMessage(), $operation->getCode());
        } catch (Exception $err) {
            return $this->responseError("there is error in internal server");
        }
    }
    public function destroy($id)
    {
        try {
            $operation = PenyewaanService::destroy($id);
            if ($operation->isSuccess) {
                return $this->responseSuccess($operation->getMessage(), $operation->getResult(), $operation->getCode());
            }
            return $this->responseError($operation->getMessage(), $operation->getCode());
        } catch (Exception $err) {
            dd($err);
            return $this->responseError("there is error in internal server");
        }
    }
}
