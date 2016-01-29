### Эмулятор Memcache для обычного хостинга
**Описание** - все закешированные данные хранятся в файлах в json формате.
- <a href="#install">Установка</a>
- <a href="#methods">Методы</a>
- <a href="#connect">Memcache::connect</a> — Открывает соединение с memcached сервером
- <a href="#pconnect">Memcache::pconnect</a> — Открывает постоянное соединение с memcached сервером
- <a href="#addserver">Memcache::addServer</a> — Добавить memcached сервер в пул соединений
- <a href="#add_set">Memcache::add</a> — Добавить значение
- <a href="#add_set">Memcache::set</a> — Установить значение
- <a href="#replace">Memcache::replace</a> — Заменить значение
- <a href="#get">Memcache::get</a> — Получить значение
- <a href="#increment">Memcache::increment</a> — Инкрементирует значение
- <a href="#decrement">Memcache::decrement</a> — Декрементирует значение
- <a href="#delete">Memcache::delete</a> — Удалить значение
- <a href="#flush">Memcache::flush</a> — Сбросить все существующие значения на сервере
- <a href="#close">Memcache::close</a> — Закрывает соединение с memcached сервером

<h3><a name="install">Установка</a></h3>
<p>Для установки на сайт достаточно создать папку cache в любом месте проекта и в файле memcache.php указать путь до папки. Так же там можно изменить название папки и расширение файлов.</p>
Для полноценной работы достаточно заинклюдить файл memcache.php в основном файле.
После этого вы получите, поддержку стандартных функций memcache.
Если же на сервере был ранее установлен memcache вы получите ошибку:
<pre>
<b>Fatal error</b>:  Cannot redeclare "Название функции" in <b>/path/to/memcache.php</b> on line <b>NUM</b>
</pre>
<h3><a name="install">Работа с memcache</a></h3>
Memcache поддерживает два типа работы. 
- Процедурный стиль
- Объектно Ориентированный

<h4>Процедурный стиль</h4>
```
$connect = memcache_connect('localhost',11211);
memcache_set($connect, 'key_set', array('value1','value2'));
print_r(memcache_get($connect, 'key_set'));
```
<h4>Объектно Ориентированный</h4>
```
$cache = new Memcache; // так же можно указать Memcached
$cache->connect('localhost',11211);
$cache->set('key_set', array('value1','value2'));
print_r($cache->get('key_set'));
```
При желании можно структурировать кеш. Разложив его по папкам. 
Делается это при помощи **^ (caret)**

Например:
```
$cache->set('dir^file', array('value1','value2'));
```
Результат:
<table id="user-content-toc" summary="Contents">
  <td>
    <ul>
      <li>
        cache/
        <ul>
          <li>
            dir/
    				<ul>
    					<li>file.tmp</li>
    					<li>file.tmp.tmpTime</li>
    				</ul>
           </li>
        </ul>
      </li>
    </ul>
  </td>
</table>
Тем самым, можно легко понять структуру кеша и при переходе на обычный memcache, не придется ничего менять.
<h3><a name="methods">Методы</a></h3>
<p>На самом деле все эти методы можно подразделить на 3 группы:</p>
- Работа со значениями
- Работа с серверами и соединениями 
- Получение информации

<h3>Работа со значениями</h3>
<p>Методы этой группы позволяют делать следующее:</p>
- Устанавливать значения
- Удалять эти значения 
- Заменять эти значения
- Обращаться к этим значения по ключу
- Управлять сроком жизни значений

<p>+ пара специфичных методов для инкремента и декремента целочисленных значений.</p>
<h3><a name="connect">Memcache::connect()</a></h3>
<p>Фейковая функция существует только для совместимости.</p>
<pre>
bool <strong>Memcache::connect</strong> ( string $host [, int $port [, int $timeout ]] )
</pre>
- **string $host** - Хост Memcached. 
- **int $port** - порт, на котором Memcached слушает соединения.
- **int $timeout** - Лимит в секундах, для подключения к демону. Подумайте дважды, прежде чем изменять значение по умолчанию 1 секунда - вы можете потерять все преимущества кэширования, если ваше соединение будет происходить слишком долго.

```
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
?>
```
<h3><a name="pconnect">Memcache::pconnect()</a></h3>
<p>Фейковая функция существует только для совместимости.</p>
<pre>
mixed <strong>Memcache::pconnect</strong> ( string $host [, int $port [, int $timeout ]] )
</pre>
- **string $host** - Хост Memcached. Этот параметр может также указать другие транспорты, как unix:///path/to/memcached.sock т.е. использовать UNIX сокеты, в этом случае порт должен быть установлен в 0.
- **int $port** - порт, на котором Memcached слушает соединения. Установите этот параметр в 0, если предполагается использование сокетов.
- **int $timeout** - Лимит в секундах, для подключения к демону. Подумайте дважды, прежде чем изменять значение по умолчанию 1 секунда - вы можете потерять все преимущества кэширования, если ваше соединение будет происходить слишком долго.

