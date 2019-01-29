<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    function __construct() {
        checkLastAdminSite();
    }

    protected function validationErrorResponse(\Exception $e) {
        return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->getErrors()->getMessages()
                        ], 422);
    }

}
