<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index() {
    	return [
    		'app' => 'Extraordinary-LMS',
    		'version' => '1.0.1',
    		'author' => 'Shellrean ICT Team',
    	];
    }
}
