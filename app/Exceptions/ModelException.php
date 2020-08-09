<?php

namespace App\Exceptions;

use Exception;
use App\Actions\SendResponse;

class ModelException extends Exception
{
	protected $message;

	public function __construct($message) 
	{
		parent::__construct();

		$this->message = $message;
	}
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return SendResponse::serverError($this->message);
    }
}
