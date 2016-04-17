# EDB is Php -> Mysql Database class. #

**Latest Version v-0.1.3**


**About:** Its very lightweight, simple, and easy to use, good start for begginers. Multi database Support. Edb class is only 5Kb.

---


## Usage ##


**Connection #1**
```

$db = new edb('example.com','username','password','databasename');

```

**Connection #2**
```

$config = array('example.com','username','password','databasename');
$db = new edb($config);

```
**Select from table #1**
```

$result = $db->q("select * from `users`limit 3");

foreach($result as $a){
	echo $a['name'].' '.$a['surname'].' '.$a['email'].' '.$a['country'].'</br>';
}


```


**Select from table #2**
```

$result = $db->q("select * from `users`limit 3");

foreach($result as $a){
	$a = (object) $a;
	echo $a->id.' '.$a->name.' '.$a->url.' '.$a->img.'</br>';
}

```

**Select line from table**
```

$result = $db->line("select * from `users` where id = '300' limit 1");
echo $result['name']; 
echo $result['surname']; 

```


**Select one from table**
```

$name = $db->one("select name from `ilike_pics` where id = '300' limit 1");
echo $name;

```
## Debuging ##

**Get all executed query count**
```

echo $db->queryCount;

```

**Get all executed query time**
```

echo $db->queryTime;

```

**Get all executed query debug data**
```

print_r( $db->queryAll );

//returns array with information
//query = executed query
//time = time for query
//type = returns type, DB - reads from database, Cache - reads from cache
Array
(
    [1] => Array
        (
            [query] => select * from users where id = '5'
            [time] => 0.04899907
            [type] => DB
        )
 
    [2] => Array
        (
            [query] => select * from location_list 
            [time] => 0.19058895
            [type] => cache
        )
 
    [3] => Array
        (
            [query] => select email from users where id = '5' limit 1
            [time] => 0.05135894
            [type] => DB
        )
 
)
```



## Using Cache ##

to use cache add parameters to function:
  * to enable cache add **true** as second parameter, default false
  * to set cache expire time use, third parameter, set seconds as number

```

$db->q($query, $cacheEnabled, $expireTime);

$name = $db->one("select name from `ilike_pics` where id = '300' limit 1", true, 3600);
echo $name;

```

to change cache dir use:


```

$db->cacheDir = './cache/database/';

//default =  './dbcache/';

```