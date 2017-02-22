<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;


class ApiController extends Controller {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function syncOrders() {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = NULL;
        $this->loadModel('Orders');

        $paras = $this->request->data;
        echo json_encode($paras);
    }

    public function syncCousine() {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = NULL;
        $this->loadModel('Orders');
    }

	// public function delegate($object, $command) {
    //     $this->layout = false;
    //     $this->autoRender = NULL;
	// 	$result = null;
	// 	try {
	// 		if ($this->request->is('post') || $this->request->is('put')) {
	// 		  $args = $this->request->data;
	// 		} else {
	// 		  $args = $this->request->query;
	// 		}
	// 		$component = Inflector::camelize($object);
	// 		// if ($component !== 'Access') {
	// 	    //   	$this->_validateAccess($args);
	// 	    // }
	// 		$this->{$component} = $this->Components->load($component);
	// 		$this->{$component}->initialize($this);
	// 		$action = Inflector::camelize($command);
	// 		$return = $this->{$component}->{$action}($args);
	// 		if ($this->{$component}->status === 'success') {
	// 		  $result = $this->_success($return);
	// 		} else {
	// 		  $result = $this->_fail($return);
	// 		}
	// 	} catch(Exception $e) {
	// 		$result = $this->_error($e->getMessage(), $e->getCode(), $result);
	// 	}
	//
	// 	$this->response->type('json');
	// 	$this->response->statusCode(200);
	// 	$this->response->body($result);
	// 	$this->response->send();
	// 	$this->_stop();
	// }
	//
	//
	// protected function _format($status, $response = array()) {
	//   $object = new stdClass();
	//   $object->status = $status;
	//   foreach ($response as $param => $value) {
	//     $object->{$param} = $value;
	//   }
	//   return json_encode($object);
	// }
	//
	// protected function _success($data = null) {
	//   return $this->_format('success', array('data' => $data));
	// }
	//
	// protected function _fail($data = null) {
	//   return $this->_format('fail', array('data' => $data));
	// }
	//
	// protected function _error($message = 'Unknown', $code = 0, $data = array()) {
	//   return $this->_format('error', array(
	//     'message' => $message,
	//     'code' => $code,
	//     'data' => $data
	//   ));
	// }
	//
	// protected function _validateAccess($args) {
	// 	$this->Access = $this->Components->load('Access');
	// 	if (!$this->Access->validate($args)) {
	// 		throw new ForbiddenException();
	// 	}
	// }
}

 ?>
