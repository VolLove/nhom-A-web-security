<?php
require_once 'configs/database.php';

abstract class BaseModel {
    // Database connection
    protected static $_connection;

    public function __construct() {

        if (!isset(self::$_connection)) {
            self::$_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            if (self::$_connection->connect_errno) {
                printf("Connect failed");
                exit();
            }
        }

    }

    /**
     * Query in database
     * @param $sql
     * @param $params (optional)
     */
    protected function query($sql, $params = []) {
        if (!empty($params)) {
            $stmt = self::$_connection->prepare($sql);
            if (!$stmt) {
                return false; // Xử lý lỗi khi chuẩn bị câu lệnh
            }

            // Bind tham số
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);

            // Thực hiện truy vấn
            $result = $stmt->execute();

            // Kiểm tra kết quả
            if ($result === false) {
                return false; // Xử lý lỗi khi thực hiện truy vấn
            }
        } else {
            $result = self::$_connection->query($sql);
        }

        return $result;
    }

    /**
     * Select statement
     * @param $sql
     * @param $params (optional)
     */
    protected function select($sql, $params = []) {
        $result = $this->query($sql, $params);
        $rows = [];
        if (!empty($result)) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Delete statement
     * @param $sql
     * @param $params
     * @return bool
     */
    protected function delete($sql, $params) {
        return $this->query($sql, $params) !== false;
    }

    /**
     * Update statement
     * @param $sql
     * @param $params
     * @return bool
     */
    protected function update($sql, $params) {
        return $this->query($sql, $params) !== false;
    }

    /**
     * Insert statement
     * @param $sql
     * @param $params
     * @return bool
     */
    protected function insert($sql, $params) {
        return $this->query($sql, $params) !== false;
    }
}
?>
