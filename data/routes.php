<?php return array (
  'mods' => 
  array (
    0 => 'album.cate',
    1 => 'album.search',
    2 => 'album.index',
  ),
  'data' => 
  array (
    'cate/{dirname}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'cate',
        'dirname' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'dirname',
      ),
      'regex' => '/^cate\\/([^\\/]+)$/i',
    ),
    'tag/{tag}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'search',
        'tag' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'tag',
      ),
      'regex' => '/^tag\\/([^\\/]+)$/i',
    ),
    'discover' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'index',
      ),
      'needreplace' => 
      array (
      ),
      'regex' => '/^discover$/i',
    ),
    'search/{keyword}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'search',
        'keyword' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'keyword',
      ),
      'regex' => '/^search\\/([^\\/]+)$/i',
    ),
  ),
);