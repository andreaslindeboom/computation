<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;

class GetVariable extends Expression
{
    use Reducible;

    private $variableName;

    public function __construct($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->variableName";
    }

    public function reduce($environment)
    {
        return [$environment[$this->variableName], $environment];
    }
}
