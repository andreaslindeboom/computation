<?php
namespace Computation\SmallStepSemantics\Tests;

use Computation\SmallStepSemantics\Elements\Add;
use Computation\SmallStepSemantics\Elements\Boolean;
use Computation\SmallStepSemantics\Elements\Multiply;
use Computation\SmallStepSemantics\Elements\Number;
use Computation\SmallStepSemantics\Elements\GetVariable;
use Computation\SmallStepSemantics\Elements\LessThan;
use Computation\SmallStepSemantics\Machine;
use Computation\SmallStepSemantics\Statements\AssignVariable;

class MachineTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function shouldAddNonReducibleElements()
    {
        $machine = new Machine(
            new Add(
                new Number(1),
                new Number(2)
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Number(3),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldAddReducibleElements()
    {
        $machine = new Machine(
            new Add(
                new Add(
                    new Number(1),
                    new Number(2)
                ),
                new Add(
                    new Number(3),
                    new Number(4)
                )
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Number(10),
            $actual
        );
    }


    /**
     * @test
     */
    public function shouldMultiplyNonReducibleElements()
    {
        $machine = new Machine(
            new Multiply(
                new Number(2),
                new Number(3)
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Number(6),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldMultiplyReducibleElements()
    {
        $machine = new Machine(
            new Multiply(
                new Multiply(
                    new Number(1),
                    new Number(2)
                ),
                new Multiply(
                    new Number(3),
                    new Number(4)
                )
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Number(24),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldDetermineLessThanForNonReducibleElements()
    {
        $machine = new Machine(
            new LessThan(
                new Number(2),
                new Number(3)
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Boolean(true),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldDetermineLessThanForReducibleElements()
    {
        $machine = new Machine(
            new LessThan(
                new Add(
                    new Number(2),
                    new Number(1)
                ),
                new Multiply(
                    new Number(2),
                    new Number(3)
                )
            )
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            new Boolean(true),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldReduceVariableWithNonReducibleElement()
    {
        $testValue = new Number(7);

        $machine = new Machine(
            new GetVariable('foo'),
            [ 'foo' => $testValue]
        );

        list($actual) = $machine->run();

        $this->assertEquals(
            $testValue,
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldReduceVariableWithReducibleElement()
    {
        $machine = new Machine(
            new GetVariable('bar'),
            [ 'bar' =>
                new Add(
                    new Number(3),
                    new Number(9)
                )
            ]
        );

        list($actual) = $machine->run();
        $this->assertEquals(
            new Number(12),
            $actual
        );
    }

    /**
     * @test
     */
    public function shouldAssignNonReducibleElement()
    {
        $testLabel = 'baz';
        $testValue = new Number(3);

        $machine = new Machine(
            new AssignVariable($testLabel, $testValue)
        );

        list($actual, $environment) = $machine->run();

        $this->assertEquals($testValue, $environment[$testLabel]);
    }

    /**
     * @test
     */
    public function shouldReduceOnAssign()
    {
        $testLabel = 'baz';
        $testExpression = new Add(
            new Number(1),
            new Number(2)
        );

        $machine = new Machine(
            new AssignVariable($testLabel, $testExpression)
        );

        list($actual, $environment) = $machine->run();

        $this->assertEquals(new Number(3), $environment[$testLabel]);
    }
}
