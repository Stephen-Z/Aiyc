<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Column_model Column_model
 * @property List_model List_model
 * @property Excel_model Excel_model
 * @property Collect_model Collect_model
 * @property Log_model Log_model
 * @property CI_Form_validation form_validation
 */

class Baidu extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Excel_model','Excel_model',true);
        $this->load->model('article/Collect_model','Collect_model',true);
        $this->load->model('system/Log_model','Log_model',true);
        $this->nav = 'baidu';

    }

	public function excel_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'excel';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $task=$this->get('task');
        $platform=$this->get('platform');
        $brand=$this->get('brand');
        $title=$this->get('title');
        $url=$this->get('url');
        $start_time=$this->get('startTime');
        $end_time=$this->get('endTime');


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params2($skipnum, $length);

        $data['page_parameter']='';

        $where=array();

        if(!empty($task)){
            $data['page_parameter'].='&task='.$task;
            $where['task']=$task;
        }

        if(!empty($platform)){
            $data['page_parameter'].='&platform='.$platform;
            $where['platform']=$platform;
        }

        if(!empty($brand)){
            $data['page_parameter'].='&brand='.$brand;
            $where['brand']=$brand;
        }

        if(!empty($title)){
            $data['page_parameter'].='&title='.$title;
            $where['title']=$title;
        }

        if(!empty($url)){
            $data['page_parameter'].='&url='.$url;
            $where['url']=$url;
        }

        if(!empty($start_time) or !empty($end_time)){
            if(empty($start_time)){
                $start_time=2010-01-01;
            }
            if(empty($end_time)){
                $end_time=2210-01-01;
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $data['page_parameter'].='startTime='.date('Y-m-d',$start_time).'&endTime='.date('Y-m-d',$end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        if(!empty($cid)){
            $where['brand_id']=intval($cid);
        }


        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->Excel_model->count_by($where);

        $rs = $this->Excel_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/article/excel_list',$data);
	}

    public function index_get()
    {
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'list';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $type=$this->get('type');
        $task=$this->get('task');
        $title=$this->get('title');
        $url=$this->get('url');
        $start_time=$this->get('startTime');
        $end_time=$this->get('endTime');

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $data['page_parameter']='';

        $where=array();

        if(!empty($type)){
            $data['page_parameter'].='&type='.$type;
            $where['type']=intval($type);
        }

        if(!empty($task)){
            $data['page_parameter'].='&task='.$task;
            $where['task']=$task;
        }

        if(!empty($title)){
            $data['page_parameter'].='&title='.$title;
            $where['title']=$title;
        }

        if(!empty($url)){
            $data['page_parameter'].='&url='.$url;
            $where['url']=$url;
        }

        if(!empty($start_time) or !empty($end_time)){
            if(empty($start_time)){
                $start_time=2010-01-01;
            }
            if(empty($end_time)){
                $end_time=2210-01-01;
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $data['page_parameter'].='startTime='.date('Y-m-d',$start_time).'&endTime='.date('Y-m-d',$end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $orderby_name='id';
        $orderby_value='DESC';

        //$count=$this->Collect_model->select('max(id) as id,task,title,url,release_time,type,search_time,platform,brand,sum(record_num) as record_num,sum(status) as status,created,updated,deleted')->group_by('task,url,created')->count_by($where);

        //echo $this->db->last_query();

        $count = count($this->Collect_model->select('max(id) as id,task,title,url,release_time,type,search_time,platform,brand,sum(record_num) as record_num,sum(status) as status,created,updated,deleted')->group_by('task,url,created')->order_by($orderby_name,$orderby_value)->get_many_by($where));


        $rs = $this->Collect_model->select('max(id) as id,task,title,url,release_time,type,search_time,platform,brand,sum(record_num) as record_num,sum(status) as status,created,updated,deleted')->group_by('task,url,created')->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        //echo $this->db->last_query();

        $data['rs']=$rs;

        //print_r($rs);

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/article/collect_list',$data);
    }

    public function delete_post() {

        if ($this->form_validation->run('deletes') == FALSE) {
            $error = $this->form_validation->error_array();
            $this->response(encode_validate_fail_message($error));
        }

        $ids = $this->post('ids');

        $this->db->trans_start();

        $result = $this->Collect_model->delete_many($ids);
        $this->db->trans_complete();

        $this->response(encode_update_message($result));
    }

    public function excel_delete_post() {

        if ($this->form_validation->run('deletes') == FALSE) {
            $error = $this->form_validation->error_array();
            $this->response(encode_validate_fail_message($error));
        }

        $ids = $this->post('ids');

        $this->db->trans_start();

        $result = $this->Excel_model->delete_many($ids);
        $this->db->trans_complete();

        $this->response(encode_update_message($result));
    }


    public function excel_create_post(){

        $data=$this->uploads('temp');

        /////////////////////////读取excel
        $uploadfile='./uploads/temp/'.$data['upload_data']['file_name'];//获取上传成功的Excel

        $g=$this->excel_fileput($uploadfile,$data,'abc');

        unlink($uploadfile);

        $postdata['nav'] = $this->nav;
        $postdata['child_nav'] = 'excel';

        $postdata['skip_url']='/article/baidu/excel';

        if($g){
            $this->load->view($this->template_patch.'/public/success',$postdata);

        }else{
            $this->load->view($this->template_patch.'/public/error',$postdata);
        }

    }

    private function uploads($path='',$filename='',$max_size='1000', $postname='file')
    {
        if ($path == '') {
            //$this->response(encode_exception_message('请设置上传文件名'));
            echo "<script>alert('请设置上传文件名');location.href='".base_url($this->patch."/article/baidu/excel")."';</script>";
        }
        $upload_parent_path = './uploads/';
        $config['upload_path'] = $upload_parent_path . $path;
        $config['allowed_types'] = 'xls|xlsx|xl';
        $config['max_size'] = $max_size;
        if($filename!=''){
            $config['file_name']=$filename;
            $config['overwrite']=TRUE;
        }else {
            $config['file_name'] = time().rand(100, 999);
        }

        if (!is_writable($upload_parent_path)) {
            //$this->response(encode_exception_message('目录没有可写属性'));
            echo "<script>alert('目录没有可写属性');location.href='".base_url($this->patch."/article/baidu/excel")."';</script>";
        }

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($postname)) {
            $error = $this->upload->display_errors('','');
            //$this->response(encode_exception_message($error));
            echo "<script>alert('{$error}');location.href='".base_url($this->patch."/article/baidu/excel")."';</script>";
        } else {
            $data = array('upload_data' => $this->upload->data());
            return $data;
            //$this->response(encode_success_message('/uploads/'.$path.'/'.$data['upload_data']['file_name'], 'object'));
        }
    }

    private function excel_fileput($filePath,$data,$tablename){
        $this->load->library('PHPExcel');
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }
        // 加载excel文件
        $PHPExcel = $PHPReader->load($filePath);

        // 读取excel文件中的第一个工作表
        $currentSheet = $PHPExcel->getSheet(0);
        // 取得最大的列号
        $allColumn = $currentSheet->getHighestColumn();
        // 取得一共有多少行
        $allRow = $currentSheet->getHighestRow();
        $highestColumm = $currentSheet->getHighestColumn(); // 取得总列数
        $colsNum= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27

        /** 循环读取每个单元格的数据 */
        $i=0;
        for ($row = 2; $row <= $allRow; $row++){//行数是以第1行开始
            for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                $dataset[$i][] = $currentSheet->getCell($column.$row)->getValue();
                //echo $column.$row.":".$currentSheet->getCell($column.$row)->getValue()."<br />";
            }
            $i++;
        }

        for($i=0;$i<count($dataset);$i++){
            for($j=0;$j<count($dataset[$i]);$j++){
                switch($j){
                    case 0 :
                        $txt='task';
                        break;
                    case 1 :
                        $txt='platform';
                        break;
                    case 2 :
                        $txt='brand';
                        break;
                    case 3 :
                        $txt='title';
                        break;
                    case 4 :
                        $txt='url';
                        break;
                    case 5 :
                        $txt='release_time';
                        break;
                }
                $goods_data[$i][$txt]=$dataset[$i][$j];
            }
        }

        $i=0;
        foreach($goods_data as $goods_data_row){
            $goods_data[$i]['release_time']=($goods_data[$i]['release_time']-25569)*24*60*60;
            if(empty($goods_data_row['platform']) or empty($goods_data_row['brand']) or empty($goods_data_row['task'])){
                unset($goods_data[$i]);
            }
            //$insert_id=$this->Excel_model->insert($goods_data_row);
            $i++;
        }

        $this->Excel_model->update_all(array('deleted'=>1));

        foreach($goods_data as $goods_data_row){
            $insert_id=$this->Excel_model->insert($goods_data_row);
            $log_data['name']=$_SESSION['admin']['name'];
            $log_data['event']='导入任务【'.$goods_data_row['task'].'】- 导入链接：'.$goods_data_row['url'];
            $log_data['ip']=$this->input->ip_address();;
            $this->Log_model->insert($log_data);
        }

        return true;

    }

    public function go_post(){

        $ids=$this->post('ids');
        if($ids){
            $num='';
            foreach($ids as $ids_row){
                $num.=' '.$ids_row;
            }
            $rs=shell_exec("/opt/bdtool/b2.sh {$num}");
            if($rs){
                $rs=json_decode($rs,true);
                $this->response(encode_success_message($rs,'object'));
            }else{
                $this->response(encode_exception_message('系统繁忙'));
            }
        }
        //

        //echo $rs;
    }

    public function export_get(){

        $task=$this->get('task');
        $type=$this->get('type');
        $title=$this->get('title');
        $url=$this->get('url');
        $start_time=$this->get('startTime');
        $end_time=$this->get('endTime');

        $where=array();

        if(!empty($task)){
            $where['task']=$task;
        }

        if(!empty($type)){
            $where['type']=intval($type);
        }

        if(!empty($title)){
            $where['title']=$title;
        }

        if(!empty($url)){
            $where['url']=$url;
        }

        if(!empty($start_time) or !empty($end_time)){
            if(empty($start_time)){
                $start_time=2010-01-01;
            }
            if(empty($end_time)){
                $end_time=2210-01-01;
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $orderby_name='id';
        $orderby_value='DESC';


        $rs = $this->Collect_model->select('max(id) as id,task,title,url,release_time,type,search_time,platform,brand,sum(record_num) as record_num,sum(status) as status,created,updated,deleted')->group_by('task,url,created')->order_by($orderby_name,$orderby_value)->get_many_by($where);

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=collect.xls");

        echo    iconv("UTF-8", "GBK", "任务")."\t";
        echo    iconv("UTF-8", "GBK", "标题")."\t";
        echo   iconv("UTF-8", "GBK", "URL")."\t";
        echo   iconv("UTF-8", "GBK", "平台")."\t";
        echo   iconv("UTF-8", "GBK", "品牌")."\t";
        echo   iconv("UTF-8", "GBK", "发布时间")."\t";
        echo   iconv("UTF-8", "GBK", "搜索时间")."\t";
        //echo   iconv("UTF-8", "GBK", "来源")."\t";
        echo   iconv("UTF-8", "GBK", "返回记录")."\t";
        echo   iconv("UTF-8", "GBK", "收录结果")."\t";
        echo   "\n";

        foreach($rs as $rs_row){
            if($rs_row['type']==1){
                $rs_row['type']='新闻';
            }else{
                $rs_row['type']='网页';
            }
            if($rs_row['status']==1){
                $rs_row['status']='收录';
            }else{
                $rs_row['status']='未收录';
            }
            $rs_row['title']=str_replace("\n","",$rs_row['title']);
            $rs_row['title']=str_replace("\t","",$rs_row['title']);
            $rs_row['title']=str_replace("\r\n","",$rs_row['title']);
            $rs_row['title']=str_replace("/\s/","",$rs_row['title']);
            $rs_row['title']=str_replace("\r","",$rs_row['title']);

            $rs_row['url']=str_replace("\n","",$rs_row['url']);
            $rs_row['url']=str_replace("\t","",$rs_row['url']);
            $rs_row['url']=str_replace("\r\n","",$rs_row['url']);
            $rs_row['url']=str_replace("/\s/","",$rs_row['url']);
            $rs_row['url']=str_replace("\r","",$rs_row['url']);

            $rs_row['platform']=str_replace("\n","",$rs_row['platform']);
            $rs_row['platform']=str_replace("\t","",$rs_row['platform']);
            $rs_row['platform']=str_replace("\r\n","",$rs_row['platform']);
            $rs_row['platform']=str_replace("/\s/","",$rs_row['platform']);
            $rs_row['platform']=str_replace("\r","",$rs_row['platform']);

            echo iconv("UTF-8", "GBK", $rs_row['task'])."\t";
            //echo iconv("UTF-8", "GBK", $rs_row['title'])."\t";
            echo mb_convert_encoding($rs_row['title'],'GBK','UTF-8');
            echo iconv("UTF-8", "GBK", $rs_row['url'])."\t";
            echo iconv("UTF-8", "GBK", $rs_row['platform'])."\t";
            echo iconv("UTF-8", "GBK", $rs_row['brand'])."\t";
            echo date('Y-m-d',$rs_row['release_time'])."\t";
            echo date('Y-m-d',$rs_row['search_time'])."\t";
            //echo iconv("UTF-8", "GBK", $rs_row['type'])."\t";
            echo iconv("UTF-8", "GBK", $rs_row['record_num'])."\t";
            echo iconv("UTF-8", "GBK", $rs_row['status'])."\t";
            echo   "\n";
        }
    }

}
