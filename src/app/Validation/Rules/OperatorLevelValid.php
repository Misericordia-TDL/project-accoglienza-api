<?php
/**
 * Copyright (c) 2017. This file belongs to Misericordia di "Torre del lago Puccini"
 */

namespace App\Validation\Rules;

use App\Models\Operator;
use App\Models\OperatorLevel;
use Respect\Validation\Rules\AbstractRule;

class OperatorLevelValid extends AbstractRule
{
    protected $operatorModel;
    /**
     * @var OperatorLevel
     */
    private $operatorLevelModel;

    function __construct(
        OperatorLevel $operatorLevelModel
    )
    {
        $this->operatorLevelModel = $operatorLevelModel;
    }

    public function validate($input)
    {
        return !empty($this->operatorLevelModel->findByLevel($input));
    }
}