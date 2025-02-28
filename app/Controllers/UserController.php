<?php

namespace App\Controllers;

use Blazer\Core\Controller;
use Blazer\Core\Request;
use Blazer\Core\Response;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        
        return $this->render('users/index', ['users' => $users]);
    }
    
    /**
     * Display a user
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->render('errors/404', [], 404);
        }
        
        return $this->render('users/show', ['user' => $user]);
    }
    
    /**
     * Store a new user
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->body();
        
        // Simple validation example
        if (empty($data['name']) || empty($data['email'])) {
            return $this->json(['error' => 'Name and email are required'], 422);
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json(['user' => $user->getAttributes(), 'message' => 'User created successfully'], 201);
    }
    
    /**
     * Update a user
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        $data = $request->body();
        
        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }
        
        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }
        
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
        
        return $this->json(['user' => $user->getAttributes(), 'message' => 'User updated successfully']);
    }
    
    /**
     * Delete a user
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        
        $user->delete();
        
        return $this->json(['message' => 'User deleted successfully']);
    }
} 