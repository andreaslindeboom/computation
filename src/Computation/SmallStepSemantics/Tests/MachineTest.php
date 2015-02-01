<?php
namespace Computation\SmallStepSemantics\Tests;

use Computation\SmallStepSemantics\Expressions\Add;
use Computation\SmallStepSemantics\Expressions\Boolean;
use Computation\SmallStepSemantics\Expressions\Conditional;
use Computation\SmallStepSemantics\Expressions\Multiply;
use Computation\SmallStepSemantics\Expressions\Number;
use Computation\SmallStepSemantics\Expressions\GetVariable;
use Computation\SmallStepSemantics\Expressions\LessThan;
use Computation\SmallStepSemantics\Machine;
use Computation\SmallStepSemantics\Statements\AssignVariable;
use Computation\SmallStepSemantics\Statements\DoNothing;
use Computation\SmallStepSemantics\Statements\Loop;
use Computation\SmallStepSemantics\Statements\Sequence;

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

    /**
     * @test
     */
    public function shouldReduceConsequenceIfConditionEqualsTrue()
    {
        $machine = new Machine(
            new Conditional(
                new Boolean(true),
                new AssignVariable(
                    'foo',
                    new Add(
                        new Number(1),
                        new Number(2)
                    )
                ),
                new DoNothing()
            )
        );

        list ($actual, $environment) = $machine->run();

        $this->assertEquals(new Number(3), $environment['foo']);
    }

    /**
     * @test
     */
    public function shouldReduceAlternativeIfConditionEqualsFalse()
    {
        $machine = new Machine(
            new Conditional(
                new Boolean(false),
                new AssignVariable(
                    'foo',
                    new Add(
                        new Number(1),
                        new Number(2)
                    )
                ),
                new DoNothing()
            )
        );

        list ($actual, $environment) = $machine->run();

        $this->assertEquals(false, isset($environment['foo']));
    }

    /**
     * @test
     */
    public function shouldAssignMultipleVariablesInSequence()
    {
        $testNumber1 = new Number(1);
        $testNumber2 = new Number(2);

        $testLabel1 = 'foo';
        $testLabel2 = 'bar';

        $machine = new Machine(
            new Sequence(
                new AssignVariable($testLabel1, $testNumber1),
                new AssignVariable($testLabel2, $testNumber2)
            )
        );

        list ($actual, $environment) = $machine->run();

        $this->assertEquals($testNumber1, $environment[$testLabel1]);
        $this->assertEquals($testNumber2, $environment[$testLabel2]);
    }

    /**
     * @test
     */
    public function shouldGetVariableSetInSameSequence()
    {
        $testNumber1 = new Number(1);
        $testNumber2 = new Number(2);
        $testNumber3 = new Number(3);

        $testLabel1 = 'foo';
        $testLabel2 = 'bar';

        $machine = new Machine(
            new Sequence(
                new AssignVariable($testLabel1, $testNumber1),
                new AssignVariable($testLabel2, new Add(
                    new GetVariable($testLabel1),
                    $testNumber2
                ))
            )
        );

        list ($actual, $environment) = $machine->run();

        $this->assertEquals($testNumber1, $environment[$testLabel1]);
        $this->assertEquals($testNumber3, $environment[$testLabel2]);
    }

    /**
     * @test
     */
    public function shouldDiscontinueLoopWhenConditionFalse()
    {
        $machine = new Machine(
            new Loop(
                new LessThan(
                    new GetVariable('i'),
                    new Number(10)
                ),
                new AssignVariable(
                    'i',
                    new Add(
                        new getVariable('i'),
                        new Number(1)
                    )
                )
            ),
            ['i' => new Number(0)]
        );

        list ($actual, $environment) = $machine->run();

        $this->assertEquals(new Number(10), $environment['i']);
    }
}
