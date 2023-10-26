<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel {

    public function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = '.$id;
        $user = $this->select($sql);

        return $user;
    }

    public function findUser($keyword) {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %'.$keyword.'%'. ' OR user_email LIKE %'.$keyword.'%';
        $user = $this->select($sql);

        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
    public function auth($userName, $password) {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM users WHERE name = "' . $userName . '" AND password = "'.$md5Password.'"';

        $user = $this->select($sql);
        return $user;
    }

    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id) {
        $sql = 'DELETE FROM users WHERE id = ?';
        $params = [$id];
        $this->delete($sql, $params);
    }
    
    

    /**
     * Update user
     * @param $input
     * @return mixed
     */
    public function updateUser($input, $currentUserId) {
        // Kiểm tra xem người dùng hiện tại có quyền cập nhật thông tin người dùng này hay không
        $sql = 'UPDATE users SET 
                 name = ?,
                 fullname = ?,
                 email = ?,
                 password = ?
                WHERE id = ? AND id = ?';
    
        $params = [
            $input['name'],
            $input['fullname'],
            $input['email'],
            md5($input['password']),
            $input['id'],
            $currentUserId // Chúng ta kiểm tra ID người dùng hiện tại
        ];
    
        $user = $this->update($sql, $params);
    
        return $user;
    }
    

    /**
     * Insert user
     * @param $input
     * @return mixed
     */
    public function insertUser($input) {
        $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (?, ?)";
        $params = [$input['name'], md5($input['password'])];
        $user = $this->insert($sql, $params);
        return $user;
    }
    

    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = []) {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] .'%"';

            //Keep this line to use Sql Injection
            //Don't change
            //Example keyword: abcef%";TRUNCATE banks;##
            $users = self::$_connection->multi_query($sql);

            //Get data
            $users = $this->query($sql);
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }

        return $users;
    }
}