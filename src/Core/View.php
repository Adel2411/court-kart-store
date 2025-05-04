<?php

namespace App\Core;

class View
{
    /**
     * Render a view template
     *
     * @param  string  $view  View template path
     * @param  array  $data  Data to pass to the view
     * @return string Rendered HTML
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = BASE_PATH.'/views/'.$view.'.php';

        if (! file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }

        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        include $viewPath;

        // Return captured output
        return ob_get_clean();
    }

    /**
     * Render a view with a layout
     *
     * @param  string  $view  View template path
     * @param  string  $layout  Layout template path
     * @param  array  $data  Data to pass to the view
     * @return string Rendered HTML with layout
     */
    public static function renderWithLayout(string $view, string $layout = 'main', array $data = []): string
    {
        $layoutPath = BASE_PATH.'/views/layouts/'.$layout.'.php';

        if (! file_exists($layoutPath)) {
            throw new \Exception("Layout file not found: $layoutPath");
        }

        // Render view content first
        $content = self::render($view, $data);

        // Add content to data for layout
        $data['content'] = $content;

        // Start output buffering
        ob_start();

        // Extract data to make variables available in layout
        extract($data);

        // Include the layout file
        include $layoutPath;

        // Return captured output
        return ob_get_clean();
    }
}
