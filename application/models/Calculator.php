<?php
/**
 * @author Andrew Fisher
 */
class Model_Calculator
{
    private $a;
    private $b;
    private $operator;
    
    public function __construct($a, Model_Calculator_Operator_Interface $o, $b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $o;
    }
    
    public function calculate()
    {
        return $this->operator->execute($this->a, $this->b);
    }
}

