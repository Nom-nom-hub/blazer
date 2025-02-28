<?php

namespace Blazer\Core;

use Blazer\Core\Database\DB;

abstract class Model
{
    /**
     * Database connection
     *
     * @var \PDO
     */
    protected static $db;
    
    /**
     * Table name
     *
     * @var string
     */
    protected static $table;
    
    /**
     * Primary key
     *
     * @var string
     */
    protected static $primaryKey = 'id';
    
    /**
     * Model attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Initialize the database connection
     *
     * @return \PDO
     */
    protected static function getConnection()
    {
        if (self::$db === null) {
            $config = require_once __DIR__ . '/../../config/config.php';
            $dbConfig = $config['database'];
            
            $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            
            try {
                self::$db = new \PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (\PDOException $e) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$db;
    }

    /**
     * Get the table name
     *
     * @return string
     */
    protected static function getTable()
    {
        if (static::$table === null) {
            // Generate table name from class name
            $className = (new \ReflectionClass(static::class))->getShortName();
            static::$table = strtolower($className) . 's';
        }
        
        return static::$table;
    }

    /**
     * Create a new model instance
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill model with attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        
        return $this;
    }

    /**
     * Get an attribute
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set an attribute
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Check if attribute exists
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Get all model attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Find a model by primary key
     *
     * @param mixed $id
     * @return static|null
     */
    public static function find($id)
    {
        $stmt = DB::query("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get all models
     *
     * @return array
     */
    public static function all()
    {
        $stmt = DB::query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Create a new record
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes)
    {
        $model = new static($attributes);
        $model->save();
        
        return $model;
    }

    /**
     * Save the model
     *
     * @return bool
     */
    public function save()
    {
        $table = static::getTable();
        $primaryKey = static::$primaryKey;
        
        // Determine if we're creating or updating
        if (isset($this->attributes[$primaryKey])) {
            // Update
            $id = $this->attributes[$primaryKey];
            $fields = [];
            $values = [];
            
            foreach ($this->attributes as $key => $value) {
                if ($key !== $primaryKey) {
                    $fields[] = "{$key} = :{$key}";
                    $values[$key] = $value;
                }
            }
            
            $fields = implode(', ', $fields);
            $values[$primaryKey] = $id;
            
            $stmt = static::getConnection()->prepare("UPDATE {$table} SET {$fields} WHERE {$primaryKey} = :{$primaryKey}");
            return $stmt->execute($values);
        } else {
            // Insert
            $fields = array_keys($this->attributes);
            $placeholders = array_map(function($field) {
                return ":{$field}";
            }, $fields);
            
            $fields = implode(', ', $fields);
            $placeholders = implode(', ', $placeholders);
            
            $stmt = static::getConnection()->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");
            $result = $stmt->execute($this->attributes);
            
            if ($result) {
                $this->attributes[$primaryKey] = static::getConnection()->lastInsertId();
            }
            
            return $result;
        }
    }

    /**
     * Delete the model
     *
     * @return bool
     */
    public function delete()
    {
        $table = static::getTable();
        $primaryKey = static::$primaryKey;
        
        if (!isset($this->attributes[$primaryKey])) {
            return false;
        }
        
        $stmt = static::getConnection()->prepare("DELETE FROM {$table} WHERE {$primaryKey} = :id");
        return $stmt->execute(['id' => $this->attributes[$primaryKey]]);
    }

    /**
     * Find models by a where clause
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function where($column, $value)
    {
        $table = static::getTable();
        
        $stmt = static::getConnection()->prepare("SELECT * FROM {$table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        
        $data = $stmt->fetchAll();
        
        $models = [];
        foreach ($data as $attributes) {
            $models[] = new static($attributes);
        }
        
        return $models;
    }
} 