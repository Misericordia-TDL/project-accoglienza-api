<?php
/**
 * Copyright (c) 2017. This file belongs to Misericordia di "Torre del lago Puccini"
 */

namespace App\Controllers;

use App\Models\OperatorLevel;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig as View;

/**
 * Class OperatorLevelController
 * @package App\Controllers
 * @author Javier Mellado <sol@javiermellado.com>
 */
class OperatorLevelController
{
    /**
     * @var View
     */
    protected $view;
    /**
     * @var OperatorLevel
     */
    protected $operatorLevelModel;

    /**
     * OperatorLevelController constructor.
     * @param View $view
     * @param OperatorLevel $operatorLevelModel
     */
    function __construct(
        View $view,
        OperatorLevel $operatorLevelModel
    )
    {
        $this->view = $view;
        $this->operatorLevelModel = $operatorLevelModel;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response): ResponseInterface
    {

        $json = ' {
    "name": "Manage Inventory",
    "description": "manages inventory",
    "level": 3,
    "join_date": "03-03-1980"
  }';
        $operatorLevelData = json_decode($json, true);
        $this->operatorLevelModel->insert($operatorLevelData);

        return $this->view->render($response, 'partials/operator/list.twig', []);
    }
}