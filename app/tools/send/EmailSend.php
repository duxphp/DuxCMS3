<?php
namespace app\tools\send;
/**
 * 邮件发送服务
 */
class EmailSend extends \app\base\service\BaseService {

    /**
     * 检查数据
     * @param $data
     * @return bool
     */
    public function check($data) {
        if($data['user_status']) {
            return $this->success();
        }
        if (!filter_var($data['receive'], \FILTER_VALIDATE_EMAIL)) {
            return $this->error('邮箱账号不正确');
        }
        return $this->success();
    }

    /**
     * 发送服务
     * @param $info
     * @return bool
     */
    public function send($info) {
        $config = target('tools/ToolsSendConfig')->getConfig('email');
        if(empty($config)){
            return $this->error('配置不存在!');
        }
        $receive = $info['receive'];
        if($info['user_info']) {
            $receive = $info['user_info']['email'];
        }
        //配置项目
        $mailConfig = array(
            'smtp_host'      => $config['smtp_host'],
            'smtp_port'      => $config['smtp_port'],
            'smtp_ssl'       => intval($config['smtp_ssl']),
            'smtp_username'  => $config['smtp_username'],
            'smtp_password'  => $config['smtp_password'],
            'smtp_from_to'   => $config['smtp_from_to'],
            'smtp_from_name' => $config['smtp_from_name'],
        );
        $email = new \dux\lib\Email($mailConfig);
        $status = $email->setMail($info['title'], html_out($info['content']))->sendMail($receive);
        if(!$status){
            return $this->error($email->getError());
        }else{
            return $this->success();
        }

    }

}