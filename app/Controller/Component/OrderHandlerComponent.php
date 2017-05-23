<?php
App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');
App::uses('PrintComponent', 'Controller/Component');


class OrderHandlerComponent extends Component {
    public $status = 'success';
    public $components = array('Paginator');

    public function __construct() {
        // register model
        $this->Admin = ClassRegistry::init('Admin');
        $this->Order = ClassRegistry::init('Order');
        $this->OrderLog  = ClassRegistry::init('OrderLog');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Log       = ClassRegistry::init('Log');
        $this->Category  = ClassRegistry::init('Category');
        $this->Cashier   = ClassRegistry::init('Cashier');
        $this->Cousine   = ClassRegistry::init('Cousine');
        $this->Extra     = ClassRegistry::init('Extra');        
    }

    public function addItem($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id', 'table', 'type', 'cashier_id']);

        // get parameters
        $item_id = $args['item_id'];
        $table = $args['table'];
        $type = $args['type'];
        $cashier_id = $args['cashier_id'];

        $admin_detail = $this->Cashier->find("first", array(
            'fields' => array('Admin.tax', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        // print_r($admin_detail);

        $tax_rate = $admin_detail['Admin']['tax']; // 13
        $restaurant_id = $admin_detail['Admin']['id'];
        // print_r($tax_rate);
        // print_r($restaurant_id);

        $CousineDetail = $this->Cousine->getCousineInfo($item_id);
        // print_r($CousineDetail);


        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $restaurant_id, 'Order.table_no' => $table, 'Order.is_completed' => 'N', 'Order.order_type' => $type )
                )
        );

        // print_r($Order_detail);

        if (empty($Order_detail)) {
            // to create a new order
            $order_id = $this->Order->insertOrder($restaurant_id, $cashier_id, $table, $type, $tax_rate);
            //return array('ret' => 0, 'message' => 'No order found!');
        } else {
            $order_id = $Order_detail['Order']['id'];
        }

        $query_str = "SELECT comb_num FROM cousines WHERE id = " . $item_id;
        $comb_num = $this->Cousine->query($query_str);
        $query_str = "SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A'";
        $extras_categories = $this->Order->query($query_str);
        if ($comb_num[0]['cousines']['comb_num'] == 0) {
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND extrascategories.extras_num = 0 ";
        }else{
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND (extrascategories.extras_num = 0 " . " OR extrascategories.id = " . $comb_num[0]['cousines']['comb_num'] . ")";
        }


        if ($CousineDetail['Cousine']['is_tax'] == 'Y') {
            $tax_amount = $tax_rate * $CousineDetail['Cousine']['price'] / 100;
        } else {
            $tax_amount = 0;
        }


        $comb_id = $this->Cousine->find("first", array(
                'conditions' => array('Cousine.id' => $item_id)
            ))['Cousine']['comb_num'];

        $order_item_id = $this->OrderItem->insertOrderItem($order_id, $item_id, $CousineDetail['Cousine']['en'], $CousineDetail['Cousine']['zh'], $CousineDetail['Cousine']['price'], $CousineDetail['Cousine']['category_id'], /*!empty($extras) ? json_encode($extras) : "",*/ $tax_rate, $tax_amount, 1, $comb_id);


        $this->Order->updateBillInfo($order_id);

        $json['order_item_id'] = $order_item_id;
        $json['comb_id'] = $comb_id;
        return json_encode($json);
    }


