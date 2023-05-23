<?php

namespace App;

class ViewRenderer
{
    public function render(string $view, array $params): ?string
    {
        extract($params);


        ob_start();

        include $view;
        $content = ob_get_contents();
        $layout = file_get_contents('../View/layout.html');
        $result = str_replace('{content}', $content, $layout);

        ob_get_clean();

        return $result;
    }

}