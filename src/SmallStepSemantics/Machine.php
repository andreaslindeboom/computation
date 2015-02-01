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
        $instructionCounter = 1;

        echo "~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*" . PHP_EOL . PHP_EOL;
        while ($this->expression->isReducible()) {
            $this->report($instructionCounter);

            $this->step();
            $instructionCounter++;
        }

        $this->report($instructionCounter);

        return [$this->expression, $this->environment];
    }

    private function step()
    {
        list($this->expression, $this->environment) = $this->expression->reduce($this->environment);
    }

    /**
     * @param $instruction
     */
    private function report($instruction)
    {
        echo "$instruction:\t" . $this->reportExpression() . $this->reportEnvironment() . PHP_EOL;
    }

    /**
     * @return string
     */
    private function reportEnvironment()
    {
        $output = [];

        foreach ($this->environment as $label => $value) {
            array_push($output, "$label: $value");
        }

        return "{" . implode(",", $output) . "}";
    }

    /**
     * @return mixed
     */
    private function reportExpression()
    {
        return str_pad($this->expression, 80);
    }

}
