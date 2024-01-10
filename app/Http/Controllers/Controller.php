<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $defaultTake = 25;
    

    protected function responseManyData(string $message, mixed $data, array $meta = null, int $code = 200): JsonResponse
    {
        if (!$meta)
            return response()->json([
                "status" => "success",
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ], $code);
        else
            return response()->json([
                "status" => "success",
                "code" => $code,
                "message" => $message,
                "data" => $data,
                "meta" => $meta,
            ], $code);
    }
    protected function responseSuccess(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "code" => $code,
            "message" => $message,
            "data" => $data
        ], $code);
    }
    protected function responseError(string $message, int $code = 500, $data = null): JsonResponse
    {
        return response()->json([
            "status" => "fail",
            "code" => $code,
            "message" => $message,
            "data" => $data
        ], $code);
    }


    // additional function
    protected function metaPagination(int $totalData, int $perPage, int $currentPage): array
    {
        return [
            "total_data" => $totalData,
            "per_page" => $perPage,
            "current_page" => $currentPage,
            "total_page" => ceil($totalData / $perPage),
        ];
    }
}
