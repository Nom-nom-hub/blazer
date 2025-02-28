<?php

namespace App\Models;

use Blazer\Core\Model;

class User extends Model
{
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected static $table = 'users';
    
    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        $users = self::where('email', $email);
        
        return !empty($users) ? $users[0] : null;
    }
} 