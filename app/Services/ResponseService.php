<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 16.05.19
 * Time: 22:56
 */

namespace App\Services;


class ResponseService
{
    public function showError($msg)
    {
        return response()->json([
            'status' => 'error',
            'msg' => $msg
        ]);
    }

    public function showSuccess($msg)
    {
        return response()->json([
            'status' => 'success',
            'msg' => $msg
        ], 200);
    }
}