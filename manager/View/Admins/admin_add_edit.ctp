<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
?>
<script>
$(document).ready(function() {
    $("#AdminNoOfTables, #AdminNoOfTakeoutTables, #AdminNoOfWaitingTables").change(function() {
        var name = $(this).attr("alt");
        $("#submit_button").attr("disabled", 'disabled')
        $(".main-content").addClass('load2 csspinner');
        window.location = "<?php echo $this->Html->url(array('controller'=>'admins', 'action'=>'updatetable', 'admin'=>true, $this->request->data['Admin']['id'])) ?>?table="+$(this).val()+"&name="+name
    })

    $("#AdminNoOfTables, #AdminTax, #AdminNoOfTakeoutTables, #AdminNoOfWaitingTables").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || ((e.keyCode < 48) || (e.keyCode > 57))) && ((e.keyCode < 96) || (e.keyCode > 105))) {
            e.preventDefault();
        }
    });
    })
</script>
<div id="app">
    <!-- sidebar -->
    <?php echo $this->element('sidebar'); ?>
    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <?php echo $this->element('header'); ?>
        <!-- end: TOP NAVBAR -->
        <div class="main-content" >
            <div class="wrap-content container" id="container">
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">
                            <?php if($id == $this->Session->read('Admin.id')){
                                echo 'Update Profile';
                            }else{
                                echo ('' == $id) ? 'Add' : 'Edit' . ' Restaurant Manager';
                            } ?>
                            </h1>
                        </div>                        
                    </div>

                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">   
                            <?php echo $this->Form->create('Admin', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                                echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); 
                            ?>
                            <div class="row">
                                <h4 style="margin-left: 13px;">
                                    Restaurant Details
                                </h4>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Restaurant Name<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('restaurant_name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">HST Number</label>
                                        <?php echo $this->Form->input('hst_number', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">First Name</label>
                                        <?php echo $this->Form->input('firstname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Last Name</label>
                                        <?php echo $this->Form->input('lastname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>

                                <!-- location panel goes here -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Address</label>
                                        <?php echo $this->Form->input('address', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <?php echo $this->Form->input('city', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Province</label>
                                        <?php echo $this->Form->input('province', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Zip code</label>
                                        <?php echo $this->Form->input('zipcode', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                <!-- location end here -->


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Online StoreID and Phone (ID-number)</label>
                                        <?php echo $this->Form->input('mobile_no', array('type' => 'text', 'maxlength' => '20', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>

                                <h4 style="margin-left: 13px;">
                                    Customization
                                </h4>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Wechat Dinein Order Goes to Kitchen Direcctly</label> 
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Goes to Kitchen Direcctly'
                                            );
                                        echo $this->Form->input('net_order_kitchen', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Wechat Takout Order Goes to Kitchen Direcctly</label> 
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Goes to Kitchen Direcctly'
                                            );
                                        echo $this->Form->input('net_takeout_kitchen', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Wechat New Order Voice Prompt</label> 
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Yes'
                                            );
                                        echo $this->Form->input('net_order_voice', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Touch Sound on Touch Screen</label> 
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'Off',
                                            '1'=>'On'
                                            );
                                        echo $this->Form->input('touch_screen_sound', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>


                                <?php if($id != $this->Session->read('Admin.id')){ ?>
                                    <!--div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Status</label>
                                            <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        </div>
                                    </div-->

                                    <div class="clearfix"></div>

                                    <?php if('' == $id){ ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Password<span class="symbol required"></span></label>
                                                <?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Confirm Password<span class="symbol required"></span></label>
                                                <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                    <?php } ?>

                                <?php } ?>

                                <!-- <h4 style="margin-left: 13px; margin-top: 28px;">
                                    Kitchen Printer Details
                                </h4> -->
                                <!-- Modified by Yishou Liao @ Dec 08 2016 -->
                                <!--
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label class="control-label">Kitchen Printer IP </label> 
                                        <?php //echo $this->Form->input('printer_ip', array('type' => 'text',  'class' => 'form-control', 'label' => false, 'div' => false, 'required' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                -->
                                <!-- End @ Dec 08 2016 -->


                                <!--Writing by Mr.Sao @ May 17 2018-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Kitchen Printer ID </label> 
                                        <?php echo $this->Form->input('kitchen_printer_device', array('type' => 'text',  'class' => 'form-control', 'label' => false, 'required' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <!-- Modified by Yishou Liao @ Dec 08 2016 -->
                                <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Service Printer IP</label> 
                                        <?php //echo $this->Form->input('service_printer_ip', array('type' => 'text',  'class' => 'form-control ', 'label' => false, 'div' => false, 'required' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                -->
                                <!-- End @ Dec 08 2016 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Service Printer ID </label> 
                                        <?php echo $this->Form->input('service_printer_device', array('type' => 'text',  'class' => 'form-control ', 'label' => false, 'required' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Kitchen Printer cuts for each cousine</label>
                                        <?php 

                                        $printerCut = array(
                                            '0'=>'No',
                                            '1'=>'Cut every cousine'
                                            );
                                        echo $this->Form->input('singlecut', array('options' => $printerCut, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tax <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('tax', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Default Tip Rate</label>
                                        <?php echo $this->Form->input('default_tip_rate', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tip Rate After Tax</label>
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Yes'
                                            );
                                        echo $this->Form->input('default_tip_after_tax', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Price Display On Main Page</label>
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Yes'
                                        	);
                                        echo $this->Form->input('main_page_show_price', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Show 2nd Language</label>
                                        <?php 
                                        $tmpArr = array(
                                            '0'=>'No',
                                            '1'=>'Yes'
                                        	);
                                        echo $this->Form->input('show2nd', array('options' => $tmpArr, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                 <h4 style="margin-left: 13px; margin-top: 28px;">
                                    Dinein Tables
                                </h4>   

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">No Of Dinein Tables <span class="symbol required"></span></label>
                                        <?php
                                        $options = [];
                                        for($i = 1; $i <= 50; $i++)
                                            $options[$i] = $i;
                                        echo $this->Form->input('no_of_tables', array(/*'options'=>$options,*/ 'type' => 'text', 'maxlength' => '3', 'class' =>'form-control', 'div' => false, 'label' => false, 'alt'=>'no_of_tables','default'=>'Please Select',  'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:10px">
                                    <div class="form-group">
                                        <label class="control-label">Size For Dinein Table</label>
                                        <div class="clear"></div>
                                        <?php
                                            $tables = explode(",", @$this->request->data['Admin']['table_size']);
                                            for($i = 1; $i <= $this->request->data['Admin']['no_of_tables']; $i++) {
                                                ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?php
                                                        echo $this->Form->input('table_size.', array('multiple'=>'multiple', 'value'=>@$tables[$i-1], 'type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => "Size for Table".$i, 'required' => true));
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                         ?>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                 <h4 style="margin-left: 13px; margin-top: 28px;">
                                    Takeout Tables
                                </h4>   

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">No Of Takeout Tables <span class="symbol required"></span></label>
                                        <?php
                                        $options = [];
                                        for($i = 1; $i <= 50; $i++)
                                            $options[$i] = $i;
                                        echo $this->Form->input('no_of_takeout_tables', array(/*'options'=>$options,*/ 'type' => 'text', 'maxlength' => '3', 'class' =>'form-control', 'div' => false, 'alt'=>'no_of_takeout_tables', 'label' => false,  'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:10px">
                                    <div class="form-group">
                                        <label class="control-label">Size For TakeOut Table</label>
                                        <div class="clear"></div>
                                        <?php
                                            $tables = explode(",", @$this->request->data['Admin']['takeout_table_size']);
                                            for($i = 1; $i <= $this->request->data['Admin']['no_of_takeout_tables']; $i++) {
                                                ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?php
                                                        echo $this->Form->input('takeout_table_size.', array('multiple'=>'multiple', 'value'=>@$tables[$i-1], 'type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => "Size for Table".$i, 'required' => true));
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                         ?>
                                    </div>
                                </div>


                                <div class="clearfix"></div>
                                 <h4 style="margin-left: 13px; margin-top: 28px;">
                                    Waiting Tables
                                </h4>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">No Of Waiting Tables <span class="symbol required"></span></label>
                                        <?php
                                        $options = [];
                                        for($i = 1; $i <= 50; $i++)
                                            $options[$i] = $i;
                                        echo $this->Form->input('no_of_waiting_tables', array(/*'options'=>$options,*/ 'type' => 'text', 'maxlength' => '3', 'class' =>'form-control', 'alt'=>'no_of_waiting_tables', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top:10px">
                                    <div class="form-group">
                                        <label class="control-label">Size For Waiting Table</label>
                                        <div class="clear"></div>
                                        <?php
                                            $tables = explode(",", @$this->request->data['Admin']['waiting_table_size']);
                                            for($i = 1; $i <= $this->request->data['Admin']['no_of_waiting_tables']; $i++) {
                                                ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?php
                                                        echo $this->Form->input('waiting_table_size.', array('multiple'=>'multiple', 'value'=>@$tables[$i-1], 'type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => "Size for Table".$i, 'required' => true));
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                         ?>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                 <h4 style="margin-left: 13px; margin-top: 28px;">
                                    Online Tables
                                </h4>  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">No Of Online Tables <span class="symbol required"></span></label>
                                        <?php
                                        $options = [];
                                        for($i = 1; $i <= 50; $i++)
                                            $options[$i] = $i;
                                        echo $this->Form->input('no_of_online_tables', array('type' => 'text', 'maxlength' => '3', 'class' =>'form-control', 'alt'=>'no_of_online_tables', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <span class="symbol required"></span>Required Fields
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">                                        
                                </div>
                                <div class="col-md-5">
                                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));

                                    if($id == $this->Session->read('Admin.id')){
                                        echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                                            array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true),
                                            array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                                        );
                                    }else{
                                        echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                                            array('plugin' => false,'controller' => 'admins','action' => 'users', 'admin' => true),
                                            array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                                        );
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
   <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->
</div>
