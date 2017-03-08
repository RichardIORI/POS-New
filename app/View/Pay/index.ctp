<div class="pay container-fluid">
    <div class="order-wrap">
        <?php echo $this->Session->flash(); ?>
        <div class="col-md-4 col-sm-4 col-xs-12 order-left">
            <h2><?php echo __('Order No.'); ?><?php echo $Order_detail['Order']['order_no'] ?><br><?php echo __('Table'); ?> <?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?></h2>

            <div class="paid-box">
                <div class="checkbox-btn">
                    <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php if ($Order_detail['Order']['table_status'] == 'P') echo "checked='checked'"; ?>/>
                    <label for="rc1" disabled><?php echo __('Paid'); ?></label>
                </div>
            </div>
            <?php
            if ($Order_detail['Order']['table_status'] <> 'P') {
                ?>
                <div class="table-box dropdown"><a href="" class="dropdown-toggle"  data-toggle="dropdown"><?php echo __('Change Table'); ?></a>
                    <ul class="dropdown-menu">
                        <div class="customchangemenu clearfix">
                            <div class="left-arrow"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('DINE IN'); ?></div>
                            <?php for ($t = 1; $t <= DINEIN_TABLE; $t++) {
                                if (!@$orders_no[$t]['D']) {
                                    ?>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$Order_detail['Order']['order_no'], 'ref' => 'pay')); ?>">D<?php echo $t; ?></a></div>
                                <?php }
                            } ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('TAKE OUT'); ?></div>
                            <?php for ($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                                if (!@$orders_no[$t]['T']) {
                                    ?>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$Order_detail['Order']['order_no'], 'ref' => 'pay')); ?>">T<?php echo $t; ?></a></div>
                                <?php }
                            } ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"><?php echo __('WAITING'); ?></div>
    <?php for ($t = 1; $t <= WAITING_TABLE; $t++) {
        if (!@$orders_no[$t]['W']) {
            ?>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'W', 'order_no' => @$Order_detail['Order']['order_no'], 'ref' => 'pay')); ?>">W<?php echo $t; ?></a></div>
        <?php }
    } ?>
                        </div>
                    </ul>
                </div>
<?php } ?>

            <div class="avoid-this text-center reprint"><button type="button" class="submitbtn"><?php echo __('Print Receipt'); ?></button></div>
            <div class="order-summary">
                <h3><?php echo __('Order Summary'); ?></h3>
                <div class="order-summary-indent clearfix">
                    <ul>
                        <?php
                        if (!empty($Order_detail['OrderItem'])) {
                            foreach ($Order_detail['OrderItem'] as $key => $value) {
                                # code...
                                $selected_extras_name = [];
                                // if ($value['all_extras']) {
                                    // $extras = json_decode($value['all_extras'], true);
                                    $selected_extras = json_decode($value['selected_extras'], true);

                                    // prepare extras string
                                    $selected_extras_id = [];
                                    if (!empty($selected_extras)) {
                                        foreach ($selected_extras as $k => $v) {
                                            $selected_extras_name[] = $v['name'];
                                            $selected_extras_id[] = $v['id'];
                                        }
                                    }
                                // }
                                ?>
                                <li class="clearfix">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-8 col-xs-8">
                                            <div class="pull-left titlebox1">
                                                <!-- to show name of item -->
                                                <div class="less-title"><?php echo $value['name_en'] . "<br/>" . $value['name_xh']; ?></div>

                                                <!-- to show the extras item name -->
                                                <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 col-xs-4 text-right price-txt">
                                            $<?php echo ($value['price'] + $value['extras_amount']); ?><?php echo $value['qty'] > 1 ? "x" . $value['qty'] : "" ?>
                                        </div>
                                    </div>
                                </li>
    <?php }
}
?>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12 RIGHT-SECTION">
            <div class="clearfix total-payment">
                <ul>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Subtotal'); ?></div>
							<div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                                $<?php echo number_format($Order_detail['Order']['subtotal'], 2);?>
							</div>

                    <?php
                    if ($Order_detail['Order']['table_status'] != 'P' and ! $Order_detail['Order']['discount_value']) {
                        ?>
                                <div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo __('Add Discount'); ?></button></div>
<?php } ?>
                        </div>
                    </li>
