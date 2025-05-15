<?php

namespace App\Core;

class View
{
    /**
     * Render a view template
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = BASE_PATH.'/views/'.$view.'.php';

        if (! file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }

        extract($data);

        ob_start();

        include $viewPath;

        return ob_get_clean();
    }

    /**
     * Render a view with a layout
     */
    public static function renderWithLayout(string $view, string $layout = 'main', array $data = []): string
    {
        $layoutPath = BASE_PATH.'/views/layouts/'.$layout.'.php';

        if (! file_exists($layoutPath)) {
            throw new \Exception("Layout file not found: $layoutPath");
        }

        $content = self::render($view, $data);

        $data['content'] = $content;

        ob_start();

        extract($data);

        include $layoutPath;

        return ob_get_clean();
    }
}
