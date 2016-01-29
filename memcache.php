<?
/*
  * @Author: Alexander Nagnitchenko
  * @link: https://vk.com/otto_rocket
  * @version: 2.0
 */
define('MEMCACHE_COMPRESSED', true);
define('cDir', './cache/'); # Директория кеша
define('ext', '.tmp'); # Разширение файла
define('extT', '.cacheTime'); # Разширение временного файла

$cacheData = array(); # Данные о кеше 
$iniData = array(); # Данные о времени жизни кеша

function memcache_add_server($h, $p = 11211, $t = 1){
  conn($h, $p, $t);
}
function memcache_connect($h, $p = 11211, $t = 1){
  conn($h, $p, $t);
}
function memcache_pconnect($h, $p = 11211, $t = 1){
  conn($h, $p, $t);
}
function memcache_add($obj, $key, $var, $flag = false, $expire = 0){
  $key = rKey($key);
  if(!file_exists(cDir.$key.ext.extT)){
    memcache_set($obj, $key, $var, $flag, $expire);
  }
  return false;
}
function memcache_set($obj, $key, $var, $flag = false, $expire = 0){
  $d = explode('/', rKey($key).ext);
  $fN = end($d);
  $c = count($d);
  unset($d[$c-1]);
  $cdt = cDir.($c > 1 ? implode('/', $d).'/' : '');
  if($c > 1 && !is_dir($cdt)){
    $cdt = cDir;
    foreach($d as $f){
      $cdt .= $f.'/';
      if(!is_dir($cdt)){
        @mkdir($cdt, 0777);
        @chmod($cdt, 0777);
      }
    }
  }
  $cdt .= $fN;
  @file_put_contents($cdt, json_encode($var));
  @chmod($cdt, 0644);
  global $cacheData, $iniData;
  $iniData[$key] = ($expire == 0 ? 0 : time() + $expire);
  @file_put_contents($cdt.extT, $iniData[$key]);
  @chmod($cdt.extT, 0644);
  $cacheData[$key] = $var;
  return true;
}
function memcache_replace($obj, $key, $var, $flag = false, $expire = 0){  
  $key = rKey($key);
  if(file_exists(cDir.$key.ext.extT)){
    if(!empty($var)){
      return memcache_set($obj, $key, $var, $flag, $expire);
    }
    return memcache_delete($obj, $key);
  }
  return false;
}
function memcache_get($obj, $keys){
  global $cacheData, $iniData;
  if(!is_array($keys)){
    $keys = array($keys);
  }
  foreach($keys as $k => $v){
    if(preg_match('/([^#]+)#/i', $v, $m)){
      unset($keys[$k]);
      foreach(glob(cDir.rKey($m[1]).'*'.ext) as $e){
        $keys[] = str_replace(array(cDir, ext), '', $e);
      }
    }
  }
  $params = array();
  foreach($keys as $key){
    $key = rKey($key);
    $file = cDir.$key.ext;
    $params[$key] = false;
    if(isset($iniData[$key]) || ($iniData[$key] = @file_get_contents($file.extT)) !== false){
      if($iniData[$key] == 0 || ($iniData[$key] > time())){
        if(!isset($cacheData[$key])){
          $cacheData[$key] = $params[$key] = json_decode(@file_get_contents($file), true);
        }else{
          $params[$key] = $cacheData[$key];
        }
      }else{
        memcache_delete($obj, $key);
      }
    }
  }
  return count($keys) > 1 ? $params : $params[$key];
}
function memcache_increment($obj, $key, $value = 1){
  $key = rKey($key);
  if(file_exists(cDir.$key.ext.extT)){
    return inDecrement($key, $value, true);
  }
  return false;
}
function memcache_decrement($obj, $key, $value = 1){
  $key = rKey($key);
  if(file_exists(cDir.$key.ext.extT)){
    return inDecrement($key, $value);
  }
  return false;
}
function memcache_delete($obj, $key, $timeOut = 0){
  $key = rKey($key);
  if(file_exists(cDir.$key.ext.extT)){
    @unlink(cDir.$key.ext.extT);
    @unlink(cDir.$key.ext);
    global $cacheData, $iniData;
    $iniData[$key] = $cacheData[$key] = false;
    return true;
  }
}
function memcache_flush($obj, $s = cDir, $d = false) {
  if ($objs = glob($s.'/*')){
    foreach($objs as $ob) {
      is_dir($ob) ? memcache_flush($obj, $ob, true) : @unlink($ob);
    }
  }
  if($d){
    @rmdir($s);
  }
}
function memcache_close($obj){
  global $cacheData;
  unset($cacheData);
}
function conn($host, $port, $timeOut){
  if(!is_dir(cDir)){
    exit('Dir cache '.cDir.' not found');
  }
  defined('MEMCACHE_HAVE_SESSION') or define('MEMCACHE_HAVE_SESSION', true);
  return true;
}
function rKey($k){
  defined('MEMCACHE_HAVE_SESSION') or die('Memcache not connect');
  return str_replace('^', '/', $k);
}
function inDecrement($k, $v, $t = false){
  global $iniData, $cacheData;
  if(isset($iniData[$k]) || ($iniData[$k] = @file_get_contents(cDir.$k.ext.extT)) !== false){
    if($iniData[$k] == 0 || ($iniData[$k] > time())){
      if(!isset($cacheData[$k])){
        $cacheData[$k] = intval(json_decode(@file_get_contents(cDir.$k.ext), true));
      }
      $cacheData[$k] = $t ? $cacheData[$k] + $v : $cacheData[$k] - $v;
      @file_put_contents(cDir.$k.ext, json_encode($cacheData[$k]));
      return true;
    }else{
      memcache_delete(true, $key);
    }
  }
  return false;
}

# OOP Style
class Memcache {
  function __call($func, $args){
    if(strtolower($func) == 'addserver'){
      $func = 'add_server';
    }
    $func = 'memcache_'.$func;
    if(!function_exists($func)){
      exit('Call to undefined function '.$func);
    }
    array_unshift( $args, true );
    return call_user_func_array($func, $args);
  }
}
class Memcached extends Memcache{}
?>
