<?php
namespace Computation\SmallStepSemantics\Tests\Elements;

use Computation\SmallStepSemantics\Elements\Add;
use Computation\SmallStepSemantics\Elements\Boolean;
use Computation\SmallStepSemantics\Elements\Multiply;
use Computation\SmallStepSemantics\Elements\Number;
use Computation\SmallStepSemantics\Elements\GetVariable;
use Computation\SmallStepSemantics\Machine;
use Computation\SmallStepSemantics\Elements\LessThan;

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

        $this->assertEquals(
            new Number(3),
            $machine->run()
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

        $this->assertEquals(
            new Number(10),
            $machine->run()
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

        $this->assertEquals(
            $machine->run(),
            new Number(6)
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

        $this->assertEquals(
            new Number(24),
            $machine->run()
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

        $this->assertEquals(
            new Boolean(true),
            $machine->run()
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

        $this->assertEquals(
            new Boolean(true),
            $machine->run()
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

        $this->assertEquals(
            $testValue,
            $machine->run()
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

        $this->assertEquals(
            new Number(12),
            $machine->run()
        );
    }
}
