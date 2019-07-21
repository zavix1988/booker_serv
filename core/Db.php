<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 13.06.19
 * Time: 14:12
 */

namespace core;


class Db
{
    protected static $instance;

    protected $dbh;

    private $rowsCount;


    protected function __construct()
    {
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $this->dbh = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_DEFAULT_CHARSET, DB_USER, DB_PASSWORD, $options);
    }

    public static function instance()
    {
        if(self::$instance === null){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function execute($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        if($sth->execute($params)){
            $this->countRows = $sth->rowCount();
            return true;
        }else {
            return false;
        }
    }

    public function query($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute($params);
        if($result !== false){
            return $sth->fetchAll();
        }
        return [];
    }

    public function getRowsCount(){
        return $this->rowsCount;
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }
}