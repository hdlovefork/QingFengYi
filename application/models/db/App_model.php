<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 15:44
 */
require_once('DB_model.php');

class App_model extends DB_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_app($app_key)
    {
        $query = $this->db->limit(1)->where('app_key', $app_key)->get(self::TABLE_APP);
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
                if($row['password'] === $password){
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
    public function modify_pwd($app_id,$new_pwd){
        return $this->db->where('id',$app_id)->update(self::TABLE_APP,[
            'password'=>password_hash($new_pwd, PASSWORD_BCRYPT,['cost'=>10])
        ]);
    }


}