```
<?php
  $cache = new Memcache();
  $cache->pconnect('localhost', 11211, 30);
?>
```
<h3><a name="addserver">Memcache::addServer()</a></h3>
<p>Фейковая функция существует только для совместимости.</p>
<pre>
bool <strong>Memcache::addServer</strong> ( string $host [, int $port = 11211 [, bool $persistent [, int $weight [, int $timeout [, int $retry_interval [, bool $status [, callable $failure_callback [, int $timeoutms ]]]]]]]] )
</pre>
- **string $host** - Хост Memcached. Этот параметр может также указать другие транспорты, как unix:///path/to/memcached.sock т.е. использовать UNIX сокеты, в этом случае порт должен быть установлен в 0.
- **int $port** - порт, на котором Memcached слушает соединения. Установите этот параметр в 0, если предполагается использование сокетов.
- **bool $persistent** - Устанавливает использование постоянного соединения. По умолчанию в значение TRUE.
- **int $weight** - Чем больше, тем вероятнее, что данный сервер будет выбран для хранения значений. Т.е. это своеобразный "вес" сервера в общем пуле, косвенно это ещё и предполагаемая нагрузка на сервер.
- **int $timeout** - Лимит в секундах, для подключения к демону. Подумайте дважды, прежде чем изменять значение по умолчанию 1 секунда - вы можете потерять все преимущества кэширования, если ваше соединение будет происходить слишком долго.
- **int $retry_interval** - Устанавливает, как часто отказавший сервер будет опрашиваться, значение по умолчанию составляет 15 секунд. Установка этого параметра в -1 отключает автоматический повтор запросов.
- **bool $status** - Помечает сервер как ONLINE. Установка этого параметра в FALSE и retry_interval -1 позволяет отказавшему серверу хранится в пуле активных серверов, чтобы не влиять на ключевые алгоритмы распределения. Обращения к этому серверу будут распределятся между оставшимися доступными серверами. По умолчанию значение TRUE, то есть сервер должен рассматриваться как ONLINE.
- **callable $failure_callback** - Позволяет пользователю указать функцию обратного вызова для запуска при обнаружении ошибки. Функция должна принимать два параметра, имя хоста и порт вышедшего из строя сервера.
- **string $timeoutms** - не описана.
 
<h3><a name="add_set">Memcache::set()<br /> Memcache::add()</a></h3>
<p>Позволяют установить значение, задать сжатие и время жизни для этого значения. Единственное различие в поведении этих методов это то, что метод Memcache::add - вернёт FALSE, если значение с таким ключём уже установлена.</p> 
<pre>
bool <strong>Memcache::add</strong> ( string $key, mixed $var [, int $flag [, int $expire ]] )
bool <strong>Memcache::set</strong> ( string $key, mixed $var [, int $flag [, int $expire ]] )
</pre>
- **string $key**  - ключ значения, используя его мы оперируем значением.
- **mixed  $var**  - значение.
- **int    $flag** - Флаг, указвающий, использовать ли сжатие в данном случае настоящего сжатия не производиться, так же можно использовать константы
- **int    $expire** - Время жизни значения(кэша). Если равно нулю, то бессрочно. Вы также можете использовать метку времени или количество секунд, начиная с текущего времени, но тогда число секунд, не может превышать 2592000 (30 дней).

````
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
  $cache->add( 'myfirst',  1000, MEMCACHE_COMPRESSED, 15 );
  $cache->set( 'mysecond', 2000, MEMCACHE_COMPRESSED, 15 );
?>
````
<h3><a name="replace">Memcache::replace()</a></h3>
<p>Перезаписать существующее значение.</p>
<p>Memcache::replace() должна использоваться, чтобы заменить <strong>существующее значение</strong>. В случае, если значение с таким ключом не существует, Memcache::replace() возвращает FALSE. В остальном Memcache::replace() ведет себя так же, как и Memcache::set(). Также можно использовать функцию memcache_replace()</p>
<pre>
string <strong>Memcache::replace </strong> ( string $key , mixed $var [, int $flag [, int $expire ]] )
</pre>  
- **string $key**  - ключ, значение которого нужно заменить.
- **mixed $var**  - новое значение.
- **int   $flag** - Флаг, указвающий, использовать ли сжатие (Здесь то и нужна Zlib) для сохраняемого значения, можно использовать <a href="http://docs.php.net/manual/ru/memcache.constants.php" target="_new">константы</a> 
- **int   $expire** - Время жизни значения(кэша). Если равно нулю, то бессрочно. Вы также можете использовать метку времени или количество секунд, начиная с текущего времени, но тогда число секунд, не может превышать 2592000 (30 дней).

