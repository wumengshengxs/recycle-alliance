<?php


namespace gm\models;

use PHPExcel;

class Excel
{
    /**
     * @param $title =>'表格名称'
     * @param $header => '表格头标题'
     * @param $data => '数据'
     * @param $keys => '数据key'
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    static public function Export($title,$header,$data,$keys)
    {
        //引入核心文件
        $phpExcel = new PHPExcel();

        //定义配置
        $fileName = $title;//文件名称
        $header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        $excel =  $phpExcel->getActiveSheet();
        //处理数据
        $excel->setTitle($fileName);
        $j = 1;
        foreach ($header as $key=>$val){
            $excel->setCellValue($header_arr[$key].$j,$val);
        }

        $i = 2;
        foreach ($data as $k => $val ) {
            foreach ($keys as $key => $value){
                //这里是设置单元格的内容
                $excel->setCellValue($header_arr[$key].$i,$val[$value]);
                $excel->getStyle( $header_arr[$key].$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            }
            $i++;
        }

        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$fileName.'.xls"');
        header('Cache-Control:max-age=0');
        header('Cache-Control:max-age=1');
        header('Content-Type:application/download');
        header('Content-Type:application/force-download');
        header('Cache-Control:cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel5($phpExcel);
        $objWriter->save('php://output');
        exit;
    }

}