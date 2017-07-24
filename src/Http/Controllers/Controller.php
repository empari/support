<?php
namespace Empari\Support\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Prepare response Json
     *
     * @param array $response
     * @return array
     */
    protected function prepareResponse(Array $response)
    {
        return [
            'data' => $response
        ];
    }
}