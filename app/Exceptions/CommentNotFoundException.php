<?php

namespace App\Exceptions;

use Exception;
use App\Actions\SendResponse;

class CommentNotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return SendResponse::notFound('comment not found');
    }
}
