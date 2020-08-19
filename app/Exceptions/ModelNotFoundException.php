<?php

namespace App\Exceptions;

use Exception;
use App\Actions\SendResponse;

class ModelNotFoundException extends Exception
{
    protected $message;

	public function __construct($message) 
	{
		parent::__construct();

		$this->message = $message;
	}

	public function render($request)
    {
        return SendResponse::notFound($this->message);
    }
}
