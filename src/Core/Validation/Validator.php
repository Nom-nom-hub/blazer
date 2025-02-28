<?php

namespace Blazer\Core\Validation;

class Validator {
    private $errors = [];
    
    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            if (!$this->validateField($data[$field] ?? null, $rule)) {
                $this->errors[$field] = "The {$field} field is invalid";
            }
        }
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
} 