<?php

/**
 * @author Andrew Fisher
 */
class Model_Calculator_Divide implements Model_Calculator_Operator_Interface
{
	/**
	 * @throws Model_Calculator_Divide_Exception
	 */
    public function execute($a, $b)
    {
		if ((int) $a === 0 || (int) $b === 0) {
			throw new Model_Calculator_Divide_Exception(
				'Error: Division by zero',
				Model_Calculator_Divide_Exception::ERROR_DIVISION_BY_ZERO);
		}
        return $a / $b;
    }
}