<?php

class Db_inc extends PDO{

    const PARAM_host='docker-db-1';
    const PARAM_port='3306';
    const PARAM_db_name='incaet';
    const PARAM_user='root';
    const PARAM_db_pass='example';

    public function __construct($options=null){
        parent::__construct(
            'mysql:host='.Db_inc::PARAM_host.';port='.Db_inc::PARAM_port.';dbname='.Db_inc::PARAM_db_name,
            Db_inc::PARAM_user,
            Db_inc::PARAM_db_pass,$options);
    }

}