    public function removeItem($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'order_no', 'cashier_id']);

        // get all params
        $item_id_list = $args['item_id_list'];
        $order_no = $args['order_no'];
        $cashier_id = $args['cashier_id'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($cashier_id);
        
        $PrintC = new PrintComponent();

        //PrintComponent::printKitchenRemoveItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));
        $PrintC->printKitchenRemoveItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));


        foreach ($item_id_list as $item_id) {
            // delete all item in order_item table
            $data['id'] = $item_id;
            $this->OrderItem->delete($data);
        }

        // update order amount
        $this->Order->updateBillInfo($order_id);
        
        return array('ret' => 1, 'message' => 'Complete!');

    }


    public function changePrice($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'table', 'type', 'order_no', 'price']);
        // get all params
        $item_id_list = $args['item_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $order_no = $args['order_no'];
        $price = $args['price'];

        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id
                            )
                        )
                );
                
            if(!$itemDetail){
               return array('ret' => 0, 'message' => "Error order_items id: $item_id !");
            }
                
            $itemDetail['OrderItem']['price'] = $price;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }

        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        
        $this->Order->updateBillInfo($order_id);

        return array('ret' => 1, 'message' => 'Complete!');
      
    }
    

    public function changeQuantity($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'table', 'type', 'order_no', 'quantity']);
        // get all params
        $item_id_list = $args['item_id_list'];
        $quantity = $args['quantity'];
        $table = $args['table'];
        $type = $args['type'];
        $order_no = $args['order_no'];

        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id
                            )
                        )
                );
            
            if(!$itemDetail){
               return array('ret' => 0, 'message' => "Error order_items id: $item_id !");
            }
            
            $itemDetail['OrderItem']['qty'] = $quantity;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }

        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $this->Order->updateBillInfo($order_id);
        
        return array('ret' => 1, 'message' => 'Complete!');

    }


    public function takeout($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list']);

        $item_id_list = $args['item_id_list'];
        
        // $order_no = $args['order_no'];

        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            
            if(!$item_detail){
            	return array('ret' => 0, 'message' => 'id '.$item_id.' not exist!');
            } 
            
            /*
            // print_r($item_detail);
            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            
            if ($is_print == 'Y') {
                if ($printer == 'K') {
                    // send to kitchen
                    echo $printer;
                } else if ($printer == 'C') {
                    // send to front
                    echo $printer;
                }
                echo $is_print;
            } // else do nothing
            */


            // set all item in order_item table as is_takeout 'Y'            
            if ($item_detail[0]['order_items']['is_takeout'] == 'Y') {
               //$update_para['is_takeout'] = 'N'; // revert is_takeout flag
            } else if ($item_detail[0]['order_items']['is_takeout'] == 'N') {
                $update_para['is_takeout'] = 'Y';
            }

            $update_para['id'] = $item_id;
            $this->OrderItem->save($update_para, false);
        }
        
        return array('ret' => 1, 'message' => 'Complete!');
    }


    public function batchAddExtras($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'extra_id_list', 'table', 'type', 'special', 'cashier_id']);

        $selected_item_id_list = $args['item_id_list'];
        $selected_extras_id_list = $args['extra_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $special = $args['special'];
        $cashier_id = $args['cashier_id'];

        // get cashier details
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        $extras_amount = 0;

        $selected_extras_list = [];
        foreach ($selected_extras_id_list as $extra_id) {
            $extra_details = $this->Extra->find("first", array(
                    "fields" => array('Extra.id', 'Extra.price', 'Extra.name_zh', 'Extra.category_id'),
                    'conditions' => array('Extra.id' => $extra_id)
                ));
            $temp_data = array(
                    'id' => $extra_details['Extra']['id'],
                    'price' => $extra_details['Extra']['price'],
                    'name' => $extra_details['Extra']['name_zh'],
                    'category_id' => $extra_details['Extra']['category_id']
                );
            array_push($selected_extras_list, $temp_data);
        }
        // echo json_encode($selected_extras_list);

        foreach ($selected_item_id_list as $item_id) {
            $item_detail = $this->OrderItem->find("first", array(
                'fields' => array('OrderItem.id', 'OrderItem.extras_amount', 'OrderItem.selected_extras'),
                'conditions' => array('OrderItem.id' => $item_id)
                    )
            );

            if (empty($item_detail['OrderItem']['selected_extras'])) {
                $item_detail['OrderItem']['selected_extras'] = json_encode($selected_extras_list);
            } else {
                $item_detail['OrderItem']['selected_extras'] = json_decode($item_detail['OrderItem']['selected_extras'], true);
                $item_detail['OrderItem']['selected_extras'] = json_encode(array_merge($item_detail['OrderItem']['selected_extras'], $selected_extras_list));
            }

            if (!empty($special)) {
                $item_detail['OrderItem']['special_instruction'] = $special;
            }


            $this->OrderItem->save($item_detail, false);

            // update extra amount will also incur the updateBillInfo() function
            $this->OrderItem->updateExtraAmount($item_id);

        }
    }


    public function addExtras($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id', 'extra_id_list', 'table', 'type', 'special', 'cashier_id']);

        $item_id = $args['item_id'];
        // selected_extras_id_list maybe empty
        $selected_extras_id_list = $args['extra_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $special = $args['special'];
        $cashier_id = $args['cashier_id'];

        // get cashier details
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        $extras_amount = 0;

        $selected_extras_list = [];
        foreach ($selected_extras_id_list as $extra_id) {
            $extra_details = $this->Extra->find("first", array(
                    "fields" => array('Extra.id', 'Extra.price', 'Extra.name_zh', 'Extra.category_id'),
                    'conditions' => array('Extra.id' => $extra_id)
                ));
            $temp_data = array(
                    'id' => $extra_details['Extra']['id'],
                    'price' => $extra_details['Extra']['price'],
                    'name' => $extra_details['Extra']['name_zh'],
                    'category_id' => $extra_details['Extra']['category_id']
                );
            array_push($selected_extras_list, $temp_data);
        }
        // echo json_encode($selected_extras_list);



        $item_detail = $this->OrderItem->find("first", array(
            'recursive' => -1,
            'fields' => array('OrderItem.id', 'OrderItem.extras_amount', 'OrderItem.selected_extras'),
            'conditions' => array('OrderItem.id' => $item_id)
                )
        );

        $item_detail['OrderItem']['selected_extras'] = json_encode($selected_extras_list);
        $item_detail['OrderItem']['special_instruction'] = $special;

        $this->OrderItem->save($item_detail, false);

        // update extra amount will also incur the updateBillInfo() function
        $this->OrderItem->updateExtraAmount($item_id);
    }

    public function tableHistory($args) {

        ApiHelperComponent::verifyRequiredParams($args, ['restaurant_id','table']);

        $restaurant_id = $args['restaurant_id'];
        $table = $args['table'];

        $conditions = array('Order.cashier_id' => $restaurant_id,
            'Order.table_no' => $table,
            'Order.is_completed' => 'Y',
            'Order.order_type' => 'D',
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        
        if (empty($Order_detail)) {
        	  $json['ret'] = 0;
        	  $json['message'] = "Sorry, there is no table history for today.";
            return json_encode($json);
        }
/*
        $this->paginate = array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Order.created' => 'desc')
        );

        $Order_detail = $this->paginate('Order');
*/
        $today = date('Y-m-d H:i', strtotime($Order_detail[0]['Order']['created']));
        
        $json['ret'] = 1;
        $json['Order_detail'] = $Order_detail;
        $json['table_no'] = $table;
        $json['today'] = $today;
        return json_encode($json);
    }


    public function makeavailable($args) {

	  	  ApiHelperComponent::verifyRequiredParams($args, ['order_no']);
	  	  	  	  
        $order_no = $args['order_no'];     
        
        $order_detail = $this->Order->find('first', array(
                 'recursive' => -1,
                 'conditions' => array(
                 'order_no' => $order_no
              )
        ));
                

        // update order
        try{
          $this->Order->updateAll(array('table_status'=>"'V'",'is_completed' => "'Y'"), array('Order.order_no' => $order_no));
        }catch(Exception $e){
        	return array('ret' => 0, 'message' => $e->getMessage() );
        }

        $logArr = array('cashier_id' => $args['cashier_id'], 'admin_id' => $order_detail['Order']['cashier_id'],'operation'=>'Void(makeavailable)', 'logs' => json_encode($order_detail));
        $this->Log->save($logArr);
        
        return array('ret' => 1, 'message' => 'Complete!');             
    }


	  public function moveOrder($args) {
	  	
	  	ApiHelperComponent::verifyRequiredParams($args, ['type', 'table', 'order_no']);
	  	$type  = $args['type'];
      $table = $args['table'];
      $order_no = $args['order_no'];
    
      $Order_detail = $this->Order->find("first", array(
          'fields' => array('Order.cashier_id', 'Order.table_no', 'Order.order_type', 'Order.phone'),
          'conditions' => array('Order.order_no' => $order_no),
          'recursive' => false
              )
      );        

      $restaurant_id = $Order_detail['Order']['cashier_id'];
      $old_type      = $Order_detail['Order']['order_type'];
      $old_table     = $Order_detail['Order']['table_no'];
      $phone         = $Order_detail['Order']['phone'];
      
      //print kitchen notification when change table(not from online table)
      if($old_type != 'L'){        	
        $printerName = $this->Admin->getKitchenPrinterName($restaurant_id);
        $print = new PrintLib();
        $print->printKitchenChangeTable($order_no, $table, $type, $old_table,$old_type, $printerName,true,$phone);
      }

 		  	
	  	/* 换桌时不修改订单号
	  	//modify order_no with new table and type
	  	//online orders 的编码规则和pos系统里面不一样
	  	if(strpos($order_no ,"-") !== FALSE){
	  		$order_no = $type.$table.substr($order_no,strpos($order_no,'-'));
	  	}else{
	  		$order_no = $type.$table.substr($order_no,-10);
	  	}
	  	*/	  
	  		
      // update new table information to database 	  					
      $this->Order->updateAll( array('table_no' => $table, 'order_type' =>"'$type'") , array('Order.order_no' => $order_no));
    
	  	return $this->Order->find('first', array(
	  					'conditions' => array('Order.order_no' => $order_no)
	    ));
	    
	  }
    
    
    public function editPhone($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['restaurant_id', 'order_no', 'phone']);
    
        $restaurant_id = $args['restaurant_id'];
        $order_no = $args['order_no'];
        $phone    = $args['phone'];
        
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        if(!$order_id) return array('ret' => 0, 'message' => "Order $order_no not exist!");
        
        $this->Order->id = $order_id;
        $ret=$this->Order->saveField('phone', $phone);
       
        if($ret) return array('ret' => 1, 'message' => 'Add successfully.');
        else return array('ret' => 0, 'message' => 'Fail to update order!');        
    }


}

?>
