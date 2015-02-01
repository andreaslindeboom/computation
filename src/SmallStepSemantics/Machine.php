<?php
namespace Computation\SmallStepSemantics;

class Machine
{
    private $expression;
    private $environment;

    public function __construct($expression, $environment = [])
    {
        $this->expression = $expression;
        $this->environment = $environment;
    }

    public function run()
    {
        $instruction = 1;

        echo "~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*" . PHP_EOL . PHP_EOL;
        while ($this->expression->isReducible()) {
            echo $instruction . ": " . $this->expression . PHP_EOL;

            $this->step();
        }
        echo $instruction . ": " . $this->expression . PHP_EOL . PHP_EOL;

        return $this->expression;
    }

    private function step()
    {
        $this->expression = $this->expression->reduce($this->environment);
    }

}
