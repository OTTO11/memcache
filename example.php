<?
/*
  * @Author: Alexander Nagnitchenko
  * @link: https://vk.com/otto_rocket
  * @file: example.php 
 */
$start = microtime(true);
$mm = memory_get_peak_usage();
error_reporting(E_ALL);
require('./memcache.php');


$cache = new Memcache();
$cache->addServer('localhost', 11211, 30);

$cache->set('my_test1', 'test_value_1'); 
$cache->set('my_test2', array(1,'foo'=>'bar',3)); 
$cache->set('my_test3', 11211); 
$cache->set('my_ololo', 'ololo'); 
$cache->add( 'myfirst',  1000, MEMCACHE_COMPRESSED, 15 );
$cache->set( '1^2^mysecond1', 2000, MEMCACHE_COMPRESSED, 15 );
 
print_r( $cache->get( array('my_test1','my_test2','1^2^mysecond1')) );

echo microtime(true)-$start."\n";
echo (memory_get_peak_usage()-$mm)/(1024*1024);
