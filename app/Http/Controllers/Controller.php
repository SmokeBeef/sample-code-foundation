<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

// Format success
// GET
// success: boolean,
// code: int,
// message: string,
// pagination: {
//     page: int -> page sekarang,
//     perPage: int,
//     totalData: int,
//     totalPage: int,
//     links: {
//         first: string,
//         prev: string,
//         next: string,
//         last: string
//     }
// }
// data: array | object

// POST
// success: boolean,
// code: int,
// message: sting,
// data: object -> data apa yang dibuat,

// PATCH
// success: boolean,
// code: int,
// message: string,
// data: object -> data yang sudah terupdate

// DELETE
// success: boolean,
// code: int,
// message: string,
// data: object -> data apa yang dihapus

// // Format Error
// GET
// success: boolean,
// code: int,
// message: string -> get by id gagal tidak ditemukan, .....
// errors: null

// POST
// success: boolean,
// code: int,
// message: string -> get by id gagal tidak ditemukan, .....
// errors: error dari validator

// PATCH
// success: boolean,
// code: int,
// message: string -> get by id gagal tidak ditemukan, .....
// errors: error dari validator

// DELETE
// success: boolean,
// code: int,
// message: string -> get by id gagal tidak ditemukan, .....
// errors: null


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $defaultTake = 25;
    protected $defaultPerPage = 25;


    /**
     * Deprecated 
     * @return JsonResponse 
     * */
    protected function responseManyData(string $message, mixed $data, array $meta = null, int $code = 200): JsonResponse
    {
        if (!$meta)
            return response()->json([
                "success" => true,
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ], $code);
        else
            return response()->json([
                "success" => true,
                "code" => $code,
                "message" => $message,
                "data" => $data,
                "meta" => $meta,
            ], $code);
    }
    protected function responseSuccess(string $message, mixed $data = null, int $code = 200, ?array $pagination = null): JsonResponse
    {
        if ($pagination)
            return response()->json([
                "success" => true,
                "code" => $code,
                "message" => $message,
                "data" => $data,
                "pagination" => $pagination
            ], $code);
        else
            return response()->json([
                "success" => true,
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ], $code);
    }
    protected function responseError(string $message, int $code = 500, $data = null): JsonResponse
    {
        return response()->json([
            "success" => false,
            "code" => $code,
            "message" => $message,
            "errors" => null
        ], $code);
    }


    // additional function
    // no for now
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
