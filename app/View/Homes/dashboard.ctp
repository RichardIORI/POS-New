<body>

        <?php echo $this->Html->css(array('dashboard'));  ?>

        <div class="clearfix homepage col-md-12 col-sm-12 col-xs-12">
            <?php echo $this->Session->flash(); ?>
            <div class="clearfix col-md-8 col-sm-8 col-xs-8" id="dine-in-component">
                  <!--   <div class="col-md-12 col-sm-12 col-xs-12" id="dine-in-title">
                      Dine in <br/> 堂食
                  </div> -->
                    <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">


                        <ul class="dine_ul" style="height:auto; overflow:auto; min-height: 580px; padding:0">
                            <!-- <?php print_r($tables['Admin']);?>  -->

                        	<?php
                            $dine_table = @explode(",", $tables['Admin']['table_size']);
                            $dine_table_order = @$tables['Admin']['table_order']?@json_decode($tables['Admin']['table_order'], true):array();
                        	for($i = 1; $i <= $tables['Admin']['no_of_tables']; $i++) {
                        	?>
                            <li class="clearfix dine_li" style="<?php echo @$dine_table_order[$i-1] ?>">
                                <div class="dropdown-menu dropdown-overlay">
                            	<ul class="dine-tables">
                                    <!--<div class="arrow"></div>-->
                                    <li class="dropdown-title"><?php echo __('Dine In'); ?><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></li>
                                    <li <?php if(@$dinein_tables_status[$i] == 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] <> 'P')echo $this->Html->url(array('controller'=>'order', 'action'=>'index', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)"; ?>"><?php echo __('Order'); ?></a></li>

                                    <li class="dropdown-submenu <?php if(!@$dinein_tables_status[$i])echo 'disabled';?>">
                                        <a class="test" tabindex="-1" href="<?php if(@$dinein_tables_status[$i])echo $this->Html->url(array('controller'=>'homes', 'action'=>'changetable', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>"><?php echo __('Change Table'); ?></a>
                                    	<?php if(@$dinein_tables_status[$i]) {;?>
                                            <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
                                                <a class="close-btn" href="javascript:void(0)">X</a>
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('DINE IN'); ?></div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if (!@$orders_no[$t]['D'] and $t <> $i) {
                                                        ?>

                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['D'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('TAKE OUT'); ?> </div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                    if (!@$orders_no[$t]['T']) {
                                                        ?>
                                                        <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['D'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('WAITING'); ?></div>
                                                <?php for($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                if(!@$orders_no[$t]['W']){  ?>
                                                   <a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$orders_no[$i]['D']));?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                <?php } }?>
                                                </div>
                                            </ul>
                                        <?php }?>
                                    </li>
                                    <li <?php if(@$dinein_tables_status[$i] <> 'N' and @$dinein_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] == 'N' OR @$dinein_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'pay', 'action'=>'index', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>"><?php echo __('Pay'); ?></a></li>

                                    <li <?php if(@$dinein_tables_status[$i] <> 'N')echo 'class="disabled"';?>><a tabindex="-1" href="javascript:makeavailable('<?php if(@$dinein_tables_status[$i] <> 'A')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'D', 'order'=>@$orders_no[$i]['D']));?>');"><?php echo __('Clear Table'); ?></a></li>

                                    <!-- Modified by Yishou Liao @ Oct 13 2016. -->
                                <li <?php if (@$dinein_tables_status[$i] <> 'N' and @ $dinein_tables_status[$i] <> 'V') echo 'class="disabled"'; else echo 'class="dropdown-submenu bottom-submenu"' ?>>
                                    <a class="test" tabindex="-1" href="<?php
                                    if (@$dinein_tables_status[$i])
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'D'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>"><?php echo __('Merge Bill'); ?></a>
                                       <?php
                                       if (@$dinein_tables_status[$i]) {
                                           ;
                                           ?>
                                        <ul class="dropdown-menu">
                                            <div class="clearfix">
                                                <a class="close-btn" href="javascript:void(0)">X</a>
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable timetable-title"><?php echo __('Merge Bill'); ?></div>
                                                <?php
                                                $dinein_tables_keys = array_keys($dinein_tables_status);
                                                for ($t = 0; $t < count(@$dinein_tables_status); $t++) {
                                                    if (@$dinein_tables_status[$dinein_tables_keys[$t]] == "N" && $dinein_tables_keys[$t] != $i) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable merge-checkbox"><input type="checkbox" value = "<?php echo $dinein_tables_keys[$t]; ?>" id="mergetable[]" name= "mergetable[]"> <?php echo $dinein_tables_keys[$t]; ?></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <!-- <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"> -->
                                                <!-- modified by Yu Dec 16, 2016 -->
                                                    <input type="button" onclick="mergebill(<?php echo $i ?>,'<?php
                                                    //Modified by Yishou Liao @ Oct 16 2016.
                                                     if(@$dinein_tables_status[$i] == 'N' OR @$dinein_tables_status[$i] == 'V'){
                                                         echo $this->Html->url(array('controller'=>'merge', 'action'=>'index', 'table'=>$i, 'tablemerge'=>"Merge_table",'type'=>'D'));
                                                     }else{
                                                         echo "javascript:void(0)";
                                                     };
                                                    //End.
                                                    ?>');" name="mergebill" id="mergebill" value="Okay" class="btn btn-primary btn-lg" style="margin-top:10px">
                                                <!-- </div> -->
                                            </div>
                                        </ul>
                                    <?php } ?>
                                </li>

                                <li <?php if (@$dinein_tables_status[$i] <> 'N' and @ $dinein_tables_status[$i] <> 'V') echo 'class="disabled"'; else echo 'class=" bottom-submenu"' ?>><a class="test" tabindex="-1" href="<?php
                                    echo $this->Html->url(array('controller'=>'split', 'action'=>'index', 'table'=>$i, 'type'=>'D', 'split_method' =>'1'));
                                    ?>"><?php echo __('Split Bill'); ?></a>
                                </li>
                                <!-- End. -->

                                    <li><a tabindex="-1" href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'tableHistory', 'table_no'=>$i)); ?>"><?php echo __('History'); ?></a></li>
                            	</ul>
                                </div>
                                <div class="<?php if(isset($dinein_tables_status[$i])) echo $colors[$dinein_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
                                    <div class="number-txt for-dine"><?php echo __('Dine'); ?> <?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>

                                    <!-- <div class="order_no_box <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
                                    	<?php
                                	 	if(!@$dinein_tables_status[$i])
                                    		echo "&nbsp;";
                                		else
                                			echo @$orders_no[$i]['D'];
                                    	?>
                                    </div> -->
                                    <div class="order_total_box <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
                                        <?php
                                        if(!@$dinein_tables_status[$i])
                                            echo "&nbsp;";
                                        else
                                            echo '$' . @round($orders_total[$orders_no[$i]['D']], 2);
                                        ?>
                                    </div>
                                    <div class="txt12 text-center <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>"><?php if(@$dinein_tables_status[$i]) {  ?> <?php echo @$orders_time[$i]['D']?date("H:i", strtotime(@$orders_time[$i]['D'])):"" ?><?php }?>
                                </div>
                            </li>
                            <?php }?>
                        </ul>
                    </div>

            </div>

            <div class="col-md-2 col-sm-2 col-xs-2" id="take-out-component">
                <div class="col-md-12 col-sm-12 col-xs-12" id="take-out-title">
                        <?php echo __('Takeout Tables'); ?> <br/>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">


                    <ul>
                        <?php
                        $takeout_tables = @explode(",", $tables['Admin']['takeout_table_size']);
                        for ($i = 1; $i <= $tables['Admin']['no_of_takeout_tables']; $i++) {
                            ?>
                            <li class="clearfix">
                                <div class="dropdown-menu dropdown-overlay">
                                <ul class="takeout-tables">
                                    <!--<div class="arrow"></div>-->
                                    <li class="dropdown-title"><?php echo __('Takeout'); ?><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></li>
                                    <li <?php if (@$takeway_tables_status[$i] == 'P') echo 'class="disabled"'; ?>><a tabindex="-1" href="<?php
                                        if (@$takeway_tables_status[$i] <> 'P')
                                            echo $this->Html->url(array('controller' => 'order', 'action' => 'index', 'table' => $i, 'type' => 'T'));
                                        else
                                            echo "javascript:void(0)";
                                        ?>"><?php echo __('Order'); ?></a></li>
                                    <li class="dropdown-submenu <?php if (!@$takeway_tables_status[$i]) echo 'disabled'; ?>">
                                        <a class="test" tabindex="-1" href="<?php
                                        if (@$takeway_tables_status[$i])
                                            echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'T'));
                                        else
                                            echo "javascript:void(0)";
                                        ?>"><?php echo __('Change Table'); ?></a>
                                           <?php
                                           if (@$takeway_tables_status[$i]) {
                                               ;
                                               ?>
                                            <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
                                                    <div class="left-arrow"></div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('DINE IN'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                        if (!@$orders_no[$t]['D']) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['T'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('TAKE OUT'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                        if (!@$orders_no[$t]['T'] and $t <> $i) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['T'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('WAITING'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                        if (!@$orders_no[$t]['W']) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'W', 'order_no' => @$orders_no[$i]['T'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                </ul>
    	                                        <?php }?>
    	                                    </li>
                                            <li <?php if(@$takeway_tables_status[$i] <> 'N' and @$takeway_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] == 'N' OR @$takeway_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'pay', 'action'=>'index', 'table'=>$i, 'type'=>'T')); else echo "javascript:void(0)";?>"> <?php echo __('Pay')?></a></li>
    	                                    <li <?php if(@$takeway_tables_status[$i] <> 'N')echo 'class="disabled"';?>><a tabindex="-1" href="javascript:makeavailable('<?php if(@$takeway_tables_status[$i] <> 'A')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'T', 'order'=>@$orders_no[$i]['T']));?>');"><?php echo __('Clear Table')?></a></li>

    		                        	</ul>
                                        </div>
    	                                <div class="<?php if(isset($takeway_tables_status[$i])) echo $colors[$takeway_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
    		                                <!-- <div class="takeout-txt">Takeout</div> -->
                                                <div class="number-txt for-dine">Out<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                                <?php
                                                if(@$takeway_tables_status[$i]) {
                                                ?>
    			                                <div class="order_no_box <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
    			                                	<?php
                                                        echo @$orders_no[$i]['T'];
    			                                	?>
    			                                </div>
    			                                <div class="txt12 text-center <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>"><?php echo @$orders_time[$i]['T']?date("H:i", strtotime(@$orders_time[$i]['T'])):"" ?></div>

                                                <?php }?>
    		                            </div>
    	                            </li>
                                <?php } ?>
                            </ul>
                        </div>
            </div>


            <div class="col-md-2 col-sm-2 col-xs-2" id="waiting-list-component">

                <div class="col-md-12 col-sm-12 col-xs-12" id="waiting-list-title">
                        <?php echo __('Waiting List'); ?>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                    <ul>
                        <?php
                        $wait_table = @explode(",", $tables['Admin']['waiting_table_size']);
                        for ($i = 1; $i <= $tables['Admin']['no_of_waiting_tables']; $i++) {
                            ?>
                            <li class="clearfix">
                                <div class="dropdown-menu dropdown-overlay">
                                <ul class="waiting-tables">
                                    <!--<div class="arrow"></div>-->
                                    <li class="dropdown-title"><?php echo __('Waiting List'); ?><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></li>
                                    <li <?php if (@$waiting_tables_status[$i] == 'P') echo 'class="disabled"'; ?>><a tabindex="-1" href="<?php
                                        if (@$waiting_tables_status[$i] <> 'P')
                                            echo $this->Html->url(array('controller' => 'order', 'action' => 'index', 'table' => $i, 'type' => 'W'));
                                        else
                                            echo "javascript:void(0)";
                                        ?>"><?php echo __('Order'); ?></a></li>

                                    <li class="dropdown-submenu <?php if (!@$waiting_tables_status[$i]) echo 'disabled'; ?>">
                                        <a class="test" tabindex="-1" href="<?php
                                        if (@$waiting_tables_status[$i])
                                            echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'W'));
                                        else
                                            echo "javascript:void(0)";
                                        ?>"><?php echo __('Change Table'); ?></a>
                                           <?php
                                           if (@$waiting_tables_status[$i]) {
                                               ;
                                               ?>
                                            <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
                                                    <div class="left-arrow"></div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"> <?php echo __('DINE IN'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                        if (!@$orders_no[$t]['D']) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['W'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('TAKE OUT'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                        if (!@$orders_no[$t]['T']) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['W'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('WAITING'); ?></div>
                                                    <?php
                                                    for ($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                        if (!@$orders_no[$t]['W'] and $t <> $i) {
                                                            ?>
                                                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'W', 'order_no' => @$orders_no[$i]['W'])); ?>"><div class="col-md-4 col-sm-4 col-xs-4 text-center timetable"><?php echo $t; ?></div></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                </ul>
                                            <?php }?>
                                        </li>
                                        <li <?php if(@$waiting_tables_status[$i] <> 'N' and @$waiting_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] == 'N' OR @$waiting_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'pay', 'action'=>'index', 'table'=>$i, 'type'=>'W')); else echo "javascript:void(0)";?>"><?php echo __('Pay'); ?></a></li>
                                        <li <?php if(@$waiting_tables_status[$i] <> 'N')echo 'class="disabled"';?>><a tabindex="-1" href="javascript:makeavailable('<?php if(@$waiting_tables_status[$i] <> 'A')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'W', 'order'=>@$orders_no[$i]['W']));?>');"><?php echo __('Clear'); ?></a></li>
                                    </ul>
                                    </div>
                                    <div class="<?php if(isset($waiting_tables_status[$i])) echo $colors[$waiting_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
                                        <div class="number-txt for-dine">Wait<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                        <div class="order_no_box <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
                                            <?php
                                            if(!@$waiting_tables_status[$i])
                                                echo "&nbsp;";
                                            else
                                                echo @$orders_no[$i]['W'];
                                            ?>
                                        </div>
                                        <div class="txt12 text-center <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
                                            <?php if(@$waiting_tables_status[$i]) {  ?> <?php echo @$orders_time[$i]['W']?date("H:i", strtotime(@$orders_time[$i]['W'])):""; } ?>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
            </div>

        </div>


                    <!-- Scroll buttons -->
                    <a href="#" class="scrollUp">Up</a>
                    <a href="#" class="scrollDown">Down</a>
        </div>