<?php if (!$Order_detail['Order']['discount_value']) { ?>
                        <li class="clearfix discount_view" style="display:none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fix_discount" style="font-size:11px;"><?php echo __('Fix Discount'); ?></label>
                                        <input type="text" id="fix_discount" required="required" class="form-control discount_section" maxlength="5"  name="fix_discount">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="discount_percent" style="font-size:11px;"><?php echo __('Discount in %'); ?></label>
                                        <input type="text" id="discount_percent" required="required" class="form-control discount_section" maxlength="5"   name="discount_percent">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="promocode" style="font-size:11px;"><?php echo __('Promo Code'); ?></label>
                                        <input type="text" id="promocode" required="required" class="form-control discount_section" maxlength="200" name="promocode">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="AdminTableSize" style="width:100%">&nbsp;</label>
                                        <a class="btn btn-primary btn-wide" id="apply-discount" href="javascript:void(0)"><?php echo __('Apply'); ?> <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </li>
<?php } ?>

<?php if ($Order_detail['Order']['discount_value']) {
    ?>
                        <li class="clearfix">
                            <div class="row">
                                    <?php
                                    // show discount code here
                                    ?>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Discount'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                                    $<?php
                                echo number_format($Order_detail['Order']['discount_value'], 2);
                                if ($Order_detail['Order']['percent_discount']) {
                                    echo "<span class='txt12'> " . $Order_detail['Order']['promocode'] . " (" . $Order_detail['Order']['percent_discount'] . "%)</span>";
                                }
                                    ?>
                                    <a aria-hidden="true" class="fa fa-times remove_discount" order_id="<?php echo $Order_detail['Order']['id']; ?>" href="javascript:void(0)"></a>
                                </div>

                            </div>
                        </li>

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('After Discount'); ?>: </div><div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format(max($Order_detail['Order']['subtotal'] - $Order_detail['Order']['discount_value'], 0), 2); else echo '0.00'; ?></div>

                        </div>
                    </li>

    <?php
}
?>

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tax'); ?>(<?php echo $Order_detail['Order']['tax'] ?>%)</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></div>
                        </div>
                    </li>
					<!-- End -->

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Total'); ?></div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php echo round($Order_detail['Order']['total'],2); ?>">$<?php echo number_format($Order_detail['Order']['total'], 2) ?></div>
                        </div>
                    </li>
<?php
if ($Order_detail['Order']['table_status'] == 'P') {
    ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Received'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>


                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price"><?php echo __('Cash'); ?>: $<?php echo $Order_detail['Order']['cash_val']; ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price"><?php echo __('Card'); ?>: $<?php echo $Order_detail['Order']['card_val']; ?></div>
                            </div>
                        </li>

    <?php if ($Order_detail['Order']['change']) { ?>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt"><?php echo __('Change'); ?></div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                                </div>
                            </li>
                        <?php } ?>

                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tip'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$<?php echo $Order_detail['Order']['tip']; ?></div>
                            </div>
                        </li>
    <?php
} else {
    ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Received'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$00.00</div>

                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price"><?php echo __('Cash'); ?>: $00.00</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price"><?php echo __('Card'); ?>: $00.00</div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt"><?php echo __('Remaining'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tip'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$00.00</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <div class="control-label col-md-4 sub-txt">Paid by:</div> -->
                                        <!-- <div class="col-md-8 tip-paid-by text-center"> -->


                                                    <!-- <input id="tip-card" name="tip_paid_by"  class="tip_paid_by" value="CARD" type="radio">
                                                    <label for="tip-card" class="control-label vcenter"><?php echo $this->Html->image("card.png", array('alt' => "card")); ?><div>Card 卡</div></label> -->


                                                    <!-- <input id="tip-cash" name="tip_paid_by"  class="tip_paid_by" value="CASH" type="radio">
                                                    <label for="tip-cash" class="control-label"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?><div>Cash 现金</div></label> -->

                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                        </li>
                <?php
            }
            ?>
                </ul>
            </div>

