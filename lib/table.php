<?php

  class ORM {

  	
  	static $config = array(); //конфиги подключения к базе
  	static $conn = array(); // все подключения

  	private $ORM = '';
  	private $conf = 'default';
  	private $filters = array();
  	private $sort = array();
  	private $limit = null;
  	private $columns = '*';

  	static function from($ORM, $conf = 'default') {
      return new ORM($ORM, $conf);
    }
  	
    
    function __construct($ORM, $conf = 'default'){
  		$this->ORM = $ORM;
  		$this->config = $conf; //текущая конфигурация
  	}


  	/*
    * добавляем конфигурацию подключения к базе
    */
    static function config($name, $host, $user, $pswd){
  		ORM::$config[$name] = array('host'=>$host, 'user'=>$user, 'pswd'=>$pswd);
  	}

  	

  	private function conn($conf){
  		
  		if (!isset(ORM::$conn[$conf])){
  			if (is_array(ORM::$config[$conf])){
  				$config =ORM::$config[$conf]; 
  				ORM::$conn[$conf] = new mysqli($config['dbhost'], $config['dbuser'],$config['dbpswd'], $config['dbname']);
          return True;
        }		
  		}
  		
  	 return False;
  		
  	}

  	
    /**
    * функции добавления
    */

    function separ($value){
  		return '`'.$value.'`';
  	} 

    function quote($value){
      return chr(39).$value.chr(39);
    }	

  	function add($column){
  		$this->columns[] = $column;
  		return $this;	
  	}

  	function columns($columns = array()){
  		$this->columns = $columns;
  		return $this;
  	}

  	function filter($column, $value, $op ='=', $type = 'AND') {
  		$this->filters[] = array('column'=>$column, 'value'=>$value, 'op'=>$op, 'type'=>$type);
  		return $this;
  	} 

  	function sort($column, $type = 'ASC') {
		  $this->sort[$column] = $type;
		  return $this;  		
  	}

  	function build(){

  		$sql = 'SELECT';
  		
  		  		
  		$sql .= ' '.$this->columns.' FROM '.$this->separ($this->ORM);
  		$sql .= $this->build_filters();
  		$sql .= $this->build_sort();

  		if ($this->limit !== null)
  			$sql .= 'LIMIT '.$this->limit;

  		$sql .= ';';

		  return $sql;

  	}


  	function build_filters(){

  		$res = '';

  		foreach ($this->filters as $filter){
  			
  			if ($res !== '')
  				$res .= ' '.$filter['type'].' ';

  			$res .=	$this->separ($filter['column']).$filter['op'].$this->quote($filter['value']);

  		} 

  		return ' WHERE '.$res;

  	} 


  	function build_sort(){

  		$res = '';

  		foreach ($this->sort as $key => $sort){
  			
  			if ($res !== '')
  				$res = ',';
  			
  			$res .= $this->separ($key).' '.$sort;
  		}

  		return ' ORDER BY '.$res;
  	
  	}


  	function all() {

  		$sql = $this->build();
      $result = $this->query($sql)->fetch();
      var_dump($result);
  		
  	}


    static function query($sql){
      $this->conn();
      return ORM::$conn[$conf]->query($sql);
    }


}