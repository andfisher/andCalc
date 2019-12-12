<?php

/**
 * @author Andrew Fisher
 */
class Model_Calculator_Operator_Factory
{
    const ADDITION = 'ADDITION';
    const SUBTRACT = 'SUBTRACT';
    const MULTIPLY = 'MULTIPLY';
    const DIVIDE = 'DIVIDE';
    
    public static function build($operator)
    {
        $classMap = [
            self::ADDITION  => 'Model_Calculator_Add',
            self::SUBTRACT  => 'Model_Calculator_Subtract',
            self::MULTIPLY  => 'Model_Calculator_Multiply',
            self::DIVIDE    => 'Model_Calculator_Divide',
        ];
        
        if (! isset($classMap[$operator])) {
            
            throw new Model_Calculator_Operator_Exception('Unsupported Operator', 1);
        }
        
        $class = $classMap[$operator];
        return new $class();
    }
}