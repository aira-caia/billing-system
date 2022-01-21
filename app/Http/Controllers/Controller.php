<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// This is parent controller, provided by laravel.
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function log($type, $remarks)
    {
        Log::create([
            'type' => $type,
            'remarks' => $remarks
        ]);
    }
}
