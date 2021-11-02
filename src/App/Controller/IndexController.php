<?php

namespace Template\App\Controller;

use Exception;
use JsonException;
use Template\App\Data;
use Template\Core\App;

class IndexController
{

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function index()
    {
        /** @var Data $data */
        $data = App::get('data');
        $templates = $data->get();

        return view('index', [
            'templates' => $templates,
            'hasTemplates' => (bool)$templates,
            'json' => json_encode(array_values($data->get()), JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),
        ]);
    }

}
