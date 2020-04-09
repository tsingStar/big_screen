<?php

/**
 * 静态保存数据库链接，避免创建过多的链接
 */
class MysqliConnection
{
    static public $link = null;
    static private $_db = null;

    static public function newlink()
    {
        self::initdbinfo();
        self::$link = mysqli_connect(self::$_db['host'], self::$_db['user'], self::$_db['password'], self::$_db['dbname'], self::$_db['port']) or die('数据库服务器连接错误:' . mysqli_connect_errno() . mysqli_connect_error());
        $db_encode = 'utf8';
        mysqli_query(self::$link, "set names '$db_encode'");
    }

    static private function initdbinfo()
    {
        if (self::$_db == null) {
            include dirname(__FILE__) . '/../data/config.php';
            self::$_db = array(
                'host' => $host,
                'user' => $user,
                'password' => $pwd,
                'dbname' => $dbname,
                'port' => $port
            );
        }
    }

    static public function getlink()
    {
        if (self::$link == null) {
            self::newlink();
        }
        return self::$link;
    }

    static public function close()
    {
        mysqli_close(self::$link);
        self::$link = null;
        return null;
    }
}

class M
{

    private $link; //数据库连接
    private $table; //表名
    private $prefix; //表前缀
    private $db_configarray; //数据库配置

    /**
     * 参数:表名 数据库配置数组 或 数据库配置文件路径
     * @param $table
     * @param string $db_config_arr_path
     */
    function __construct($table)
    {

        $this->conn();
        $this->table = $this->prefix . $table;
    }

    /**
     * 连接数据库
     */
    private function conn()
    {
        $this->prefix = 'weixin_';
        $this->link = MysqliConnection::getlink();
    }

    /**
     * 数据查询
     * 参数:sql条件 查询字段 使用的sql函数名
     * @param string $where
     * @param string $field
     * @param string $fun
     * @return array
     * 返回值:结果集 或 结果(出错返回空字符串)
     */
    public function select($where = '1', $field = "*", $fun = '', $type = 'assoc', $join = '')
    {
        $rarr = array();
        if (empty($fun)) {
            $sqlStr = "select $field from $this->table $join where $where";
            $rt = mysqli_query($this->link, $sqlStr);
            if (!$rt) {
                return '';
            }
            if ($type == "row") {
                while ($arr = mysqli_fetch_row($rt)) {
                    array_push($rarr, $arr);
                }
            } else {
                while ($arr = mysqli_fetch_assoc($rt)) {
                    array_push($rarr, $arr);
                }
            }

        } else {
            $rarr = $this->find($where, $field, $fun, $type, $join);
        }
        return $rarr;
    }

    /**
     * 数据查询
     * 参数:sql条件 查询字段 使用的sql函数名
     *
     * @param string $where
     * @param string $field
     * @param string $fun
     *
     * @return array 返回值:结果集 或 结果(出错返回空字符串)
     */
    public function find($where = '1', $field = "*", $fun = '', $type = 'assoc', $join = '')
    {
        $rarr = array();
        if (empty($fun)) {
            $sqlStr = "select $field from $this->table $join where $where ";
        } else {
            $sqlStr = "select $fun($field) as rt from $this->table $join where $where ";
        }
        $rt = mysqli_query($this->link, $sqlStr);
        if (!$rt) {
            return '';
        }
        if ($type == "row") {
            $rarr = mysqli_fetch_row($rt);
        } else {
            $rarr = mysqli_fetch_assoc($rt);
        }
        $rarr = empty($fun) ? $rarr : $rarr['rt'];
        return $rarr;
    }

