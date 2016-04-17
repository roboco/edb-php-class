<?php

/**
   * EDB Class -- Easy Database Class
   * @version 0.1.3
   * @author Eduards Marhelis <eduards.marhelis@gmail.com>
   * @link http://code.google.com/p/edb-php-class/
   * @copyright Copyright 2010 Eduards Marhelis
   * @license http://www.opensource.org/licenses/mit-license.php MIT License
   * @package EDB Class
   */
class edb
    {
	private	$connection		=	false;
	public	$debug			=	false; //debuging all
	public	$res			=	0; //last result data
	public	$line			=	0; //last line data
	public	$one			=	0; //last one data
	public	$queryAll		= 	array();
	public	$queryCount		= 	0; //tatal query count
	public	$queryTime		= 	0; //total query time
	public	$cacheDir		=	'../cache/db/';
	public 	$cacheOveride   =   false;

	
	private $_memcache_is_on= 	true; //this instantiates memcache class if its installed.
	private $_cacheType     =  'memcache';// either memcache or filecache is default
	private	$_memcache_host = 'localhost';
	private	$_memcache_port = '11211';
	private $_lifetime      = 	3600;
	private $_memcache_compressed = 0; // 0 or 'MEMCACHE_COMPRESSED'
	private $_memcachekey	=   'dev_key';
	private $_memcache;
	
	public	$utf8Cache		=	false; //use only when you have 

	/**
	   * @function 			__Construct 
	   * @description 		Connects to database when created new edb(); object.
	   * @param string 		$host 	Database Host.
	   * @param string 		$user 	Database user.
	   * @param string 		$pass 	Database pass.
	   * @param string 		$db 	Database name.
	   * @return 			nothing.
	*/

public function __construct($host, $user=0, $pass=0, $db=0)
	{
		$data = $host;
	
		if(is_array($data))
		{
			$host = $data[0];
			$user = $data[1];
			$pass = $data[2];
			$db   = $data[3];
		}
		

		if($this->_memcache_is_on == true && class_exists('Memcache')) {
		
		$this->_memcache = new Memcache();
		@$this->_memcache->pconnect($this->_memcache_host, $this->_memcache_port);
		$stats = @$this->_memcache->getExtendedStats();
		$available = (bool) $stats["$this->_memcache_host:$this->_memcache_port"];
		if ($available && @$this->_memcache->pconnect($this->_memcache_host, $this->_memcache_port)){

		$this->_cacheType = 'memcache';

		}else{

		$this->_cacheType = 'filecache';

		}
		} elseif($this->_memcache_is_on == true && class_exists('Memcached')) {
			$this->_memcache = new Memcached;
			$this->_memcache->addServer($this->_memcache_host, $this->_memcache_port);
			$this->_cacheType = 'memcache';		
		} else {

		$this->_cacheType = 'filecache';

		}
		
		$this->connection = mysqli_connect($host, $user, $pass, $db) or die('db error');

	}
	


/**
	   * @function 			q  (shortening for query) 
	   * @description 		runs mysql query and returns php array.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
public function q($a,$cacheType=null,$t=null){
		$cached = $this->_getCache($a,$t);
		if(!empty($cached)){
			$this->res = $cached;
		}else{
			$start	=	microtime(1);
			$this->res = array();
			
			$q = mysqli_query($this->connection, $a) or die(mysqli_error());
			//while($row = mysql_fetch_array($q)){
			while($row = mysqli_fetch_array($q)){
				$this->res[] = $row;
			}
			$end = microtime(1);
		if(!empty($cacheType) || $this->cacheOveride == true) {
            $this->_setCache($a,$this->res,$t); 
                                  }
			$this->debugData($start,$end,$a);
		}
		return $this->res;
	}

	/**
	   * @function 			line   
	   * @description 		runs mysql query and returns php array with line from db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
	public function line($a,$cacheType=null,$t=null){
		$cached = $this->_getCache($a,$t);
		if(!empty($cached)){
			$this->line = $cached;
		}else{
			$start	=	microtime(1);
			$query = mysqli_query($this->connection, $a);
			$this->line = mysqli_fetch_array( $query );
			$end	=	microtime(1);
		if(!empty($cacheType) || $this->cacheOveride == true) { 
                    $this->_setCache($a,$this->line,$t);                     
                }
			$this->debugData($start,$end,$a);
			
		}
		return $this->line;
	}
	/**
	   * @function 			one   
	   * @description 		runs mysql query and returns php string db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function one($a,$cacheType=null,$t=null){
		$cached = $this->_getCache($a,$t);
		if(!empty($cached)){
			$this->one = $cached;
		}else{
			$start	=	microtime(1);
			$query = mysqli_query($this->connection, $a);
			$r = mysqli_fetch_array( $query );
			//$r = mysql_fetch_assoc( $query );
			$end	=	microtime(1);
			$this->debugData($start,$end,$a);
			$i=0; if(isset($b)) {$i=$b;}
			$this->one = $r[$i];
		if(!empty($cacheType) || $this->cacheOveride == true) { 
                    $this->_setCache($a,$this->one,$t); }
		}
		return $this->one;
	}
	/**
	   * @function 			s   
	   * @description 		runs mysql query and returns result from mysql query. used for inserts and updates. 
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function s($a){		
		$start	=	microtime(1);
		$q = mysqli_query($this->connection, $a) or die(mysqli_error());
		$end	=	microtime(1);
		$this->debugData($start,$end,$a);
		return $q;
	}
	
	/**
	   * @function 			getCache  
	   * @description 		provides a check to determine whether to fetch file cache of memcache 
	   * @param string 		$query 	Mysql Code.
	   * @return 			string.
	   */
	private function _getCache($query, $file_lifetime=3600){

	if($this->_cacheType === 'memcache') {
		$querykey = $this->_memcachekey . md5($query);
		$cache = $this->_memcache->get($querykey);	
	} else {
			$cacheFile = $this->cacheDir . md5($query) .'.cache';
			if(is_file($cacheFile) && (time()-filemtime($cacheFile))<$file_lifetime){
				$cache = $this->_getFileCache($cacheFile,$query);
			} else {
				$cache = null;
			}
	}

	
	return $cache;	
}


	/**
	   * @function 			setCache  
	   * @description 		provides a check to determine whether to save to file cache of memcache 
	   * @param string 		$query 	Mysql query string.
	   * @param 	 		$result resutl of mysql query
	   * @return 			string.
	   */
	private function _setCache($query, $result, $t=null){
		if(isset($t)){
			$memory_lifespan = $t;
		} else {
			$memory_lifespan = $this->_lifetime;
		}	
	
    	if($this->_cacheType === 'memcache') {
			$querykey = $this->_memcachekey . md5($query);
			$this->_memcache->set($querykey, $result, $memory_lifespan);
			return true;
			} else {
			$cacheFile = $this->cacheDir . md5($query) .'.cache';
			$this->_setFileCache($cacheFile,$result,$query);
			return true;		
		}
  }
	
	private function _setFileCache($file,$result,$q,$o=true){
		$fh = fopen($file, 'w') or die("can't open file");
		if($o) { fwrite($fh, json_encode($result)); }
		else{ fwrite($fh, $result); }
		fclose($fh);
	}



	private function _getFileCache($file,$a,$o=true){
	
		$start	=	microtime(1);
		$fh = fopen($file, 'r');
		$data = fread($fh, filesize($file));
		fclose($fh);
		//if($o) { $data = (array)json_decode($data); }
		if($o) { $data = (array)json_decode($data,true); }
		$end	=	microtime(1);
		$this->debugData($start,$end,$a,'cache');
		return $data;
	}
	   
	private function debugData($start,$end,$a,$b='DB'){
		$this->queryCount++;
		$t = number_format($end - $start, 8);
		$this->queryTime = $this->queryTime + $t;
		$this->queryAll[ $this->queryCount ] = array('query'=>$a,'time'=>$t,'type'=>$b);
	}
	
	//select * from table
	public function selectAll($a,$c=0,$t=30){
		$query = "SELECT * FROM `$a`";
		return $this->q($query,$c,$t);
	}
	
	//insert data $db->insert($table,$data);
	public function insert($a,$b){
		$q = "INSERT INTO $a (";
		foreach($b as $c=>$d){
			$q .= "`$c`,";
		}
		$q = substr($q,0,-1);
		$q .= ") values (";
		foreach($b as $c=>$d){
			$q .= "'$d',";
		}
		$q = substr($q,0,-1);
		return $this->s($q.');');
	}
	
	//update row or rows, $db->update($tableName,$updateValues,$whereValues);
	public function update($a,$b,$c){
		$q = "UPDATE `$a` SET ";
		foreach($b as $v=>$k){
			$q .= "`$v`='$k',";
		}
		$q = substr($q,0,-1);
		$q .= " WHERE 1";
		foreach($c as $v=>$k){
			$q .= " AND `$v`='$k'";
		}
		return $this->s($q);
	}
	

	public function countTable($a,$c=0,$t=30){
		$q = "SELECT COUNT(*) FROM `$a` LIMIT 1";
		return $this->one($q,$c,$t);
	}
	
	public function countWhere($a,$b,$c=0,$t=30){
		$q = "SELECT COUNT(*) FROM `$a` WHERE $b LIMIT 1";
		return $this->one($q,$c,$t);
	}
	//get last inserted ID	
	public function lastID()
    {
		return mysqli_insert_id($this->connection);
    }
    
    public function make_db_safe($s)	{
				$s = str_replace('\n', '<br />', $s);
				$s = mysqli_real_escape_string($this->connection, $s);
				return $s;
			}
   
   public function make_safe($var)
   	{
	$var = preg_replace('/[^-a-zA-Z0-9_ ]/', '', $var);
	return $var;
	}
    
    
	/**
	   * @function 			__destruct   
	   * @description 		closes mysql connection.
	   */
	public function __destruct(){
	if(is_resource($this->connection))
	{
    mysqli_close($this->connection);
    }
	}
	


}