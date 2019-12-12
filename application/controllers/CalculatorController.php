<?php
/**
 * @copyright Copyright (c) 2019, Andrew Fisher
 * @author Andrew Fisher
 */

class CalculatorController extends Zend_Controller_Action
{
	public function indexAction()
	{
        $this->form = Form_Calculator_Factory::build();
        
        if ($this->getRequest()->isPost()) {
			
			if ($this->form->isValid($this->_request->getPost())) { ;

				$a = $this->getRequest()->getPost('val1');
				$b = $this->getRequest()->getPost('val2');
				$operator = Model_Calculator_Operator_Factory::build($this->getRequest()->getPost('operator'));

				$calculator = new Model_Calculator($a, $operator, $b);

				try {
					$this->view->result = $calculator->calculate();
				} catch (Model_Calculator_Divide_Exception $e) {
					$this->view->errors = [
						[$e->getCode() => $e->getMessage()]
					];
				}
			} else {
				$this->view->errors = $this->form->getMessages();
			}
        }
        
		$this->view->form = $this->form;
		
		$this->render();
    }
}

// End of /application/controllers/CalculatorController.php