<?php
/**
 * Copyright (c) 2017. This file belongs to Misericordia di "Torre del lago Puccini"
 */

namespace App\Controllers\Operator;

use App\Models\Eloquent\OperatorRepository;
use App\Models\Exception\EmptyDataSetException;
use App\Models\Operator;
use App\Models\OperatorLevel;
use App\Validation\Validator;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;
use Respect\Validation\Validator as v;

/**
 * Class createOperator
 * @package App\Controllers\Operator
 * @author Javier Mellado <sol@javiermellado.com>
 */
final class CreateOperatorAction
{
    /**
     * @var OperatorRepository
     */
    protected $operatorRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var Messages
     */
    private $flash;
    /**
     * @var OperatorLevel
     */
    private $operatorLevel;

    /**
     * OperatorController constructor.
     * @param RouterInterface $router
     * @param Validator $validator
     * @param OperatorRepository $operatorRepository
     * @param Messages $flash
     * @param OperatorLevel $operatorLevel
     * @internal param View $view
     */
    function __construct(
        RouterInterface $router,
        Validator $validator,
        OperatorRepository $operatorRepository,
        Messages $flash,
        OperatorLevel $operatorLevel
    )
    {
        $this->operatorRepository = $operatorRepository;
        $this->router = $router;
        $this->validator = $validator;
        $this->flash = $flash;
        $this->operatorLevel = $operatorLevel;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailNotTaken(),
            'password' => v::noWhitespace()->notEmpty(),
            'name' => v::notEmpty()->alpha()->length(2, 20),
            'surname' => v::notEmpty()->alpha()->length(2, 20),
            'phonenumber' => v::noWhitespace()->notEmpty()->numeric()->phone(),
            'operator_level' => v::noWhitespace()->notEmpty()->OperatorLevelValid($this->operatorLevel),
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Operator data is not correct');
            return $response->withRedirect($this->router->pathFor('enter-operator-data'));
        }

        $operatorData = $request->getParams();
        unset(
            $operatorData['csrf_name'],
            $operatorData['csrf_value']
        );

        try {
            $this->operatorRepository->insert($operatorData);
            $this->flash->addMessage('info', 'Operator created correctly');

        } catch (\InvalidArgumentException $e) {
            $this->flash->addMessage('error', 'Operator not found. Could not perform deletion.');
        } catch (EmptyDataSetException $e) {
            $this->flash->addMessage('error', $e->getMessage());
        }


        return $response->withRedirect($this->router->pathFor('list-operator'));
    }
}