<?php

namespace Blazer\Core;

class Response
{
    /**
     * Response content
     *
     * @var mixed
     */
    protected $content;

    /**
     * HTTP status code
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Response headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * HTTP status codes
     *
     * @var array
     */
    protected $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        301 => 'Moved Permanently',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    /**
     * Create a new Response instance
     *
     * @param mixed $content Response content
     * @param int $status HTTP status code
     * @param array $headers Response headers
     */
    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $status;
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Set a response header
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function header($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get the response content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the response content
     *
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Send the response
     *
     * @return void
     */
    public function send()
    {
        // Send status code
        http_response_code($this->statusCode);

        // Set default content type if not set
        if (!isset($this->headers['Content-Type'])) {
            if (is_array($this->content) || is_object($this->content)) {
                $this->headers['Content-Type'] = 'application/json';
            } else {
                $this->headers['Content-Type'] = 'text/html; charset=UTF-8';
            }
        }

        // Send headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send content
        if (is_string($this->content)) {
            echo $this->content;
        } elseif (is_array($this->content) || is_object($this->content)) {
            echo json_encode($this->content);
        } else {
            echo (string)$this->content;
        }
    }
} 