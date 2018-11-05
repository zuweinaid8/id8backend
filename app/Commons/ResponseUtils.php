<?php

namespace App\Commons;

use Illuminate\Http\Response;

trait ResponseUtils
{
    public function generateResponse($message, $result, $code)
    {
        return response([
            'code' => $code,
            'message' => $message,
            'errors' => $result
        ], '200');
    }

    public function respond($message, $result, $code)
    {
        return response([
            'code' => $code,
            'message' => $message,
            'result' => $result
        ], '200');
    }

    public function respondNoResult($message, $code)
    {
        return response([
            'code' => $code,
            'message' => $message,
        ], '200');
    }

    public function respondCreated()
    {
        return response([], Response::HTTP_CREATED);
    }

    public function respondDeleted()
    {
        return response([
            'code' => 200,
            'message' => 'Deleted!',
        ], '200');
    }

    public function respondNoContent()
    {
        return response([], Response::HTTP_NO_CONTENT);
    }
}