    public function findarray($where = '1', $field = "*", $join = '')
    {
        $sqlStr = "select $field from $this->table $join where $where";
        $result = mysqli_query($this->link, $sqlStr);
        $arr = mysqli_fetch_array($result);
        return $arr;
    }
    /**
     * 数据更新
     * 参数:sql条件 要更新的数据(字符串 或 关联数组)
     *
     * @param mixed $where 查询条件
     * @param array $data 更新的数据
     *
     * @return bool
     * 返回值:语句执行成功或失败,执行成功并不意味着对数据库做出了影响
     */
    public function update($where, $data)
    {
        $ddata = '';
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $str_v = $v === null ? 'null' : "'" . strval($v) . "'";
                $ddata .= ',' . strval($k) . '=' . $str_v;
            }
            $ddata = ltrim($ddata, ',');
        } else {
            $ddata = $data;
        }
        $sqlStr = "update $this->table set $ddata where $where";
        // echo $sqlStr;
        return mysqli_query($this->link, $sqlStr);
    }
    /**
     * 插入多行数据
     *
     * @param array $rows 数据
     * @return void
     */
    public function addMutiRows($rows){
        if(count($rows)<=0){
            return false;
        }
        $keys=array_keys($rows[0]);
        $value_str='';
        for($i=0,$l=count($rows);$i<$l;$i++){
            $str='';
            for($j=0,$len=count($keys);$j<$len;$j++){
                $str.=',"'.$rows[$i][$keys[$j]].'"';
                
            }
            $str=ltrim($str,',');
            $value_str.=',('.$str.')';
        }
        $value_str=ltrim($value_str,',');

        foreach($keys as $k=>$v){
            $keys[$k]='`'.$v.'`';
        }
        $keys_str=' ('.implode(',',$keys).') ';
        
        $sqlStr='insert into '.$this->table.$keys_str.' values '.$value_str.';';
        if (mysqli_query($this->link,$sqlStr)) {
            return true;
        }
        return false;
    }
    /**
     * 数据添加
     * 参数:数据(数组 或 关联数组 或 字符串)
     * @param $data
     * @return int
     * 返回值:插入的数据的ID 或者 0
     */
    public function add($data)
    {
        if (is_array($data)) {
            $field = $idata = '';
            $diff_arr = array_diff_assoc(array_keys($data), range(0, count($data) - 1));
            $is_assoc = empty($diff_arr) ? false : true;
            if ($is_assoc) {
                //关联数组
                foreach ($data as $k => $v) {
                    $str_v = $v === null ? 'null' : "'" . strval($v) . "'";
                    $field .= ',' . strval($k);
                    $idata .= ',' . $str_v;
                }
                $field = ltrim($field, ',');
                $idata = ltrim($idata, ',');
                $sqlStr = "insert into $this->table($field) values ($idata)";
            } else {
                //非关联数组 或字符串
                foreach ($data as $k => $v) {
                    $str_v = $v === null ? 'null' : "'" . strval($v) . "'";
                    $idata .= ',' . $str_v;
                }
                $idata = ltrim($idata, ',');
                $sqlStr = "insert into $this->table values ($idata)";
            }
        } else {
            //为字符串
            $idata = $data;
            $sqlStr = "insert into $this->table values ($idata)";
        }
        if (mysqli_query($this->link, $sqlStr)) {
            return mysqli_insert_id($this->link);
        }
        return 0;
    }

    public function insert_id()
    {
        return mysqli_insert_id($this->link);
    }

    /**
     * 执行sql语句
     *
     */
    public function query($sql)
    {
        return mysqli_query($this->link, $sql);
    }


    public function fetch_array($result)
    {
        $rarr = array();
        while ($result && $arr = mysqli_fetch_assoc($result)) {
            array_push($rarr, $arr);
        }
        return $rarr;
    }

    public function first_row($result)
    {
        $data = mysqli_fetch_assoc($result);
        return $data;
    }

    /**
     * 数据删除
     * 参数:sql条件
     * @param $where
     * @return bool
     */
    public function delete($where)
    {
        $sqlStr = "delete from $this->table where $where";
        return mysqli_query($this->link, $sqlStr);// or die(mysqli_error($this->link));
    }

    public function begin_transaction()
    {
        return mysqli_begin_transaction($this->link);
    }

    public function commit()
    {
        mysqli_commit($this->link);
    }

    public function rollback()
    {
        mysqli_rollback($this->link);
    }

    public function autocommit($mode = true)
    {
        mysqli_autocommit($this->link, $mode);
    }

    /**
     * 显示数据库
     * 参数:sql条件
     * @param $sql sql语句
     * @return array
     * 返回值:结果集 或 结果(出错返回空字符串)
     */
    public function showdatabase($sql)
    {

    }

    /**
     * 关闭MySQL连接
     * @return bool
     */
    public function close()
    {
        $this->link = MysqliConnection::close();
        return $this->link;
    }

    /**
     * 执行会返回结果行数的操作，包含update,insert,delete
     * @param $sql
     * @return bool|int
     */
    public function executeUpdate($sql)
    {
        $result = mysqli_query($this->link, $sql);
        if (!$result) {
            return false;
        }
        return mysqli_affected_rows($this->link);
    }

}
