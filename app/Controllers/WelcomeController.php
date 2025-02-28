<?php

namespace App\Controllers;

use Blazer\Core\Controller;
use Blazer\Core\Request;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->view('welcome', [
            'title' => 'Welcome to Blazer'
        ]);
    }
} 