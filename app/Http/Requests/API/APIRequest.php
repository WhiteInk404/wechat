<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Response;

class APIRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $messages = implode(' ', array_flatten($errors));

        return Response::json($this->makeError($messages), 400);
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
