<?php
namespace controller\admin;

use util\Administrator;
use common\Db;
use daos\Setting;
use daos\SettingDao;

class Settings extends Administrator  {

    public $_msg = [
        'name'=>'请填写正确的姓名',
        'name.chs'=>'必须是中文'
    ];

    public $_rule = [
        'name' => 'chs|require|max:5'
    ];


    public function index() {
        $data['title'] = '站点设置';
        $data['csrf_name']  = $this->security->get_csrf_token_name();
        $data['csrf_token'] = $this->security->get_csrf_hash();
        $this->assign($data);
        $this->display();
    }

    public function topic() {
        $this->display();
    }

    public function user() {
        $this->display();
    }

    public function test() {
        $this->display('setweb');
    }

    //基本设置
    public function basic(){
        ed($_POST);
        $set = $this->form(
            'site_name',
            'welcome_tip',
            'short_intro',
            'show_captcha',
            'site_run',
            'site_stats',
            'site_keywords',
            'site_description',
            'reward_title',
            'is_rewrite',
            'show_captcha',
            'site_close',
            'site_close_msg',
            'basic_folder',
            'static',
            'themes',
            'logo',
            'auto_tag',
            'encryption_key'
        );
        $logo = pathinfo($set['logo']);
        if(isset($logo['extension'])) {
            if (in_array(strtolower($logo['extension']), ['gif', 'png', 'jpg', 'jpeg'])) {
                $set['logo'] = '<img src=' . $set['logo'] . '>';
            }
        }
        //SettingDao::obj()->adds($set);
        SettingDao::obj()->update($set);
        show_message('话题设定更新成功#tab1', site_url('admin/siteset'), 1);
    }

    //话题设定
    public function topicset($per_page_num=10,$home_page_num=20,$timespan=0,$words_limit=5000) {
        $set = $this->form(
            'comment_order',
            'is_approve'
        );
        $set['per_page_num']=$per_page_num;
        $set['home_page_num']=$home_page_num;
        $set['timespan']=$timespan;
        $set['words_limit']=$words_limit;
        //SettingDao::obj()->adds($set);
        SettingDao::obj()->update($set);
        show_message('话题设定更新成功', '/admin/siteset#tab2', 1);
    }

    //会员设定
    public function userset(){
        $set = $this->form(
            'credit_start',
            'credit_login',
            'credit_post',
            'credit_reply',
            'credit_reply_by',
            'credit_del',
            'credit_follow',
            'disabled_username'
        );
        //SettingDao::obj()->adds($set);
        SettingDao::obj()->update($set);
        show_message('会员设定更新成功', '/admin/siteset#tab3', 1);
    }

    //mailset设定
    public function mailset() {
        $set = $this->form(
            'protocol',
            'smtp_host',
            'smtp_port',
            'smtp_user',
            'smtp_pass',
            'mail_reg'
        );
        SettingDao::obj()->setValue('mail',$set);
        show_message('邮件配置更新成功', '/admin/siteset#tab4', 1);
    }

    //routes
    public function route() {
        $rule = $this->form(
            'default_controller',
            'node_show_url',
            'view_url',
            'tag_url'
        );
        SettingDao::obj()->setValue('route',$rule);
        show_message('自定义url更新成功', '/admin/siteset#tab5', 1);
        str_replace([
            '{cid}',
            '{slug}',
            '{category}',
            '{directory}',
            '{year}',
            '{month}',
            '{day}',
            '{mid}'
        ],[
            '[cid:digital]',
            '[slug]',
            '[category]',
            '[directory:split:0]',
            '[year:digital:4]',
            '[month:digital:2]',
            '[day:digital:2]',
            '[mid:digital]'
        ], $rule);

        $routes = "<?php \n\n";
        $routes .= "\$route['default_controller'] = '" . $this->input->post('default_controller') . "';\n";
        $routes .= "\$route['404_override'] = '';\n";
        $routes .= "\$route['admin']='/admin';\n";
        $routes .= "\$route['add.html']='topic/add';\n";
        $routes .= "\$route['qq_login'] = 'oauth/qqlogin';\n";
        $routes .= "\$route['qq_callback'] = 'oauth/qqcallback';\n";
        $routes .= "\$route['" . $this->input->post('node_show_url') . "'] = 'node/show/$1';\n";
        $routes .= "\$route['" . $this->input->post('view_url') . "'] = 'topic/show/$1';\n";
        $routes .= "\$route['" . $this->input->post('tag_url') . "'] = 'tag/show/$1';\n";

        if (write_file(APPPATH . 'config/routes.php', $routes)) {
            show_message('自定义url更新成功', '/admin/site_settings#tab5', 1);
        }
    }

    //存储设定
    public function storage() {
        $this->config->update('qiniu', 'storage_set', $this->input->post('storage_set'));
        $this->config->update('qiniu', 'accesskey', $this->input->post('accesskey'));
        $this->config->update('qiniu', 'secretkey', $this->input->post('secretkey'));
        $this->config->update('qiniu', 'bucket', $this->input->post('bucket'));
        $this->config->update('qiniu', 'file_domain', prep_url($this->input->post('file_domain')));
        show_message('存储配置更新成功', '/admin/siteset', 1);
    }
}