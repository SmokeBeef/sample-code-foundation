<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Jobs\InsertManyAdmin;
use App\Services\AdminService;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    public function store(AdminRequest $req)
    {
        try {

            $payload = $req->validated();
            $result = $this->adminService->store($payload);

            if (!$result) {
                return $this->responseError("username already exist", 409);
            }

            return $this->responseSuccess("success create new Admin", $result, 201);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }


    public function findAll()
    {
        try {

            $result = $this->adminService->findAll();

            return $this->responseSuccess("success get All Admin", $result, 200);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }
    
    public function destroy($id)
    {
        try {

            $result = $this->adminService->destroy($id);
            if(!$result){
                return $this->responseError("id $id not found", 404);
            }
            return $this->responseSuccess("success delete Admin", $result, 200);
        } catch (Exception $th) {
            return $this->responseError("There is Error in Server");
        }
    }
}
