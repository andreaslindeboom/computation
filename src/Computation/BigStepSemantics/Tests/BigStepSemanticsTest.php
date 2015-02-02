<?php
namespace Computation\BigStepSemantics\Tests;

use Computation\BigStepSemantics\Expressions\Add;
use Computation\BigStepSemantics\Expressions\Boolean;
use Computation\BigStepSemantics\Expressions\GetVariable;
use Computation\BigStepSemantics\Expressions\LessThan;
use Computation\BigStepSemantics\Expressions\Multiply;
use Computation\BigStepSemantics\Expressions\Number;
use Computation\BigStepSemantics\Statements\AssignVariable;
use Computation\BigStepSemantics\Statements\DoNothing;
use Computation\BigStepSemantics\Statements\IfStatement;
use Computation\BigStepSemantics\Statements\Sequence;
use Computation\BigStepSemantics\Statements\WhileLoop;

class BigStepSemanticsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function shouldAddNonReducibleElements()
    {
        $expression = new Add(
            new Number(1),
            new Number(2)
        );

        $actual = $expression->evaluate();

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
        $expression = new Add(
            new Add(
                new Number(1),
                new Number(2)
            ),
            new Add(
                new Number(3),
                new Number(4)
            )
        );

        $actual = $expression->evaluate();

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
        $expression = new Multiply(
            new Number(2),
            new Number(3)
        );

        $actual = $expression->evaluate();

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
        $expression = new Multiply(
            new Multiply(
                new Number(1),
                new Number(2)
            ),
            new Multiply(
                new Number(3),
                new Number(4)
            )
        );

        $actual = $expression->evaluate();

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
        $expression = new LessThan(
            new Number(2),
            new Number(3)
        );

        $actual = $expression->evaluate();

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
        $expression = new LessThan(
            new Add(
                new Number(2),
                new Number(1)
            ),
            new Multiply(
                new Number(2),
                new Number(3)
            )
        );

        $actual = $expression->evaluate();

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

        $expression = new GetVariable('foo');

        $actual = $expression->evaluate(['foo' => $testValue]);

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
        $expression = new GetVariable('bar');

        $actual = $expression->evaluate([
            'bar' =>
                new Add(
                    new Number(3),
                    new Number(9)
                )
        ]);

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

        $statement = new AssignVariable($testLabel, $testValue);

        $environment = $statement->evaluate([$testLabel => $testValue]);

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

        $statement = new AssignVariable($testLabel, $testExpression);

        $environment = $statement->evaluate([$testLabel => $testExpression]);

        $this->assertEquals(new Number(3), $environment[$testLabel]);
    }

    /**
     * @test
     */
    public function shouldReduceConsequenceIfConditionEqualsTrue()
    {
        $statement = new IfStatement(
                new Boolean(true),
                new AssignVariable(
                    'foo',
                    new Add(
                        new Number(1),
                        new Number(2)
                    )
                ),
                new DoNothing()
        );

        $environment = $statement->evaluate();

        $this->assertEquals(new Number(3), $environment['foo']);
    }

    /**
     * @test
     */
    public function shouldReduceAlternativeIfConditionEqualsFalse()
    {
        $statement = new IfStatement(
            new Boolean(false),
            new AssignVariable(
                'foo',
                new Add(
                    new Number(1),
                    new Number(2)
                )
            ),
            new DoNothing()
        );

        $environment = $statement->evaluate();

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

        $statement = new Sequence(
            new AssignVariable($testLabel1, $testNumber1),
            new AssignVariable($testLabel2, $testNumber2)
        );

        $environment = $statement->evaluate();

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

        $statement = new Sequence(
            new AssignVariable($testLabel1, $testNumber1),
            new AssignVariable($testLabel2, new Add(
                new GetVariable($testLabel1),
                $testNumber2
            ))
        );

        $environment = $statement->evaluate();

        $this->assertEquals($testNumber1, $environment[$testLabel1]);
        $this->assertEquals($testNumber3, $environment[$testLabel2]);
    }

    /**
     * @test
     */
    public function shouldDiscontinueLoopWhenConditionIsFalse()
    {
        $statement = new WhileLoop(
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
        );

        $environment = $statement->evaluate(['i' => new Number(0)]);

        $this->assertEquals(new Number(10), $environment['i']);
    }
}
