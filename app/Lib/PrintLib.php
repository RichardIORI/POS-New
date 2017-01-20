<?php 

class PrintLib {

    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width

    public $itemLineLen = 180;
    // public $charNo = $this->itemLineLen / $this->fontW;
    public $charNo = 20;
    public $lineStartPos = 10;

    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $items = json_decode($this->data['items']);

        echo json_encode($items[0]);
    }

    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $items = json_decode($this->data['items']);




        echo json_encode($items[0]);
    }



    public function header() {
        $this->layout = false;
        $this->autoRender = NULL;

        $header = "";

        return $header;
    }


    public function footer() {
        $this->layout = false;
        $this->autoRender = NULL;

        $footer = "";

        return $footer;
    }


    public function switchZh() {
        $fontZh = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $fontZh);
    }

    public function switchEn() {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
    }


    public function printZh($str, $x, $y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printBigZh ($str, $x, $y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    // each chinese character take two byte
    public function printItemZh($str, $x, &$y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        // change the str to chinese string
        // $str =  iconv("UTF-8", "gb2312", $str);
        $start = 0;

        while (mb_strlen($str, 'UTF-8') > 0) {
            $print_str = mb_substr($str, $start, 10);
            printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $print_str), $x, $y);
            $str = mb_substr($str, $start);
            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            
            $start += 10;
           
        }

        // printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printItemEn($str, $x, &$y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        $start = 0;
        while (strlen($str) != 0) {
            $print_str = substr($str, $start, 20);
            printer_draw_text($this->handle, $print_str, $x, $y);
            $str = mb_substr($str, $start);

            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            $start += 20;
        }

        printer_delete_font($font);
    }

    public function printEn($str, $x, $y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }

    public function printBigEn($str, $x, $y) {
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }



    public function printHeaderPage($handle, $order_no, $table_no, $table_type, $print_zh=true, $header_type) {
        printer_start_page($handle);

        $table_type_str = "";
        if ($table_type == 'D') {
            $table_type_str = '[[堂食]]';
        } else if ($table_type == 'T') {
            $table_type_str = '[[外卖]]';
        } else if ($table_type == 'W') {
            $table_type_str = '[[等候]]';
        }

        $y = 10;

        if ($header_type == "kitchen") {
            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组"), 138, $y);
            } else {
                $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, "Kitchen", 138, $y);
            }
        }
        printer_end_page($handle);
        

        printer_start_page($handle);

        $y = 0;
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "Order Number: #" . $order_no, 32, $y);

        $y += 35;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $table_no), 32, $y);
        //End

        $y += 35;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $y, 600, $y);


        printer_delete_font($font);
        printer_end_page($handle);
    }

    public function printFooterPage($handle) {
        printer_start_page($handle);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);
        printer_end_page($handle);
    }

    public function printKitchenItemsPage($handle, $item_detail) {
        foreach ($item_detail as $item) {
            printer_start_page($handle);

            $font1H = 32;
            $font2H = 38;
            $font3H = 32; 
            $font1 = printer_create_font("Arial", $font1H, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            $font2 = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $font2H, 16, PRINTER_FW_BOLD, false, false, false, 0);
            
            $font3 = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $font3H, 14, PRINTER_FW_BOLD, false, false, false, 0); //maximum 12 per line
            

            $name_zh = $item['name_xh'];
            $name_en = $item['name_en'];
            // $price = $item['price'];
            $selected_extras = $item['selected_extras'];

            if ($item['is_takeout'] == 'Y') {
                $name_zh = "(外卖)" . $name_zh;
                $name_en = "(Take out)" . $name_en;
            }

            $y = 10;
            
            printer_select_font($handle, $font1);
            printer_draw_text($handle, $name_en, 80, $y);
            $y += $font1H + 3;
        
            printer_select_font($handle, $font2);
            printer_draw_text($handle,iconv("UTF-8", "gb2312", $name_zh), 80, $y);
            $y += $font2H + 3;
            
            printer_select_font($handle, $font3);

            if (strlen($selected_extras) > 0) {
                $selected_extras_arr = $this->mbStrSplit($selected_extras, 14);
                foreach($selected_extras_arr as $line) {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $line), 80, $y);
                    $y += $font3H;
                }
            }

            printer_delete_font($font1);
            printer_delete_font($font2);
            printer_delete_font($font3);

            printer_end_page($handle);
        }    
    }

    public function mbStrSplit($string, $len=1) {
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $string = mb_substr($string, $len, $strlen,"utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }



    // print all items with cancelled tag
    public function printCancelledItems($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {
        // do not check $item_id_list 

        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        // add cancel for each item
        for ($i = 0; $i < count($item_detail); ++$i) {
            $item_detail[$i]['name_xh'] = "(取消)" .  $item_detail[$i]['name_xh'];
            $item_detail[$i]['name_en'] = "(Cancel)" . $item_detail[$i]['name_en'];
        }



        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        // print header
        $this->printHeaderPage($handle, $order_no, $table_no, $table_type, true, "kitchen");

        // print items
        $this->printKitchenItemsPage($handle, $item_detail);
    
        // print footer
        $this->printFooterPage($handle);


        printer_end_doc($handle);
        printer_close($handle);

        // send feedback to server
        return $debug_str;
    }


    public function printKitchenItemDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {

        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }


        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        // print header
        $this->printHeaderPage($handle, $order_no, $table_no, $table_type, true, "kitchen");

        // print items
        $this->printKitchenItemsPage($handle, $item_detail);

        // print footer
        $this->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);

        return $debug_str;

    }

    public function printUrgeItemDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        // add cancel for each item
        for ($i = 0; $i < count($item_detail); ++$i) {
            $item_detail[$i]['name_xh'] = "(加急)" .  $item_detail[$i]['name_xh'];
            $item_detail[$i]['name_en'] = "(Hurry)" . $item_detail[$i]['name_en'];
        }



        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        // print header
        $this->printHeaderPage($handle, $order_no, $table_no, $table_type, true, "kitchen");

        // print items
        $this->printKitchenItemsPage($handle, $item_detail);
    
        // print footer
        $this->printFooterPage($handle);


        printer_end_doc($handle);
        printer_close($handle);

        // send feedback to server
        return $debug_str;
    }


}




 ?>