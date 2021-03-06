<?php
/**
 * lionfish 商城系统
 *
 * ==========================================================================
 * @link      http://www.liofis.com/
 * @copyright Copyright (c) 2015 liofis.com.
 * @license   http://www.liofis.com/license.html License
 * ==========================================================================
 *
 * @author    fish
 *
 */
namespace Seller\Model;

class ExcelModel{


    protected function column_str($key)
    {
        $array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');
        return $array[$key];
    }

    protected function column($key, $columnnum = 1)
    {
        return $this->column_str($key) . $columnnum;
    }


    public function export_delivery_goodslist( $list, $params = array() )
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;

        $list_info = $params['list_info'];


        $sheet->setCellValue('A1', $list_info['line1']);

        //$sheet->mergeCells('A1:C1');
        $rownum++;

        $sheet->setCellValue('A2', $list_info['line2']);
        //$sheet->mergeCells('A2:C2');
        $rownum++;


        foreach ($params['columns'] as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);

            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }

        }

        ++$rownum;
        $len = count($params['columns']);

        foreach ($list as $row ) {
            $i = 0;

            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                $sheet->setCellValue($this->column($i, $rownum), $value);
                ++$i;
            }

            ++$rownum;
        }



        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));


        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }


    public function export_delivery_list_pinew($params_list, $list = array())
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);


        /**
        ["200_"]=>
        array(4) {
        ["goods_name"]=>
        string(15) "牙刷【李】"
        ["goods_goodssn"]=>
        string(0) ""
        ["goods_count"]=>
        int(10)
        ["head_goods_list"]=>
        array(2) {
        [1]=>
        array(5) {
        ["price"]=>
        string(6) "0.0100"
        ["total_price"]=>
        float(0.09)
        ["buy_quantity"]=>
        int(9)
        ["head_name"]=>
        string(11) "15865422541"
        ["total_quatity"]=>
        int(9)
        }
        [118]=>
        array(5) {
        ["price"]=>
        string(6) "0.0100"
        ["total_price"]=>
        float(0.01)
        ["buy_quantity"]=>
        string(1) "1"
        ["head_name"]=>
        string(11) "18919633344"
        ["total_quatity"]=>
        string(1) "1"
        }
        }
        }
         **/
        $sheet->setCellValue('A1', '序号');
        $sheet->setCellValue('B1', '商品编码');
        $sheet->setCellValue('C1', '商品名称');
        $sheet->setCellValue('D1', '规格');
        $sheet->setCellValue('E1', '单价');
        $sheet->setCellValue('F1', '总价');
        $sheet->setCellValue('G1', '订购数');
        $sheet->setCellValue('H1', '团长');
        $sheet->setCellValue('I1', '合计数');




        $i =1;
        $rownum = 1;



        foreach( $params_list as  $params )
        {
            $next_postion_begin = $rownum + 1;

            for($j=1;$j<= count($params['head_goods_list']); $j++)
            {
                $rownum++;
            }

            if( count($params['head_goods_list']) > 1 )
            {
                //需要合并了
                $sheet->mergeCells('A'.$next_postion_begin.':A'.$rownum);
                $sheet->getStyle('A'.$next_postion_begin.':A'.$rownum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                $sheet->mergeCells('B'.$next_postion_begin.':B'.$rownum);
                $sheet->getStyle('B'.$next_postion_begin.':B'.$rownum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                $sheet->mergeCells('C'.$next_postion_begin.':C'.$rownum);
                $sheet->getStyle('C'.$next_postion_begin.':C'.$rownum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


                $sheet->mergeCells('D'.$next_postion_begin.':D'.$rownum);
                $sheet->getStyle('D'.$next_postion_begin.':D'.$rownum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


                $sheet->mergeCells('I'.$next_postion_begin.':I'.$rownum);
                $sheet->getStyle('I'.$next_postion_begin.':I'.$rownum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            }

            $sheet->setCellValue('A'.$next_postion_begin , $i);
            $sheet->setCellValue('B'.$next_postion_begin ,  $params['goods_goodssn'] );
            $sheet->setCellValue('C'.$next_postion_begin ,  $params['goods_name'] );
            $sheet->setCellValue('D'.$next_postion_begin ,  $params['sku_str'] );

            $k = $next_postion_begin;
            foreach( $params['head_goods_list'] as $head_goods )
            {
                $sheet->setCellValue('E'.$k ,  $head_goods['price'] );
                $sheet->setCellValue('F'.$k ,  $head_goods['total_price'] );
                $sheet->setCellValue('G'.$k ,  $head_goods['buy_quantity'] );
                $sheet->setCellValue('H'.$k ,  $head_goods['head_name'] );
                $k++;
            }

            $sheet->setCellValue('I'.$next_postion_begin ,  $params['goods_count'] );
            $i++;
        }



        $excel->getActiveSheet()->setTitle($list['title']);


        $filename = ($list['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;






        $excel->getActiveSheet()->setTitle($list['title']);


        $filename = ($list['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }


    /**
     * 功能描述： 批量导出团长的配送清单
     * =====================================
     * @author  Lucas
     * email:   598936602@qq.com
     * Website  address:  www.mylucas.com.cn
     * =====================================
     * 创建时间: 2020-03-22 20:04:05
     * @example
     * @link    文档参考地址：
     * @return  返回值
     * @version 版本  1.0
     */
    public function export_delivery_all_list($lists, $params = array())
    {//dump($lists);exit;
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('武房小店')->setLastModifiedBy('武房小店')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');

        foreach ($lists as $l => $list ) {
            // 创建sheet
            $excel->createSheet();
            $sheet = $excel->setActiveSheetIndex($l);
            $rownum = 1;

            foreach ($params['columns'] as $key => $column ) {
                //dump($this->column($key, $rownum)); // 获取单元格："A1"
                //dump($this->column_str($key));exit; // 获取行："A"
                $sheet->setCellValue($this->column($key, $rownum), $column['title']);

                if (!(empty($column['width']))) {
                    $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
                }
                // 设置第一行行高
                $sheet->getRowDimension($rownum)->setRowHeight(24);
                // 设置边框线加粗
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getTop()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getLeft()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getBottom()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getRight()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // 设置居中显示
                $sheet->getstyle($this->column($key, $rownum))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getstyle($this->column($key, $rownum))->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
                // 设置字体加粗
                $sheet->getstyle($this->column($key, $rownum))->getFont()->setBold(true);
                // 设置标题行填充色
                $sheet->getStyle($this->column($key, $rownum))->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $sheet->getStyle($this->column($key, $rownum))->getFill()->getStartColor()->setARGB('80EEEEEE');
            }

            $sheet->freezePane('A2');
            //p($list);
            ++$rownum;
            $len = count($params['columns']);
            $spaceline = 0;
            // 遍历每个工作组的列表数据
            foreach ($list as $eee => $row ) {
                $i = 0;
                while ($i < $len) {
                    $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                    $sheet->setCellValue($this->column($i, $rownum), $value);
                    //需要合并的行如 第二行和第三行，$i < 4代表合并E列前面的列,0为A,1为B,2为C,3为D
                    // $mergeColStartNum = '2';
                    // $mergeColEndNum = '3';
                    foreach ($params['sheetsMergeArr'][$l][0] as $p => $m) {
                        if($rownum == $m && $i < 4){
                            $i_name = $this->column_str($i);
                            $sheet->mergeCells($i_name.$m.':'.$i_name.$params['sheetsMergeArr'][$l][1][$p]);

                            //// 将合并的单元格设置样式垂直居中
                            // $sheet->getstyle($i_name.$m)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            // $sheet->getstyle($i_name.$m)->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
                        }
                    }

                    // $sheet->getStyle($this->column($i, $rownum))->getAlignment()->setWrapText(TRUE);
                    // if($i == 4){
                    // 	$sheet->getstyle($this->column($i, $rownum))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    // }else{
                    // 	$sheet->getstyle($this->column($i, $rownum))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    // }
                    // $sheet->getstyle($this->column($i, $rownum))->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getTop()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getLeft()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getBottom()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getRight()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);

                    ++$i;
                }
                $sheet->getRowDimension($rownum)->setRowHeight(20*$row['jishu']);
                ++$rownum;
                unset($list[$eee]);
            }
            // $sheet->getstyle('A2:A100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('A2:A100')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->getstyle('B2:B100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('B2:B100')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->getstyle('C2:C100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('C2:C100')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->getstyle('D2:D100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('D2:D100')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->getstyle('E2:E100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('E2:E100')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);

            // $sheet->getColumnDimension('H')->setWidth(12);
            // $sheet->getColumnDimension('I')->setWidth(36);
            //$sheet->setCellValue('H2', '配送信息');

            // $sheet->getstyle('H3:I7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('H3:I7')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->setCellValue('H3', '配送日期：');
            // $sheet->setCellValue('I3', $params['delivery_date']);
            // $sheet->setCellValue('H4', '配送地址：');
            // $sheet->setCellValue('I4', $params['sheetsAttrArr'][$l]['address']);
            // $sheet->setCellValue('H5', '联系人：');
            // $sheet->setCellValue('I5', $params['sheetsAttrArr'][$l]['head_name']);
            // $sheet->setCellValue('H6', '联系方式：');
            // $sheet->setCellValue('I6', $params['sheetsAttrArr'][$l]['head_mobile']);
            // $sheet->setCellValue('H7', '总单量：');
            // $sheet->setCellValue('I7', $params['sheetsAttrArr'][$l]['count_ji']);
            // $sheet->setCellValue('H8', '备注：');
            //$sheet->setCellValue('I8', '湖北省武汉市洪山区梨园街道东沙花园小区物业');
            // $sheet->setCellValue('H9', '配送合计');
            // $sheet->setCellValue('H10', '品种');
            // $sheet->setCellValue('I10', '1387148498');
            //$sheet->getColumnDimension('I')->setWidth(30);

            $excel->getActiveSheet()->setTitle($params['sheetsTitleArr'][$l]);
            unset($lists[$l]);
        }
        //p(convert(memory_get_usage(true)));
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }

    /**
     * 功能描述： 批量导出团长的配送清单
     * =====================================
     * @author  Lucas
     * email:   598936602@qq.com
     * Website  address:  www.mylucas.com.cn
     * =====================================
     * 创建时间: 2020-03-22 20:04:05
     * @example
     * @link    文档参考地址：
     * @return  返回值
     * @version 版本  1.0
     */
    public function export_delivery_all_list_dan($lists, $params = array())
    {//dump($lists);exit;
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('武房小店')->setLastModifiedBy('武房小店')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $excel->createSheet();
        $rownum = 1;
        foreach ($lists as $l => $list ) {
            // 创建sheet




            foreach ($params['columns'] as $key => $column ) {
                //dump($this->column($key, $rownum)); // 获取单元格："A1"
                //dump($this->column_str($key));exit; // 获取行："A"
                $sheet->setCellValue($this->column($key, 1), $column['title']);

                if (!(empty($column['width']))) {
                    $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
                }
                // 设置第一行行高
                $sheet->getRowDimension(1)->setRowHeight(24);
                // 设置边框线加粗
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getTop()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getLeft()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getBottom()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // $sheet->getstyle($this->column($key, $rownum))->getBorders()->getRight()->setBorderstyle(\PHPExcel_style_Border::BORDER_MEDIUM);
                // 设置居中显示
                $sheet->getstyle($this->column($key, 1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getstyle($this->column($key, 1))->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
                // 设置字体加粗
                $sheet->getstyle($this->column($key, 1))->getFont()->setBold(true);
                // 设置标题行填充色
                $sheet->getStyle($this->column($key, 1))->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $sheet->getStyle($this->column($key, 1))->getFill()->getStartColor()->setARGB('80EEEEEE');
            }

            $sheet->freezePane('A2');
            //p($list);
            if($rownum == 1){
                ++$rownum;
            }

            $len = count($params['columns']);
            $spaceline = 0;
            // 遍历每个工作组的列表数据
            foreach ($list as $eee => $row ) {
                $i = 0;
                while ($i < $len) {
                    $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                    $sheet->setCellValue($this->column($i, $rownum), $value);
                    //需要合并的行如 第二行和第三行，$i < 4代表合并E列前面的列,0为A,1为B,2为C,3为D
                    // $mergeColStartNum = '2';
                    // $mergeColEndNum = '3';
                    foreach ($params['sheetsMergeArr'][$l][0] as $p => $m) {
                        if($rownum == $m && $i < 4){
                            $i_name = $this->column_str($i);
                            $sheet->mergeCells($i_name.$m.':'.$i_name.$params['sheetsMergeArr'][$l][1][$p]);

                            //// 将合并的单元格设置样式垂直居中
                            // $sheet->getstyle($i_name.$m)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            // $sheet->getstyle($i_name.$m)->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
                        }
                    }

                    $sheet->getStyle($this->column($i, $rownum))->getAlignment()->setWrapText(TRUE);
                    // if($i == 4){
                    // 	$sheet->getstyle($this->column($i, $rownum))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    // }else{
                    // 	$sheet->getstyle($this->column($i, $rownum))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    // }

                    // $sheet->getstyle($this->column($i, $rownum))->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);

                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getTop()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getLeft()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getBottom()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // $sheet->getstyle($this->column($i, $rownum))->getBorders()->getRight()->setBorderstyle(\PHPExcel_style_Border::BORDER_THIN);
                    // if($rownum == $mergeColStartNum && $i < 3){
                    // 	$i_name = $this->column_str($i);
                    // 	$sheet->mergeCells($i_name.$mergeColStartNum.':'.$i_name.$mergeColEndNum);
                    // }
                    ++$i;
                }
                $sheet->getRowDimension($rownum)->setRowHeight(20*$row['jishu']);
                ++$rownum;
                unset($list[$eee]);
            }


            // $sheet->getColumnDimension('H')->setWidth(12);
            // $sheet->getColumnDimension('I')->setWidth(36);
            //$sheet->setCellValue('H2', '配送信息');

            // $sheet->getstyle('H3:I7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $sheet->getstyle('H3:I7')->getAlignment()->setVertical(\PHPExcel_style_Alignment::VERTICAL_CENTER);
            // $sheet->setCellValue('H3', '配送日期：');
            // $sheet->setCellValue('I3', $params['delivery_date']);
            // $sheet->setCellValue('H4', '配送地址：');
            // $sheet->setCellValue('I4', $params['sheetsAttrArr'][$l]['address']);
            // $sheet->setCellValue('H5', '联系人：');
            // $sheet->setCellValue('I5', $params['sheetsAttrArr'][$l]['head_name']);
            // $sheet->setCellValue('H6', '联系方式：');
            // $sheet->setCellValue('I6', $params['sheetsAttrArr'][$l]['head_mobile']);
            // $sheet->setCellValue('H7', '总单量：');
            // $sheet->setCellValue('I7', $params['sheetsAttrArr'][$l]['count_ji']);
            // $sheet->setCellValue('H8', '备注：');
            //$sheet->setCellValue('I8', '湖北省武汉市洪山区梨园街道东沙花园小区物业');
            // $sheet->setCellValue('H9', '配送合计');
            // $sheet->setCellValue('H10', '品种');
            // $sheet->setCellValue('I10', '1387148498');
            //$sheet->getColumnDimension('I')->setWidth(30);

            //$excel->getActiveSheet()->setTitle($params['sheetsTitleArr'][$l]);
            unset($lists[$l]);
        }

        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }

    /**
    批量导出团长配送清单
     **/
    public function export_delivery_list_pi( $params_list, $list = array() )
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);

        $rownum = 1;
        foreach( $params_list as  $params )
        {

            $list_info = $params['list_info'];

            $line1 = $list_info['head_name'];
            $line2 = '团长：'.$list_info['head_name'].'     提货地址：'.$list_info['head_address'].'     联系电话：'.$list_info['head_mobile'];
            $line3 = $list_info['list_sn'].'     时间：'.date('Y-m-d H:i:s', $list_info['create_time']);
            $line4 = '配送路线：'.$list_info['line_name'].'     配送员：'.$list_info['clerk_name'];

            $sheet->setCellValue('A'.$rownum, $line1);


            $rownum++;

            $sheet->setCellValue('A'.$rownum, $line2);
            $rownum++;

            $sheet->setCellValue('A'.$rownum, $line3);

            $rownum++;

            $sheet->setCellValue('A'.$rownum, $line4);

            $rownum++;
            $rownum++;


            foreach ($list['columns'] as $key => $column ) {
                $sheet->setCellValue($this->column($key, $rownum), $column['title']);

                if (!(empty($column['width']))) {
                    $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
                }

            }

            ++$rownum;
            $len = count($list['columns']);



            foreach ($params['data'] as $row ) {
                $i = 0;


                while ($i < $len) {

                    $value = ((isset($row[$list['columns'][$i]['field']]) ? $row[$list['columns'][$i]['field']] : ''));


                    $sheet->setCellValue($this->column($i, $rownum), $value);
                    ++$i;
                }

                ++$rownum;
            }

            $rownum++;
            $rownum++;

        }



        $excel->getActiveSheet()->setTitle($list['title']);


        $filename = ($list['title'] . '-' . date('Y-m-d H:i', time()));

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }


    public function export_delivery_list($list, $params = array())
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;

        $list_info = $params['list_info'];

        $sheet->setCellValue('A1', $list_info['line1']);


        //$sheet->mergeCells('A1:D1');
        $rownum++;

        $sheet->setCellValue('A2', $list_info['line2']);
        //$sheet->mergeCells('A2:D2');
        $rownum++;

        $sheet->setCellValue('A3', $list_info['line3']);
        //$sheet->mergeCells('A3:D3');
        $rownum++;

        $sheet->setCellValue('A4', $list_info['line4']);
        //$sheet->mergeCells('A4:D4');
        $rownum++;


        foreach ($params['columns'] as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);

            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }

        }

        ++$rownum;
        $len = count($params['columns']);

        foreach ($list as $row ) {
            $i = 0;

            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                $sheet->setCellValue($this->column($i, $rownum), $value);
                ++$i;
            }

            ++$rownum;
        }



        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));


        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;

    }


    /**
     * 导出Excel
     * @param type $list
     * @param type $params
     */
    public function export($list, $params = array())
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

//ThinkPHP\Library\Vendor\ROOT_PATH

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;

        foreach ($params['columns'] as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);

            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }

        }

        ++$rownum;
        $len = count($params['columns']);

        foreach ($list as $row ) {
            $i = 0;

            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                $sheet->setCellValue($this->column($i, $rownum), $value);
                ++$i;
            }

            ++$rownum;
        }

        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));



        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * @param $objWriter PHPExcel_Writer_IWriter
     */
    public function SaveViaTempFile($objWriter)
    {
        $filePath = '' . rand(0, getrandmax()) . rand(0, getrandmax()) . '.tmp';
        $objWriter->save($filePath);
        readfile($filePath);
        unlink($filePath);
    }

    /**
     * 生成模板文件Excel
     * @param type $list
     * @param type $params
     */
    public function temp($title, $columns = array())
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();
        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;

        foreach ($columns as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);

            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }

        }

        ++$rownum;
        $len = count($columns);
        $k = 1;

        while ($k <= 5000) {
            $i = 0;

            while ($i < $len) {
                $sheet->setCellValue($this->column($i, $rownum), '');
                ++$i;
            }

            ++$rownum;
            ++$k;
        }

        $excel->getActiveSheet()->setTitle($title);
        $filename = ($title);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit();
    }

    public function import($excefile)
    {

        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel/IOFactory.php';
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel/Reader/Excel5.php';
        $path = ROOT_PATH . '/Uploads/image/'.date('Y-m-d').'/';

        if (!(is_dir($path))) {
            RecursiveMkdir($path);
        }


        $filename = $_FILES[$excefile]['name'];
        $tmpname = $_FILES[$excefile]['tmp_name'];

        if (empty($tmpname)) {
            message('请选择要上传的Excel文件!', '', 'error');
        }


        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (($ext != 'xlsx') && ($ext != 'xls')) {
            //message('请上传 xls 或 xlsx 格式的Excel文件!', '', 'error');
        }


        $file = time() . 1 . '.' . $ext;
        $uploadfile = $path . $file;
        $result = move_uploaded_file($tmpname, $uploadfile);

        if (!($result)) {
            //message('上传Excel 文件失败, 请重新上传!', '', 'error');
        }


        $reader = \PHPExcel_IOFactory::createReader(($ext == 'xls' ? 'Excel5' : 'Excel2007'));
        $excel = $reader->load($uploadfile);
        $sheet = $excel->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnCount = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $values = array();
        $row = 1;

        while ($row <= $highestRow) {
            $rowValue = array();
            $col = 0;

            while ($col < $highestColumnCount) {
                $rowValue[] = (string) $sheet->getCellByColumnAndRow($col, $row)->getValue();
                ++$col;
            }

            $values[] = $rowValue;
            ++$row;
        }

        return $values;
    }

    /**
     * 导出储值卡列表
     * @param array $list
     */
    public function exportRechargecards($list)
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }
        sellerLog('导出储值卡excel', 3);
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator('cwang');
        $columns = array(
            array(
                'title' => '',
                'field' => 'index'
            ) ,
            array(
                'title' => '名称',
                'field' => 'cardname'
            ) ,
            array(
                'title' => '批次',
                'field' => 'cardmark'
            ) ,
            array(
                'title' => '张数',
                'field' => 'cardcount'
            ) ,
            array(
                'title' => '金额',
                'field' => 'valuemoney',
            ) ,
            array(
                'title' => '过期时间',
                'field' => 'expire_time',
            ) ,
            array(
                'title' => '生成时间',
                'field' => 'addtime'
            ) ,
            array(
                'title' => '密码',
                'field' => 'password'
            )
        );

        $sheetIndex = 0;
        foreach ($list as $name=>$item) {
            if ($sheetIndex > 0) {
                $excel->createSheet();
            }
            $sheet = $excel->setActiveSheetIndex($sheetIndex);
            foreach ($columns as $key => $column ) {
                $sheet->setCellValue($this->column($key), $column['title']);
                $sheet->getstyle($this->column($key))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($this->column_str($key))->setWidth(24);
            }

            foreach ($item as $index => $row ) {
                foreach ($columns as $key => $column ) {
                    $value = $row[$column['field']];
                    if ($column['field'] == 'index') {
                        $value = $index+1;
                    } elseif ($column['field'] == 'expire_time' || $column['field'] == 'addtime') {
                        $value = date('Y/m/d H:i', time());
                    }
                    if($column['field'] == 'password'){
                        $sheet->setCellValue($this->column($key, $index+2), $value . ' ');
                    }else{
                        $sheet->setCellValue($this->column($key, $index+2), $value);
                    }
                }
            }

            $sheet->getstyle('A2:' . $this->column(count($columns), count($item)+1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getstyle('B2:B' . (count($item) + 1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->setTitle('储值卡'.$name);
            $sheetIndex ++;
        }

        $excel->setActiveSheetIndex(0);
        $filename = '储值卡' . date('YmdHis', time());

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$filename.'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * 添加了商品列表到处excel
     * @author 刘鑫芮 2020-03-02
     * @param $params 到处excel参数
     * @param $list 商品数据
     * */
    public function export_goods_list_pi( $params, $list = array() ) {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();
        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;
        $list_info = $params['list_info'];
        foreach ($params['columns'] as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);
            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }
        }
        ++$rownum;
        $len = count($params['columns']);
        foreach ($list as $row ) {
            $i = 0;
            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                if(strstr($params['columns'][$i]['field'], 'option_') > -1 && (int)$row['hasoption'] === 1){// 规格有数据
                    if(strstr($params['columns'][$i]['field'], 'option_') > -1){// 规格不存在
                        $j = 0;
                        foreach ($row['option'] as $row_option ) {
                            ++$rownum;
                            $j = $i;
                            while ($j < $len) {
                                $excel_option_field = str_replace('option_','',$params['columns'][$j]['field']);
                                $value_option = ((isset($row_option[$excel_option_field]) ? $row_option[$excel_option_field] : ''));
                                $sheet->setCellValue($this->column($j, $rownum), $value_option);
                                ++$j;
                            }
                        }
                        $i = $j;
                    }
                }else{
                    $sheet->setCellValue($this->column($i, $rownum), $value);
                }

                ++$i;
            }
            ++$rownum;
        }

        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));
        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    public function export_goods_list_edit( $params, $list = array() ) {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }
        require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/phpexcel/PHPExcel.php';
        $excel = new \PHPExcel();
        $excel->getProperties()->setCreator('狮子鱼商城')->setLastModifiedBy('狮子鱼商城')->setTitle('Office 2007 XLSX Test Document')->setSubject('Office 2007 XLSX Test Document')->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('report file');
        $sheet = $excel->setActiveSheetIndex(0);
        $rownum = 1;
        $list_info = $params['list_info'];
        foreach ($params['columns'] as $key => $column ) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);
            if (!(empty($column['width']))) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }
        }
        ++$rownum;
        $len = count($params['columns']);
        foreach ($list as $row ) {
            $i = 0;
            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));
                if(strstr($params['columns'][$i]['field'], 'option_') > -1 && (int)$row['hasoption'] === 1){// 规格有数据
                    if(strstr($params['columns'][$i]['field'], 'option_') > -1){// 规格不存在
                        $j = 0;
                        foreach ($row['option'] as $row_option ) {
                            ++$rownum;
                            $j = $i;
                            while ($j < $len) {
                                $excel_option_field = str_replace('option_','',$params['columns'][$j]['field']);
                                $value_option = ((isset($row_option[$excel_option_field]) ? $row_option[$excel_option_field] : ''));
                                $sheet->setCellValue($this->column($j, $rownum), $value_option);
                                ++$j;
                            }
                        }
                        $i = $j;
                    }
                }else{
                    $sheet->setCellValue($this->column($i, $rownum), $value);
                }

                ++$i;
            }
            ++$rownum;
        }

        //设置导出的excel样式
        $range1 = 'C2:J'.(count($list) + 1);
        $range2 = 'A1:B'.(count($list) + 1);
        $range3 = 'A2:B'.(count($list) + 1);
        $excel->getActiveSheet()->getProtection()->setSheet(true);
        $excel->getActiveSheet()->getStyle($range1)->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $excel->getActiveSheet()->getStyle($range2)->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle($range3)->getFont()->getColor()->setRGB('FF0000');

        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));
        $excel->getActiveSheet()->setTitle($params['title']);
        $filename = ($params['title'] . '-' . date('Y-m-d H:i', time()));
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$params['title'].'.xls"');
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


}
?>