<?php

namespace Terramar\Bundle\SalesBundle\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * A successful JsonSuccessResponse tailored to the Terramar client side library
 */
class JsonSuccessResponse extends JsonResponse
{
    /**
     * Constructor
     *
     * @param string $message
     * @param bool   $redirect
     * @param bool   $reload
     */
    public function __construct($message = '', $redirect = false, $reload = false)
    {
        $data = array(
            'type' => 'success',
            'message' => $message,
            'redirect' => $redirect,
            'reload' => $reload
        );

        parent::__construct($data);
    }
}
