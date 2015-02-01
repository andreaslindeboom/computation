<?php
namespace Computation\SmallStepSemantics\Elements;

use Computation\SmallStepSemantics\Base\Element;
use Computation\SmallStepSemantics\Base\NotReducible;

class Number extends Element
{
    use NotReducible;

    private $value;

    function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
