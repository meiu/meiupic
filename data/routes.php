<?php return array (
  'mods' => 
  array (
    0 => 'album.cate',
    1 => 'album.tags',
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
    'tags/{tag}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'tags',
        'tag' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'tag',
      ),
      'regex' => '/^tags\\/([^\\/]+)$/i',
    ),
  ),
);