<div id="dialog" title="Please Enter Password" style="display:none" class="popPassword">
    <!-- <span>Please Enter Your Password</span> -->
    <div class="input-group input-group-lg">
        <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
        <input id="login-password" type="password"  class="EntPassword form-control" placeholder="password" aria-describedby="sizing-addon1"/>
    </div>

    <input type="hidden" id="url" value="" />
    <div class="form-group">
        <div class="col-sm-12 controls">
            <input class="btn btn-primary btn-lg enter" type="button" value="Enter" onclick="checkPassword('<?php echo $admin_passwd[0]['admins']['password']?>')"/>
        <input class="btn btn-secondary btn-lg cancel" type="button" value="Cancel" onclick="checkPasswordC()"/>
        </div>

    </div>
</div>
<?php echo $this->element('footer'); ?>

<script>
	$(document).ready(function () {

        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.scrollUp').fadeIn();
            } else {
                $('.scrollUp').fadeOut();
            }

            if ($(this).scrollTop() + $(this).height() >= $(document).height() - 100) {
                $('.scrollDown').fadeOut();
            } else {
                $('.scrollDown').fadeIn();
            }
        });

        $('.scrollUp').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 300);
            return false;
        });

        $('.scrollDown').click(function() {
            $('html, body').animate({ scrollTop: $(document).height() }, 300);
            return false;
        });

        $('.dropdown-submenu a.test').on("click", function (e) {
            var subMenu = $(this).next('ul');
            subMenu.toggle();
            if(subMenu.hasClass('sub-open'))
                subMenu.removeClass('sub-open');
            else
                subMenu.addClass('sub-open');
            e.stopPropagation();
            e.preventDefault();
        });

        $('.dropdown-menu a.close-btn').on("click", function (e) {
            var subMenu = $(this).closest('ul');
            subMenu.toggle();
            subMenu.removeClass('sub-open');
            e.stopPropagation();
            e.preventDefault();
        });

        $('.dropdown-toggle').on('click', function (e) {
            setTimeout(function () {
                var ddmenu = $('.open').find('.dropdown-menu');
                if (ddmenu) {
                    var position_x = ddmenu.offset().left;
                    var el_width = ddmenu.outerWidth();
                    var window_width = $(window).width();

                    if (position_x + el_width > window_width && !ddmenu.hasClass('dropdown-menu-right')) {
                        ddmenu.addClass('dropdown-menu-right');
                    }
                }
            }, 1);
        });
        $('.dropdown-submenu a.test').on('click', function (e) {
            setTimeout(function() {
                var ddmenu = $('.dropdown-submenu').find('ul.dropdown-menu.sub-open');
                if (ddmenu.offset()) {
                    var position_y = ddmenu.offset().top;
                    if (position_y < 0) {
                        ddmenu.css({top:0});
                    }
                }
            }, 1);

        });

        $('.merge-checkbox').on('click', function(e) {
            e.stopPropagation();
        });

        $('#admin-link').on('click', function(e) {
            e.preventDefault();
            var pass = prompt("Input Password","");
            pass = hex_md5(pass);
            if (pass == "<?php echo $admin_passwd[0]['admins']['password']?>") {
                window.location.assign('<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'index')) ?>');
            }
        })

        $('#print-today-all').on('click', function(e) {
        	e.preventDefault();
        	var pass = prompt("Input Password","");
        	pass = hex_md5(pass);
        	if (pass == "<?php echo $admin_passwd[0]['admins']['password']?>") {
				$.ajax({
					url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printTodayOrders')); ?>",
					method: "post",
					// async: false,
					success: function (html) {
						alert("Finished");
					},
					error: function (html) {
						alert("error");
					}
				});
        	} else {
            	alert("Wrong password");
        	}
		});

        $('#print-today-items').on('click', function(e) {
            e.preventDefault();
            var pass = prompt("Input Password","");
            pass = hex_md5(pass);
            if (pass == "<?php echo $admin_passwd[0]['admins']['password']?>") {
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printTodayItems')); ?>",
                    method: "post",
                    // async: false,
                    success: function (html) {
                        alert("Finished");
                    },
                    error: function (html) {
                        alert("error");
                    }
                });
            } else {
                alert("Wrong password");
            }
        })
	});

    $(window).load(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            $('.scrollDown').fadeOut();
        } else {
            $('.scrollDown').fadeIn();
        }
    });
