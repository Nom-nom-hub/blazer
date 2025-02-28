<?php

namespace Blazer\Core;

use Blazer\Core\Response;
use Blazer\Core\DevServer;

abstract class Controller
{
    private static $viewCache = [];

    /**
     * Get the views path from config
     *
     * @return string
     */
    protected function getViewsPath()
    {
        $config = require_once ROOT_DIR . '/config/config.php';
        return isset($config['views']['path']) 
            ? $config['views']['path'] 
            : ROOT_DIR . '/app/Views';
    }

    /**
     * Render a view
     *
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @return Response
     */
    protected function view($view, array $data = [])
    {
        // Use cached view path if available
        if (!isset(self::$viewCache[$view])) {
            $viewPath = ROOT_DIR . '/app/Views/' . str_replace('.', '/', $view) . '.php';
            if (!file_exists($viewPath)) {
                throw new \Exception("View file not found: {$viewPath}");
            }
            self::$viewCache[$view] = $viewPath;
        }
        
        // Extract data to make it available in view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file using cached path
        include self::$viewCache[$view];
        
        $content = ob_get_clean();
        
        // In debug mode, add file watcher
        if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG']) {
            $script = <<<HTML
            <script>
            console.log('[Blazer] File watcher started');
            
            let currentHash = '';
            let isChecking = false;
            
            async function checkForChanges() {
                if (isChecking) return;
                
                try {
                    isChecking = true;
                    const response = await fetch('/?watch=1&hash=' + currentHash);
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const data = await response.json();
                    if (data.reload) {
                        console.log('[Blazer] Changes detected, reloading...');
                        window.location.reload();
                        return;
                    }
                    
                    if (data.hash) {
                        currentHash = data.hash;
                    }
                } catch (e) {
                    console.error('[Blazer] Watch error:', e);
                } finally {
                    isChecking = false;
                }
            }
            
            // Check every 1 second
            setInterval(checkForChanges, 1000);
            </script>
            HTML;
            
            $content = str_replace('</body>', $script . '</body>', $content);
        }
        
        return new Response($content);
    }

    /**
     * Create a JSON response
     *
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @param array $headers HTTP headers
     * @return Response
     */
    protected function json($data, $statusCode = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        return new Response(
            json_encode($data),
            $statusCode,
            $headers
        );
    }

    /**
     * Create a redirect response
     *
     * @param string $url URL to redirect to
     * @param int $statusCode HTTP status code
     * @return Response
     */
    protected function redirect($url, $statusCode = 302)
    {
        return new Response('', $statusCode, ['Location' => $url]);
    }

    protected function getConfig()
    {
        static $config = null;
        if ($config === null) {
            $config = require ROOT_DIR . '/config/config.php';
        }
        return $config;
    }
} 