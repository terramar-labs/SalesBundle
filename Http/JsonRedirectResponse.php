<?php

namespace Terramar\Bundle\SalesBundle\Http;

/**
 * A successful JsonSuccessResponse that will cause the client's browser to reload the  current page
 */
class JsonRedirectResponse extends JsonSuccessResponse
{
    public function __construct($path)
    {
        parent::__construct('', $path);
    }
}
