<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Jobs\InsertManyAdmin;
use App\Services\AdminService;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function store(AdminRequest $req)
    {
        $adminService = new AdminService();
        try {

            $payload = $req->validated();
            $result = $adminService->store($payload);

            if (!$result) {
                return $this->responseError($adminService->getErrorMessage(), $adminService->getCode());
            }

            return $this->responseSuccess("success create new Admin", $adminService->data, 201);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }


    public function findAll()
    {
        $adminService = new AdminService();
        try {

            $adminService->findAll();

            return $this->responseSuccess("success get All Admin", $adminService->data, 200);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }

    public function destroy($id)
    {
        $adminService = new AdminService();
        try {

            $result = $adminService->destroy($id);
           
            if (!$result) {
                return $this->responseError($adminService->getErrorMessage(), $adminService->getCode());
            }
            return $this->responseSuccess("success delete Admin", $adminService->getData(), 200);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }
}
