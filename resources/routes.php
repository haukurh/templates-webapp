<?php

use Template\Core\Router;

/** @var Router $router */
$router->get('', 'IndexController@index');
$router->get('template/create', 'TemplateController@create');
$router->get('template/edit/{id}', 'TemplateController@edit');
$router->delete('template/delete/{id}', 'TemplateController@delete');
$router->post('template/save', 'TemplateController@save');
