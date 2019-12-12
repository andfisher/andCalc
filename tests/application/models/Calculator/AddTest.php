<?php

require_once dirname(__FILE__) . '/../../../application/models/Calculator/Add.php';

class Model_CalculatorAddTest extends PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {}


    public function testAdditionResults()
    {
        $addition = new Model_Calculator_Add();

        $this->assertEquals($addition->execute(99, 28), 99 + 28);
        $this->assertEquals($addition->execute(88.2, 15.4), 103.6);
        $this->assertEquals($addition->execute(99, -100), -1);
    }

}

