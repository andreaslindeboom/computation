<?php
namespace Computation\SmallStepSemantics\Elements;

use Computation\SmallStepSemantics\Base\Element;
use Computation\SmallStepSemantics\Base\Reducible;

class GetVariable extends Element
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
        return "get:$this->variableName";
    }

    public function reduce($environment)
    {
        return [$environment[$this->variableName], $environment];
    }
}