<?php
if ($Order_detail['Order']['table_status'] <> 'P') {
    ?>
                <div class="card-wrap"><input type="text" id="screen" buffer="0" maxlength="13" readonly></div>
                <div class="card-indent clearfix">
                    <ul>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>

                        <li>4</li>
                        <li>5</li>
                        <li>6</li>

                        <li>7</li>
                        <li>8</li>
                        <li>9</li>

                        <li class="clear-txt" id="Clear"><?php echo __('Clear'); ?></li>
                        <li>0</li>
                        <li class="enter-txt" id="Enter"><?php echo __('Enter'); ?></li>
                    </ul>
                </div>

                <div class="card-bot clearfix text-center">
                    <button type="button" class="btn btn-danger select_card" id="card"> <?php echo $this->Html->image("card.png", array('alt' => "card")); ?> <?php echo __('Card'); ?></button>
                    <button type="button" class="btn btn-danger select_card" id="cash"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?> <?php echo __('Cash'); ?></button>

                    <!-- <button type="button" class="btn btn-warning select_card"  id="tip"><?php echo $this->Html->image("cash.png", array('alt' => "tip")); ?> Tip 小费</button> -->

                    <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?><?php echo __('Confirm'); ?> </button>

                    <input type="hidden" id="selected_card" value="" />
                    <input type="hidden" id="card_val" name="card_val" value="" />
                    <input type="hidden" id="cash_val" name="cash_val" value="" />
                    <input type="hidden" id="tip_val"name="tip" value="" />
                    <input type="hidden" id="tip_paid_by"name="tip_paid_by" value="" />
                </div>

<?php } ?>
        </div>
    </div>
</div>

