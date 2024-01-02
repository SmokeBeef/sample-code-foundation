<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    protected function responseManyData(string $message, mixed $data, array $meta = null, int $code = 200): JsonResponse
    {
        if (!$meta)
            return response()->json([
                "code" => $code,
                "message" => $message,
                "datas" => $data,
            ], $code);
        else
            return response()->json([
                "code" => $code,
                "message" => $message,
                "datas" => $data,
                "meta" => $meta,
            ], $code);
    }
    protected function responseSuccess(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            "code" => $code,
            "message" => $message,
            "data" => $data
        ], $code);
    }
    protected function responseError(string $message, int $code = 500, $data = null): JsonResponse
    {
        return response()->json([
            "code" => $code,
            "message" => $message,
            "data" => $data
        ], $code);
    }


    // additional function
    protected function metaPagination(int $totalData, int $perPage, int $currentPage): array
    {
        return [
            "totalData" => $totalData,
            "perPage" => $perPage,
            "currentPage" => $currentPage,
            "totalPage" => ceil($totalData / $perPage),
        ];
    }
}
