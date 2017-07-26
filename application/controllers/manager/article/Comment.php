 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:04 PM
 * class for 文章评论
 */
class Comment extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='my_mission';
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Comment_model','Comment_model',true);
        $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
        $this->load->model('dispatch/Replydispatch_model','Replydispatch_model',true);
    }

    public function index_get(){
        $data=array();
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='article_comment';

        $cnrs=array('name' => '文章评论');
        $data['cnrs']=$cnrs;

        //prepare data
        $orderby_name='Dcreated';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $where=array();
        $where['member_id']=$_SESSION['admin']['id'];
        $where['operation']=0;


        $tmprs=$this->Dispatch_model->get_many_by($where);
        $count=count($tmprs);

        $rs=$this->Dispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_article($where['member_id'],0);

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/article_comment',$data);
    }

    //发表评论 stephen 2017-05-06
    public function create_post(){
        $articleId = $this->input->post('articleid');
        $comment_content = $this->input->post('content');
        $isReply=$this->input->post('isreply');
        $user_id = $_SESSION['admin']['id'];
        $task_id=$this->input->post('taskid');


        $data=array();
        $data['article_id']=$articleId;
        $data['content']=$comment_content;
        $data['user_id']=$user_id;
        $data['is_reply']=$isReply;
        $data['task_id']=$task_id;
        if($isReply==1){
            $data['reply_id']=$this->input->post('reply_id');
        }

        $is_exist=$this->Comment_model->count_by(array('task_id'=>$task_id,'is_reply'=>$isReply));
        if($is_exist==0){
            if($this->Comment_model->insert($data)){
                //修改回复数
                if($isReply==0){
                    $where=array();
                    $where['id']=$articleId;
                    $tempdata=$this->List_model->get_by($where);
                    $reply=$tempdata['reply'];
                    $preReply=$reply;
                    $reply += 1;
                    $updateData=array();
                    $updateData['pre_reply']=$preReply;
                    $updateData['reply']=$reply;
                    $this->List_model->update_by($where,$updateData);
                }

                $update_data=array();
                $update_data['member_commit']=time();
                $update_data['task_done']=1;
                if($isReply==0){
                    $this->Dispatch_model->update_by(array('member_id' => $_SESSION['admin']['id'],'id'=>$task_id),$update_data);
                }
                else{
                    $this->Replydispatch_model->update_by(array('member_id' => $_SESSION['admin']['id'],'id'=>$task_id),$update_data);
                }
                echo 1;
            }else{
                echo 0;
            }
        }
        else{
            if($this->Comment_model->update_by(array('task_id'=>$task_id,'is_reply'=>$isReply),array('content'=>$comment_content))){
                $update_data=array();
                $update_data['member_commit']=time();
                $update_data['task_done']=1;
                if($isReply==0){
                    $this->Dispatch_model->update_by(array('member_id' => $_SESSION['admin']['id'],'id'=>$task_id),$update_data);
                }
                else{
                    $this->Replydispatch_model->update_by(array('member_id' => $_SESSION['admin']['id'],'id'=>$task_id),$update_data);
                }
                echo 1;
            }
            else{
                echo 0;
            }
        }


    }

    public function commentlist_get(){
        $data=array();

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='article_commentList';

        $cnrs=array('name' => '我的评论');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='Did';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $user_id = $_SESSION['admin']['id'];
        $count=$this->Comment_model->count_by(array('user_id' => $user_id));
        $rs=$this->Comment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->member_article_allcomment($user_id);
        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/comment_list',$data);
    }

    public function replycommentlist_get(){
        $data=array();

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='article_replycommentList';

        $cnrs=array('name' => '我的回复评论');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='site_task_article_comment.id';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $user_id = $_SESSION['admin']['id'];
        $where=array();
        $where['is_reply']=1;
        $count=$this->Comment_model->count_by($where);
        $rs=$this->Comment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_reply($user_id);
        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/replycomment_list',$data);
    }





    public function admincommentlist_get(){
        $data=array();

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']='dispatch_system';
        $data['child_nav']='article_commentList';

        $cnrs=array('name' => '我的评论');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='Did';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        //$user_id = $_SESSION['admin']['id'];
        $where=array('is_reply'=>0);
        $count=$this->Comment_model->count_by($where);
        $rs=$this->Dispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->admin_join_comment();
        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/admincomment_list',$data);
    }

    public function adminreplylist_get(){
        $data=array();

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']='dispatch_system';
        $data['child_nav']='article_replycommentList';

        $cnrs=array('name' => '我的回复评论');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='site_task_article_comment.id';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        //$user_id = $_SESSION['admin']['id'];
        $where=array('is_reply'=>1);
        $count=$this->Comment_model->count_by($where);
        $rs=$this->Comment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->admin_join_reply();
        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/comment_adminreplylist',$data);
    }



    public function updatestatus_post(){
//        $articleID=$this->input->post('articleid');
//        $userID=$this->input->post('user_id');
        $status=$this->input->post('status');
        $task_id=$this->input->post('task_id');

        $cuswhere=array();
//        $cuswhere['user_id']=$userID;
//        $cuswhere['article_id']=$articleID;
        $cuswhere['task_id']=$task_id;

        $post_data=array();
        $post_data['comment_status']=$status;

        if($this->Comment_model->update_by($cuswhere,$post_data)){
            $update_data=array();
            $update_data['admin_commit']=time();
            $update_data['task_done']=$status;
            $this->Dispatch_model->update_by(array('id'=>$task_id),$update_data);
            echo 1;
        }
        else{
            echo 0;
        }
    }


    public function updatereplystatus_post(){
        $articleID=$this->input->post('articleid');
        $userID=$this->input->post('user_id');
        $status=$this->input->post('status');
        //$task_id=$this->input->post('task_id');

        $cuswhere=array();
        //$cuswhere['user_id']=$userID;
        $cuswhere['id']=$userID;

        $post_data=array();
        $post_data['comment_status']=$status;

        if($this->Comment_model->update_by($cuswhere,$post_data)){
            $update_data=array();
            $update_data['admin_commit']=time();
            $update_data['task_done']=$status;
            $this->Replydispatch_model->update_by(array('id'=>$articleID),$update_data);
            echo 1;
        }
        else{
            echo 0;
        }
    }



    public function onlinecomment_get(){
        $data=array();
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']='article';
        $data['child_nav']='article_commentList';

        $cnrs=array('name' => '评论列表');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='order_id';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        //$user_id = $_SESSION['admin']['id'];
        $count=$this->Onlinecomment_model->count_all();
        $rs=$this->Onlinecomment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_article();
        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/comment_onlinecomment',$data);
    }


    public function reply_get(){
        $data=array();
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='reply_comment';

        $cnrs=array('name' => '回复评论');
        $data['cnrs']=$cnrs;

        //prepare data
        $orderby_name='reply_dispatch.created';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $where=array();
        $where['member_id']=$_SESSION['admin']['id'];
        $where['operation']=0;

        $tmprs=$this->Replydispatch_model->get_many_by($where);
        $count=count($tmprs);

        $rs=$this->Replydispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_comment($where['member_id'],0);

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/article_replycomment',$data);
    }
}