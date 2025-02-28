<?php

namespace App\Controllers;

use Blazer\Core\Controller;
use Blazer\Core\Request;
use Blazer\Core\Response;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Welcome to Blazer Framework',
            'description' => 'A lightweight PHP MVC framework'
        ];
        
        return $this->render('home/index', $data);
    }
    
    /**
     * Display the about page
     *
     * @param Request $request
     * @return Response
     */
    public function about(Request $request)
    {
        $data = [
            'title' => 'About Blazer',
            'content' => 'Blazer is a lightweight MVC framework built with PHP.'
        ];
        
        return $this->render('home/about', $data);
    }
} 