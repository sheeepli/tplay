<?php
// +----------------------------------------------------------------------
// | Tplay [ WE ONLY DO WHAT IS NECESSARY ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://tplay.pengyichen.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 听雨 < 389625819@qq.com >
// +----------------------------------------------------------------------


namespace app\admin\controller;

use app\admin\controller\Permissions;
use app\admin\model\Applet;
use app\admin\model\Webconfig as ModelWebconfig;
use \think\Db;

class Webconfig extends Permissions
{
    public function index()
    {
        $web_config = ModelWebconfig::get(['web' => 'web']);
        $this->assign('web_config', $web_config);
        return $this->fetch();
    }

    public function publish()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['name', 'require', '网站名称不能为空'],
                ['file_type', 'require', '上传类型不能为空'],
                ['file_size', 'require', '上传大小不能为空'],
            ]);
            //验证部分数据合法性
            if (!$validate->check($post)) {
                $this->error('提交失败：' . $validate->getError());
            }

            if (empty($post['is_log'])) {
                $post['is_log'] = 0;
            } else {
                $post['is_log'] = $post['is_log'];
            }

            if (false == ModelWebconfig::update($post, ['web' => 'web'])) {
                return $this->error('提交失败');
            } else {
                addlog();
                return $this->success('提交成功', 'admin/webconfig/index');
            }
        }
    }
    public function applet()
    {
        $applet = Applet::get(['applet' => 'applet']);
        $this->assign('applet', $applet);
        return $this->fetch();
    }
    public function edit_applet()
    {

        Applet::update($this->request->post(), ['applet' => 'applet']);
        return $this->success('提交成功', 'admin/webconfig/applet');
    }
}
