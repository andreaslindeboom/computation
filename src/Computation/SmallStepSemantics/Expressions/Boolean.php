<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\NotReducible;

class Boolean extends Expression
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
        return $this->value ? 'true' : 'false';
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
