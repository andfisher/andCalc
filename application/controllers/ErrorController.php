<?php
class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:

                // 404 error -- controller or action not found
				//$this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
			case 'EXCEPTION_ACCESS_DENIED':
				$this->getResponse()->setHttpResponseCode(403);
                $this->view->message = 'Access denied!';
				break;
            default:
                // application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
				break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
		
    }
}

// End of ../application/controllers/ErrorController.php