</script>

</body>

<!-- Modified by Yishou Liao @ Oct 13 2016. -->
<script type="text/javascript">
    function mergebill(tableId) {
        var table_merge = "";
        $('input[name="mergetable[]"]:checked').each(function () {
           table_merge += $(this).val()+",";
            $(this).attr("checked",false);
        });
        table_merge = table_merge.substring(0,(table_merge.length-1));

        document.location = "../merge/index/table:"+tableId+"/tablemerge:"+table_merge+"/type:D";

    }

	//Modified by Yishou Liao @ Nov 18 2016.
	function makeavailable(url){
		$('#dialog').show();
		$(".EntPassword").val("");
		$('#url').val(url);
	}
	// modified by Yu Dec 15, 2016
    // move hide() inside
	function checkPassword(passwd){
		// $('#dialog').hide();
        var pwd_makeavailable = hex_md5($(".EntPassword").val());
		if (pwd_makeavailable == passwd){
            $('#dialog').hide();
			document.location = $('#url').val();;
		} else {
			// alert("Your password is incorrect!");
            $('.popPassword .input-group-addon').notify("Your password is incorrect!", {position: "top",
                className: "error"}
            );
		};
    }
	function checkPasswordC(){
		$('#dialog').hide();
	}
	//End.
</script>
<!-- End. -->
<script>
    $(document).ready(function () {
        // setTimeout(function(){
        //    window.location.reload(1);
        // }, 30000);
        if ($(window).width() <= 780) {
            $(".dine_ul, .dine_li").removeAttr("style");
            $('#dine-in-component').removeClass('col-md-8 col-sm-8 col-xs-8')
            $("#take-out-component, #waiting-list-component").removeClass('col-md-2 col-sm-2 col-xs-2');
            // $("").removeAttr("style");
        }
        $(window).resize(function () {
            if ($(window).width() <= 780) {
                $(".dine_ul, .dine_li").removeAttr("style");
                // $("").removeAttr("style");
                $('#dine-in-component').removeClass('col-md-8 col-sm-8 col-xs-8')
                $("#take-out-component, #waiting-list-component").removeClass('col-md-2 col-sm-2 col-xs-2');
            } else {
                $('#dine-in-component').addClass('col-md-8 col-sm-8 col-xs-8')
                $("#take-out-component, #waiting-list-component").addClass('col-md-2 col-sm-2 col-xs-2');
            }
        })

    });
</script>
