<?php

// app/Traits/ApiResponse.php
namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data = [], $message = 'Successfull!', $status = 1)
    {
        return response()->json(array_merge([
            'status' => $status,
            'message' => $message,
        ], $data));
    }

    public function errorResponse($message = 'Xatolik yuz berdi.', $statusCode = 400)
    {
        return response()->json([
            'status' => 0,
            'message' => $message,
        ], $statusCode);
    }

    public function paginationResponse($paginator)
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ];
    }

    public function singleItemResponse($item, $message = "Successfull!")
    {
        return $this->successResponse([
            'item' => $item
        ], $message);
    }
}
