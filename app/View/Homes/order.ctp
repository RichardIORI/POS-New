<html>
<head>
    <?php echo $this->Html->css(array('order')); ?>
</head>
    

<body>

    <header class="product-header">

        <div class="home-logo">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
            </a>

            <div class="HomeText text-left">
                <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>">Home 主页</a>
                <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
            </div>

        </div>



        <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>

        <ul class="nav nav-tabs text-center">
            <?php
            if (!empty($records)) {
                foreach ($records as $key => $category) {
                    ?>
                    <li <?php if ($key == 0) echo "class='active'" ?>><a data-toggle="tab" href="#tab<?php echo $category['Category']['id']; ?>"><?php echo $category['Category']['eng_name'] . "<br/>" . $category['Category']['zh_name']; ?></a></li>
                    <?php
                }
            }
            ?>
        </ul>

        
    </header>
    <div class="clearfix cartwrap-wrap col-md-12 col-sm-12 col-xs-12">      
        <div class="col-md-9 col-sm-8 col-xs-12 home-link">
            <div class="cart-txt" id="order_no_display">
            <!-- Modified by Yishou Liao @ Dec 09 2016 -->
                Order 订单号 #<?php echo @$Order_detail['Order']['order_no']; ?>, Table 桌 #<?php echo $table; ?>
            <!-- End -->
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="searchwrap">
                <label for="search-input"><i class="fa fa-search" aria-hidden="true"></i></label>
                <a class="fa fa-times-circle-o search-clear" aria-hidden="true"></a>
                <input id="search-input" class="form-control input-lg" placeholder="Search 搜索">
            </div>
        </div>

    </div>


    <div class="clearfix cart-wrap col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-4 col-sm-5 col-xs-12 summary_box">
            <div class="clearfix marginB15 cashierbox" style="display:none">
                <div class="pull-left marginR5">
                    <?php if ($cashier_detail['Cashier']['image']) { ?>
                        <?php echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . CASHIER_IMAGE_PATH . $cashier_detail['Cashier']['image'] . "&h=60&w=60&&zc=4&Q=100", array('class' => 'img-circle img-responsive')); ?>
                    <?php } else { ?>
                        <?php echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . TIMB_PATH . 'no_image.jpg' . "&h=60&w=60&&zc=4&Q=100", array('class' => 'img-circle img-responsive')); ?>
                    <?php } ?>
                </div>
                <div class="pull-left marginL5 clearfix">
                    <div class="txt16 marginB5 marginT5"><?php echo ucfirst($cashier_detail['Cashier']['firstname']) . " " . $cashier_detail['Cashier']['lastname']; ?></div>
                    <div class="txt15"><?php echo str_pad($cashier_detail['Cashier']['id'], 4, 0, STR_PAD_LEFT); ?></div>
                </div>
            </div>
        </div>


        <div class="col-md-8 col-sm-7 col-xs-12 products-panel">
            <div class="tab-content">
                <!-- <?php print_r ($records); ?> -->
                <?php
                if (!empty($records)) {
                    $count = 0;
                    foreach ($records as $key => $category) {
                        $count++;
                        ?>
                        <div id="tab<?php echo $category['Category']['id']; ?>" class="tab-pane fade in <?php if ($key == 0) echo "active" ?>">
                            <div class="clearfix">
                                <div class="clearfix row productbox">
                                    <?php if (!empty($category['Cousine'])) { ?>
                                        <ul>
                                            <?php
                                            foreach ($category['Cousine'] as $items) {
                                                ?>
                                                <li class="col-md-3 col-sm-6 col-xs-6 add_items" alt="<?php echo $items['id']; ?>" title="Add to Cart">
                                                    <div class="item-wrapper">
                                                        <div class="clearfixrow">
                                                            <div class="dish-price">$<?php echo number_format($items['price'], 2); ?></div>
                                                            <div class="dish-title"><div class="name-title"><strong><?php echo $items['zh_name'] . "<br/>" . $items['eng_name']; ?></strong></div></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    } else {
                                        echo "<div class='noitems'>No Items Available</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>                
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12" id="button-group">
        <button id="send-to-kitchen-btn" class="btn btn-lg">Send to Kitchen</button>
        <button id="pay-btn" class="btn btn-lg">Pay</button>
        <button id="taste-btn" class="btn btn-lg">Taste</button>
        <button id="combo-btn" class="btn btn-lg">Combo</button>
        <button id="delete-btn" class="btn btn-lg">Delete</button>
        <button id="quantity-btn" class="btn btn-lg">Quantity</button>
        <button id="take-out-btn" class="btn btn-lg">Take Out</button> 
        <button id="add-discount-btn" class="btn btn-lg">Add Discount</button> 
    </div>

</body>
</html>




<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', 'jquery.kinetic.min.js', 'flowtype.js'));
echo $this->fetch('script');
?>
<script type="text/javascript">
    var Order_Item_Printer = Array(); //Modified by Oct 25 2016.

    /*$(document).on('click', ".add_items", function () {
        var item_id = $(this).attr("alt");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'additems')); ?>",
            method: "post",
            data: {item_id: item_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".order-summary-indent").scrollTop($(".order-summary-indent ul").height());


                $(".products-panel").removeClass('load1 csspinner');
				// Modiied by Yishou Liao @ Dec 12 2016
				$("#order_no_display").html("Order 订单号 #" + $("#Order_no").val() + ", Table 桌 #<?php echo $table; ?>");
				// End @ Dec 12 2016
				
                // Modified by Yishou Liao @ Oct 27 2016.
				Order_Item_Printer = Array();
				
				// Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
				//End
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
                ;
                //End.
				
				//Modified by Yishou Liao @ Dec 15 2016
				if ($("#show_extras_flag").val() ==  true) {
					$(".dropdown-toggle").trigger("click");
				};
				//End @ Dec 15 2016
            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        })
    })*/

    $(".add_items").on("click", function () {
        var item_id = $(this).attr("alt");
        var message = $("#Message").val();

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'addItem')); ?>",
            method: "post",
            data: {item_id: item_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".order-summary-indent").scrollTop($(".order-summary-indent ul").height());

                /*$('.less-title').flowtype({
                    // fontRatio : 15,
                    // minimum: 500,
                    minFont: 13,
                    maxFont: 20
                });*/


                $(".products-panel").removeClass('load1 csspinner');

                $("#order_no_display").html("Order 订单号 #" + $("#Order_no").val() + ", Table 桌 #<?php echo $table; ?>");
 
                Order_Item_Printer = Array();
                
                if ($('#Order_Item').val() != ""){
                    var arrtmp = $('#Order_Item').val().split("#");
                };

                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
                

                if ($("#show_extras_flag").val() ==  true) {
                    $(".dropdown-toggle").trigger("click");
                };

            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        })
    })

    $(document).on('click', ".close-link", function () {
        var item_id = $(this).attr("alt");
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'removeitem')); ?>",
            method: "post",
            data: {item_id: item_id, order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');

                // Modified by Yishou Liao @ Oct 27 2016.
				Order_Item_Printer = Array();
				// Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
				//End
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
                ;
                //End.
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    })

    $(document).on('click', ".waimai-link", function () {
        var item_id = $(this).attr("alt");
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'waimaiitem')); ?>",
            method: "post",
            data: {item_id: item_id, order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
				Order_Item_Printer = Array();
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    })

    $(document).on('click', ".add_extras", function () {
        var item_id = $(this).attr("item_id");
        var price = $(this).attr("price");
        var name = $(this).attr("name");
        var extra_id = $(this).attr("alt");
		//MOdified by Yishou Liao @ Dec 15 2016
		var category_id = $(this).attr("category_id");

		var numcomb = $("#numofcomb").val().split(",");
		var str_search = $("#block" + item_id).html();
		var current_num = 0;
		var count_num=0;
		while (current_num != -1){
			current_num = str_search.indexOf('category_id=\"'+numcomb[0]+'\"',(current_num+1));
			if (current_num!=-1){count_num++;};
		};
		if (count_num<parseInt(numcomb[1])||category_id!=numcomb[0]) {
		//End @ Dec 15 2016
		
        var html = '<div class="extras_inner" category_id="' + category_id + '" alt="' + extra_id + '"><span>' + name + '</span><span>' + (price != 0 ? price : "") + '</span><a class="fa fa-times remove_extra" href="javascript:void(0)"> </a></div>';

        $("#block" + item_id).append(html);
		};//MOdified by Yishou Liao @ Dec 15 2016

    })



    $(document).on('click', ".remove_extra", function () {
        $(this).parent(".extras_inner").remove();

    })



    $(document).on("click", '.sub-items', function (e) {
        e.stopPropagation();
    })

    $(document).on("click", "#submit", function () {
        //Modified by Yishou Liao @ Oct 26 2016.
        var ext_arr;
        for (var i = 0; i < Order_Item_Printer.length; i++) {
            if (Order_Item_Printer[i][10] != "") {
                ext_arr = JSON.parse(Order_Item_Printer[i][10]);
                Order_Item_Printer[i][10] = "";
                for (var j = 0; j < ext_arr.length; j++) {
					//Modified by Yishou Liao @ Nov 16 2016
					if (ext_arr[j]['price'] == "-1") {
						Order_Item_Printer[i][16] = "#T#";
					}else{
	                    Order_Item_Printer[i][10] += ext_arr[j]['name'] + "  ";
					};
					//End
                };
            };
        };

        //Modified by Yishou Liao @ Nov 01 2016.
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printTokitchen',1,0)); ?>",
            method: "post",
            data: {
                Print_Item: Order_Item_Printer,
                Printer: {"K": "<?php echo $cashier_detail['Admin']['kitchen_printer_device']; ?>", "C": "<?php echo $cashier_detail['Admin']['service_printer_device']; ?>"},
                order_no: $("#Order_no").val(),//Modified by Yishou Liao @ Dec 12 2016
                order_type: '<?php echo isset($Order_detail['Order']['order_type']) ? $Order_detail['Order']['order_type'] : "" ?>',
                table_no: '<?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))) . ' #' . $table ?>',
                table: '<?php echo $table ?>',
            },
            dataType: "html",
            async: false,
            success: function (html) {
                // update order message here
                if (!$(this).hasClass('disabled')) {
                    var order_id = $(this).attr("alt");
                    $.ajax({
                        url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'updateordermessage')); ?>",
                        method: "post",
                        data: {order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", message: $("#Message").val(), is_kitchen: "Y"},
                        success: function (html) {
                            window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
                        },
                        beforeSend: function () {
                            $(".summary_box").addClass('load1 csspinner');
                        }
                    })
                }
            },
            error: function (html) {
                alert("error");
            }
        })
        //End.
    });
    $(document).on("click", "#pay", function () {
		//Modified by Yishou Liao @ Jan 07 2017
		$("#submit").trigger("click");
		//End @ Jan 07 2017
		
        // update order message here
        if (!$(this).hasClass('disabled')) {
            var order_id = $(this).attr("alt");
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'updateordermessage')); ?>",
                method: "post",
                data: {order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", message: $("#Message").val(), is_kitchen: "N"},
                success: function (html) {
                    window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'pay', 'table' => $table, 'type' => $type)); ?>";
                },
                beforeSend: function () {
                    $(".summary_box").addClass('load1 csspinner');
                }
            })
        }
    });
	
	//Modified by Yishou Liao @ Nov 10 2016
	$(document).on("click", ".clearbtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val("");
	});
	$(document).on("click", "#extnobtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"No ");
	});
	$(document).on("click", "#extmorebtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"More ");
	});
	$(document).on("click", "#extlessbtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"Less ");
	});
	//End

    $(document).on("click", ".savebtn", function () {
        var id = $(this).attr("alt");
        var message = $("#Message").val();
        var array = new Array();

        // get all selected extras items of menu
        $("#block" + id + " div.extras_inner").each(function () {
            array.push($(this).attr('alt'));
        });
        var input_value = array.toString();
        var element = $(this).parent("ul.dropdown-menu");

		var var_inputext;
		if ($("#ext_memo").val()!=""){
			if (input_value!=""){
				var_inputext = input_value+","+$("#ext_memo"+id).val();
			}else{
				var_inputext = $("#ext_memo"+id).val();
			};
		}else{
			var_inputext = input_value;
		};
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_extras')); ?>",
            method: "post",
            data: {item_id: id, extras: var_inputext, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
                $(".clearfix.cart-wrap").removeClass("csspinner");

                // Modified by Yishou Liao @ Oct 28 2016.
                Order_Item_Printer = Array();
				// Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
				//End
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                };
                //End.

            },
            beforeSend: function () {
                element.addClass('load1 csspinner');
            }
        })
    })

    $(document).ready(function () {

        $(".search-clear").click(function () {
            $("#search-input").val('');
            $("#search-input").focus();
            $(".add_items").show();
        })

        $("#search-input").on("keyup", function () {
            var value = $(this).val();

            $(".add_items").each(function (index) {
                $row = $(this);

                var id = $row.find(".name-title").text();

                if (id.toLowerCase().indexOf(value) < 0) {
                    $row.hide();
                } else {
                    $row.show();
                }
            });
        });

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'summarypanel', $table, $type)); ?>",
            method: "post",
            success: function (html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');

                // Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
					for (var i = 0; i < arrtmp.length; i++) {
						Order_Item_Printer.push(arrtmp[i].split("*"));
					};
				};//Modified by Yishou Liao @ Nov 28 2016
                //End
                // responsive font size
                /*$('.less-title').flowtype({
                    // fontRatio : 15,
                    // minimum: 500,
                    minFont: 13,
                    maxFont: 20
                })*/

            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        })
    });

    $(document).on("keyup", ".discount_section", function () {
        if ($(this).val()) {
            $(".discount_section").attr("disabled", "disabled");
            $(this).removeAttr("disabled");
			$(this).focus();
        } else {
            $(".discount_section").removeAttr("disabled");
        }
    })

    $(document).on("click", "#apply-discount", function () {

        var fix_discount = $("#fix_discount").val();
        var discount_percent = $("#discount_percent").val();
        var promocode = $("#promocode").val();

        if (fix_discount || discount_percent || promocode) {
            // apply promocode here
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_discount')); ?>",
                method: "post",
                dataType: "json",
                data: {fix_discount: fix_discount, discount_percent: discount_percent, promocode: promocode, order_id: $("#pay").attr("alt")},
                success: function (html) {
                    if (html.error) {
                        alert(html.message);
                        $(".discount_section").val("").removeAttr("disabled");
                        $(".products-panel").removeClass('load1 csspinner');
                        $(".summary_box").removeClass('load1 csspinner');
                        return false;
                    } else {
                        $.ajax({
                            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'summarypanel', $table, $type)); ?>",
                            method: "post",
                            success: function (html) {
                                $(".summary_box").html(html);
                                $(".products-panel").removeClass('load1 csspinner');
                                $(".summary_box").removeClass('load1 csspinner');
                            }
                        })
                    }
                },
                beforeSend: function () {
                    $(".products-panel").addClass('load1 csspinner');
                    $(".summary_box").addClass('load1 csspinner');
                }
            })


        } else {
            alert("Please add discount first.");
            return false;
        }
    })

    $(document).on('click', ".remove_discount", function () {
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'remove_discount')); ?>",
            method: "post",
            data: {order_id: order_id},
            success: function (html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    })
    $(document).on('click', ".add-discount", function () {
        if (!$(this).hasClass('disabled')) {
            $(".discount_view").toggle();
        }
    });
    $(document).on("click", '.dropdown-toggle', function () {
        if ($(this).attr("aria-expanded") == 'true') {
            $(".clearfix.cart-wrap").addClass("csspinner");
        } else {
            $(".clearfix.cart-wrap").removeClass("csspinner");
        }
        dropDownFixPosition($(this), $(".dropdown-menu"));
    });

    // notice
    // change style, should be move to style.css in the future
    function dropDownFixPosition(button, dropdown) {
        var dropDownTop = button.position().top;
        var left = $(document).width() - dropdown.width();
        var top = $(document).height() - dropdown.height();
        dropdown.css('left', left / 2 + "px");
        dropdown.css('top', "20%");
    }


    $(document).on('click', "html", function () {
        $(".clearfix.cart-wrap").removeClass("csspinner");
    });

    $(document).on('click', ".dropdown-menu", function (event) {
        event.stopPropagation();
    });
</script>


<script>
// touch move
// modified by Yu Dec 16, 2016

    $(document).ready(function () {
        $('.txt13').flowtype({
            fontRatio : 15,
            // minimum: 500,
            minFont: 13,
            maxFont: 20
        });


        $('.dish-title br').after('<br/>')

    });

</script>