<?php

namespace App\Exceptions;

use Exception;

class SecurityException extends Exception
{
    protected $message = 'A security issue was identified with your request, and it was terminated.';

    public function render()
    {
        return response()->json([
            'code' => 410,
            'message' => $this->message,
        ], 403);
    }
}
