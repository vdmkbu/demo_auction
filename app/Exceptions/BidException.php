<?php

namespace App\Exceptions;

use Exception;

class BidException extends Exception
{

    public function render($request)
    {
        if($request->ajax()) {

            $json = [
                'success' => false,
                'error' => $this->getMessage(),
            ];

            return response()->json($json, 400);
        }
        else {
            return redirect()->back()->with('error',$this->getMessage());
        }

    }
}
