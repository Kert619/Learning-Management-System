<?php

namespace App\Traits;

trait HttpResponse
{

    public function success($data, $message = "Success", $code = 200)
    {
        return response()->json(['data' => $data, 'message' => $message, 'status_code' => $code], $code);
    }

    public function error($message = "Failed", $code = 400)
    {
        return response()->json(['message' => $message, 'status_code' => $code], $code);
    }
}
