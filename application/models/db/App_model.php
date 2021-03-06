<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 15:44
 */
include_once 'DB_model.php';

class App_model extends DB_model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $app_id 可以是微信的APPKEY或者是应用的ID
     * @return mixed
     */
    public function get_app($app_id)
    {
        $query = $this->db->where('id', $app_id)->get(self::TABLE_APP);
        return $query->row_array();
    }

    /**
     * 通过邮箱地址查询一条记录
     * @param $email
     * @return mixed
     */
    public function get_by_email($email)
    {
        return $this->db->get_where(self::TABLE_APP, ['email' => $email], 1)->row_array();
    }


    public function update_key($id, $data)
    {
        $update_data['wx_key'] = trim($data['wxkey']);
        $update_data['wx_secret'] = trim($data['wxsecret']);
        $update_data['tb_pid'] = trim($data['tbpid']);
        $update_data['dtk_key'] = trim($data['dtkkey']);
        $update_data['update_time'] = mktime();
        return $this->db->update(self::TABLE_APP, $update_data, ['id' => $id]);
    }

    /**
     * 更新微信授权访问令牌和刷新令牌
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update_token($id, $data)
    {
        $arr['authorizer_access_token'] = $data['authorizer_access_token'];
        $arr['authorizer_refresh_token'] = $data['authorizer_refresh_token'];
        $arr['expires_in'] = $data['expires_in'];
        $arr['authorizer_appid'] = $data['authorizer_appid'];

        $json = json_encode($arr);

        $update_data['wx_authorizer_access_token'] = $json;
        return $this->db->update(self::TABLE_APP, $update_data, ['id' => $id]);
    }

    public function update_tb_token($id, $token_json_str)
    {
        return $this->db->update(self::TABLE_APP,
            [
                'tb_access_token'=>$token_json_str
            ],
            ['id' => $id]
        );
    }

    /**
     * 新建一个APP记录，成功返回新增的ID，否则返回FALSE
     * @param $email
     * @param $password
     * @return mixed
     */
    public function add_app($email, $password)
    {
        $this->load->helper('string');
        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]),
            'app_key' => random_string('md5'),
            'create_time' => mktime(),
            'update_time' => mktime()
        ];
        if ($this->db->insert(self::TABLE_APP, $data) === TRUE) {
            return $this->db->insert_id();
        }
        return FALSE;

    }

    /**
     * 验证用户名和密码是否正确，如果正确则返回array，如果不匹配则返回FALSE,如果不存在邮箱则返回NULL
     * @param $email
     * @param $password
     * @param bool $origin 指示是否原始字符串比较
     * @return array|bool|null
     */
    public function check_email_pwd($email, $password, $origin = FALSE)
    {
        $row = $this->db->get_where(self::TABLE_APP, ['email' => $email], 1)->row_array();
        if ($row && $row['password']) {
            if ($origin) {
                //原始比较密码
                if ($row['password'] === $password) {
                    unset($row['password']);
                    return $row;
                }
            } else {
                //加密后校对
                if (password_verify($password, $row['password'])) {
                    unset($row['password']);
                    return $row;
                }
            }
            //密码不匹配
            return FALSE;
        }
        //无该email记录
        return NULL;
    }

    /**
     * 修改密码，成功返回TRUE，否则返回FALSE
     * @param $app_id
     * @param $new_pwd
     * @return mixed
     */
    public function modify_pwd($app_id, $new_pwd)
    {
        return $this->db->where('id', $app_id)->update(self::TABLE_APP, [
            'password' => password_hash($new_pwd, PASSWORD_BCRYPT, ['cost' => 10])
        ]);
    }

    public function refresh_token()
    {
        echo 'ok';
        die;
    }

}