```
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
  $cache->add( 'one',   111, 0, 15 );
  $cache->add( 'two',   222, 0, 15 );
  $cache->add( 'three', 333, 0, 15 );
  
  $cache->replace( 'three', 777 );
  
  print_r( $cache->get( array('one','two','three')) );
?>
```
<p>Результат:</p>
<pre>  
Array ( [one] => 111 [two] => 222 [three] => 777 )
</pre>
<h3><a name="get">Memcache::get()</a></h3>
<p>Вернёт запрошенное значение или FALSE в случае неудачи, или, если значение с таким ключём ещё не установлено.</p>
<p>Можно передать массив ключей значений, тогда Memcache::get тоже вернёт массив, он будет содержать найденные пары ключ-значение.</p>
<pre>
string <strong>Memcache::get</strong> ( string $key [, int   &amp;$flags ] )
array  <strong>Memcache::get</strong> ( array $keys [, array &amp;$flags ] )
</pre>
- **string $key** - ключ значения, или массив ключей, значения которых нужно получить.
- **int    $flag** - назначение этого параметра осталось для меня тайной. Я пробовал использовать некоторые целочисленные значения, но это не дало ни какого эффекта. Да и примеры в основном даны без его использования, из чего мною сделан вывод: можно прекрасно работать и без него :)

```  
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
  $cache->add( 'myfirst',  1000, MEMCACHE_COMPRESSED, 15 );
  $cache->set( 'mysecond', 2000, MEMCACHE_COMPRESSED, 15 );
  print_r( $cache->get( array('myfirst','mysecond')) );
?>

```
<p>Результат:</p>
<pre>
Array ( [myfirst] => 1000 [mysecond] => 2000 )
</pre>
<h3><a name="increment">Memcache::increment()</a></h3>
<p>Увеличивает значение указанного ключа на указанное значение. Если значение указанного ключа не числовое и не может быть конвертировано в число, то оно изменит свое значение на значение, указанное как второй параметр. Memcache::increment() не создает элемент, если он еще не существует.</p>
<pre>
int <strong>Memcache::increment</strong> ( string $key [, int $value = 1 ] )
</pre>
- **string $key**   - ключ, значение которого нужно инкрементировать.
- **int $value** - значение инкремента.

```
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
  
  $cache->set( 'someOne', 10, 0, 5 );
  
  $cache->increment( 'someOne', 10, 0, 5 );
  $cache->increment( 'someOne', 10, 0, 5 );
  
  echo $cache->get('someOne');// выведет 30
?>
```
<h3><a name="decrement">Memcache::decrement()</a></h3>
<p>Уменьшает значение указанного ключа на указанное значение. Аналогично Memcache::increment(), текущее значение элемента преобразуется в числовое и после этого уменьшается</p>
<pre>
int <strong>Memcache::decrement</strong> ( string $key [, int $value = 1 ] )
</pre>
- **string $key**   - ключ, значение которого нужно декрементировать.
- **int $value** - значение декремента.

<h3><a name="delete">Memcache::delete()</a></h3>
<p>Удалить значение из кэша.</p>
<p>Возвращает TRUE в случае успешного завершения или FALSE в случае возникновения ошибки.</p>
<pre>
string <strong>Memcache::delete</strong> ( string $key [, int $timeout = 0 ] )
</pre>
- **string $key** - ключ значение, которого нужно удалить.
- **int    $timeout** - Этот параметр так же устарел и не поддерживается, по умолчанию равен 0 секунд. Не используйте этот параметр.

<h3><a name="flush">Memcache::flush()</a></h3>
<p>Memcache::flush() удаляет полностью все каталоги и файлы из папки cache</p>
<pre>
bool <strong>Memcache::flush</strong> ( void )
</pre>
<p>Пример ниже не выведет ничего.</p>
```
<?php
  $cache = new Memcache();
  $cache->connect('localhost', 11211, 30);
  
  $cache->set( 'someOne',  111, 0, 5 );
  $cache->set( 'someTwo',  222, 0, 5 );
  $cache->set( 'someTree', 333, 0, 5 );
  
  $cache->flush(); //Очистит кэш
  
  print_r( $cache->get( array('someOne','someTwo','someTree')) );
?>
```

<h3><a name="close">Memcache::close()</a></h3>
<p>Фейковая функция существует только для совместимости. Также можно использовать функцию memcache_close().</p>
<pre>
bool <strong>Memcache::close</strong> ( void )
</pre>
