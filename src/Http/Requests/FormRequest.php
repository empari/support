<?php
namespace Empari\Support\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use \Illuminate\Foundation\Http\FormRequest as Request;

class FormRequest extends Request
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        //Always return Json
        return new JsonResponse([
            'data' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Rules for authorize Request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}