<script>
    // $('#tip-cash').trigger('click');

    var text_remaining = "<?php echo __('Remaining'); ?>";
    var text_change = "<?php echo __('Change'); ?>";
    var text_cash = "<?php echo __('Cash'); ?>";
    var text_card = "<?php echo __('Card'); ?>";

    $(document).on('click', '.reprint', function () {

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'pay', 'action' => 'printBill')); ?>",
            method: "post",
            data: {
                order_no: "<?php echo $Order_detail['Order']['order_no']; ?>",
            },
            success: function (html) {

            }
        })
        //End.
    });


    $(document).ready(function () {

        $(".select_card").click(function () {
            $(".select_card").removeClass("active")
            $(this).addClass("active")
            var type = $(this).attr("id");
            if (type == 'card') {
                $("#cash").removeClass("active");
                var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) * 100 : 0;
                $("#screen").attr('buffer', card_val);
                $("#screen").val($("#card_val").val());
            } else if (type == 'cash') {
                var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) * 100 : 0;
                $("#screen").attr('buffer', cash_val);
                $("#screen").val($("#cash_val").val());
            } else {
                var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
                $("#screen").attr('buffer', tip_val);
                $("#screen").val($("#tip_val").val());
            }
            $("#selected_card").val(type);
        })



        $(".select_tip").click(function () {
            $(".select_card").removeClass("active");
            $(this).toggleClass("active");
            var val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
            $("#screen").attr('buffer', val);
            $("#screen").val($("#tip_val").val());
        })

        $("#submit").click(function () {
            if ($("#selected_card").val()) {
                if (parseFloat($(".change_price").attr("amount")) >= 0) {

                    // check tip type(card/cash) if exists
                    if (parseFloat($("#tip_val").val())) {
                        if (!$("#tip_paid_by").val()) {
                            $("#submit").notify("Please select tip payment method card or cash \n 请选择提示付款方式卡或现金. ", {
                                position: "top center",
                                className:"warn"
                            });
                            // alert("Please select tip payment method card or cash 请选择提示付款方式卡或现金. ");
                            return false;
                        }
                    }

                    // submit form for complete payment process
                    $.ajax({
                        url: "<?php echo $this->Html->url(array('controller' => 'pay', 'action' => 'complete')); ?>",
                        method: "post",
                        data: {
                            pay: $(".received_price").attr("amount"),
                            paid_by: $("#selected_card").val(),
                            change: $(".change_price").attr("amount"),
                            table: "<?php echo $table ?>",
                            type: "<?php echo $type ?>",
                            order_id: "<?php echo $Order_detail['Order']['id'] ?>",
                            card_val: $("#card_val").val(),
                            cash_val: $("#cash_val").val(),
                            tip_val: $("#tip_val").val(),
                            tip_paid_by: $("#tip_paid_by").val(),
                        },
                        success: function (html) {
                            $(".alert-warning").hide();
                            // $(".reprint").trigger("click");
                            $.ajax({
                                url: "<?php echo $this->Html->url(array('controller' => 'pay', 'action' => 'printReceipt')); ?>",
                                method: "post",
                                data: {
                                    order_no: "<?php echo $Order_detail['Order']['order_no']; ?>",
                                },
                                success: function (html) {
                                    window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
                                }
                            })
                        },
                        beforeSend: function () {
                            $(".RIGHT-SECTION").addClass('load1 csspinner');
                            $(".alert-warning").show();
                        }
                    })
                } else {
                    $.notify("Invalid amount, please check and verfy again 金额无效，请检查并再次验证.", {
                        position: "top center",
                        className:"warn"
                    });
                    // alert("Invalid amount, please check and verfy again 金额无效，请检查并再次验证.");
                    return false;
                }
            } else {
                $.notify("Please select card or cash payment method 请选择卡或现金付款方式. ", {
                        position: "top center",
                        className:"warn"
                    });
                // alert("Please select card or cash payment method 请选择卡或现金付款方式. ");
                return false;
            }
        })

        $(".card-indent li").click(function () {
            if (!$("#selected_card").val() && !$(".select_tip").hasClass("active")) {
                // alert("Please select payment type cash/card or select tip.");
                $.notify("Please select payment type card/cash.",  {
                    position: "top center",
                    className:"warn",
                });
                return false;
            }

            if ($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt"))
                return false;

            var digit = parseInt($(this).html());
            var nums = $("#screen").attr('buffer') + digit;

            // store buffer value
            $("#screen").attr('buffer', nums);
            nums = nums / 100;
            nums = nums.toFixed(2);
            if (nums.length < 12)
                $("#screen").val(nums).focus();
            else
                $("#screen").focus();
        })


        function recalculateAmount(cash_val, card_val, tip, total_price) {

            $("#tip_val").val(tip);

            var card_extra_tip = 0;

            console.log(cash_val);

            var amount = cash_val + card_val;

            $(".received_price").html("$" + amount.toFixed(2));
            $(".received_price").attr('amount', amount.toFixed(2));


            if (card_val >= total_price) {

                card_extra_tip = card_val - total_price;
                tip += card_extra_tip;

                $(".change_price_txt").html("Change 找零");
                $(".change_price").html("$" + cash_val.toFixed(2));
                $(".change_price").attr('amount', (cash_val).toFixed(2));

                $(".tip_price").html("$" + card_extra_tip.toFixed(2));
                $("#tip_val").val(card_extra_tip.toFixed(2));
                if (card_extra_tip > 0) {
                    $("#tip_paid_by").val("CARD");
                }

            } else { // card_val < total_price

                $(".change_price").html("$" + Math.abs(amount - total_price).toFixed(2));
                $(".change_price").attr('amount', (amount - total_price).toFixed(2));

                if (amount < total_price) {
                    $(".change_price_txt").html(text_remaining);
                } else { // amount >= total_price
                    $(".change_price_txt").html(text_change);
                }

                $(".tip_price").html("$" + (0).toFixed(2));
                $("#tip_val").val((0).toFixed(2));
                if (card_extra_tip > 0) {
                    $("#tip_paid_by").val("NO TIP");
                }
            }



        }


        $("#Enter").click(function () {
            if (!$("#selected_card").val()) {
                $.notify("Please select payment type card/cash.",  {
                    position: "top center",
                    className:"warn",
                });
                return false;
                // alert("Please select payment type card/cash.");
                // return false;
            }
            var amount = $("#screen").val() ? parseFloat($("#screen").val()) : 0;
            var total_price = parseFloat($(".total_price").attr("alt"));

            if ($("#selected_card").val() == 'cash') {
                $("#cash_val").val(amount.toFixed(2));
                $(".cash_price").html(text_cash + amount.toFixed(2));
            }
            if ($("#selected_card").val() == 'card') {
                $("#card_val").val(amount.toFixed(2));
                $(".card_price").html(text_card + amount.toFixed(2));
            }
            if ($("#selected_card").val() == 'tip') {
                $("#tip_val").val(amount.toFixed(2));
                // $(".tip_price").html("$" + amount.toFixed(2));
            }

            var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
            var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;
            var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) : 0;


            recalculateAmount(cash_val, card_val, tip_val, total_price);

        })

        $("#rc1").click(function (E) {
            E.preventDefault();
        })

        $("#Clear").click(function () {

            var selected_card = $("#selected_card").val();
            var total_price = parseFloat($(".total_price").attr("alt"));
            if (selected_card == 'cash') {
                $("#cash_val").val(0);
                $(".cash_price").html(text_cash + ": $" + (0).toFixed(2));
            }

            if (selected_card == 'card') {
                $("#card_val").val(0);
                $(".card_price").html(text_card + ": $" + (0).toFixed(2));

            }
            if (selected_card == 'tip') {
                $("#tip_val").val(0);
            }

            var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
            var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;
            var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) : 0;

            recalculateAmount(cash_val, card_val, tip_val, total_price);

            $("#screen").attr('buffer', 0);
            $("#screen").val("");
            $("#screen").focus();
        })

        $("#screen").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: home, end, left, right, down, up
                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });
    })

    $(document).on("keyup", ".discount_section", function () {
        if ($(this).val()) {
            $(".discount_section").attr("disabled", "disabled");
            $(this).removeAttr("disabled");
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
                url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'addDiscount')); ?>",
                method: "post",
                dataType: "json",
                data: {fix_discount: fix_discount, discount_percent: discount_percent, promocode: promocode, order_no: "<?php echo $Order_detail['Order']['order_no'] ?>"},
                success: function (html) {
                    if (html.error) {
                        $.notify(html.message,  {
                            position: "top center",
                            className:"warn",
                        });
                        // alert(html.message);
                        $(".discount_section").val("").removeAttr("disabled");
                        $(".RIGHT-SECTION").removeClass('load1 csspinner');
                        return false;
                    } else {
                        window.location.reload();
                    }
                },
                beforeSend: function () {
                    $(".RIGHT-SECTION").addClass('load1 csspinner');
                }
            })


        } else {
            $.notify("Please add discount first.",  {
                position: "top center",
                className:"warn",
            });
            // alert("Please add discount first.");
            return false;
        }
    })

    $(document).on('click', ".remove_discount", function () {
        var order_id = "<?php echo $Order_detail['Order']['id'] ?>";
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'removeDiscount')); ?>",
            method: "post",
            data: {order_no: "<?php echo $Order_detail['Order']['order_no'] ?>"},
            success: function (html) {
                window.location.reload();
            },
            beforeSend: function () {
                $(".RIGHT-SECTION").addClass('load1 csspinner');
            }
        })
    })
    $(document).on('click', ".add-discount", function () {
        $(".discount_view").toggle();
    });


    $(document).on('click', ".tip_paid_by", function () {
        $("#tip_paid_by").val($(this).val());
    });

    // modified by Yu Dec 15, 2016
/*    $(document).ready(function() {
        $('.order-summary-indent').kinetic();
    });
*/

    // $(document).ready(function () {
    //     $('body').flowtype({
    //         minimum: 500,
    //         minFont: 12,
    //         maxFont: 40
    //     });
    // });
</script>
