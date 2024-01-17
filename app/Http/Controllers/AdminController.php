<?php

namespace App\Http\Controllers;

use App\Dto\AdminDto;
use App\Http\Requests\AdminRequest;
use App\Jobs\InsertManyAdmin;
use App\Services\AdminService;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function create(AdminRequest $req)
    {
        $adminService = new AdminService();
        try {

            $payload = new AdminDto(...$req->validated());
            dd($payload);
            $operation = $adminService->store($payload);

            if (!$operation) {
                return $this->responseError($adminService->getErrorMessage(), $adminService->getCode());
            }

            return $this->responseSuccess("success create new Admin", $adminService->data, 201);
        } catch (Exception $th) {
            dd($th);
            return $this->responseError("There is Error in Server");
        }
    }


    public function index(Request $req)
    {
        $adminService = new AdminService();
        try {

            $page = $req->query("page", "1");
            $perPage = $req->query("perpage", $this->defaultTake);
            $search = $req->query("search");

            $adminService->findAll($page, $perPage, $search);

            // $meta = $this->metaPagination(
            //     $adminService->getTotalData(),
            //     $adminService->getPerPage(),
            //     $adminService->getPageNow()
            // );
            $meta = $adminService->getMetaPagination();

            return $this->responseManyData("success get All Admin", $adminService->getData(), $meta);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function destroy($id)
    {
        $adminService = new AdminService();
        try {

            $operation = $adminService->destroy($id);

            if (!$operation) {
                return $this->responseError($adminService->getErrorMessage(), $adminService->getCode());
            }
            return $this->responseSuccess("success delete Admin", $adminService->getData(), 200);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }
}
