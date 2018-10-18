<?php return array (
  'mods' => 
  array (
    0 => 'album.cate',
    1 => 'album.search',
    2 => 'album.index',
    3 => 'album.photos',
    4 => 'space.index',
    5 => 'album.space',
    6 => 'friend.friends',
    7 => 'friend.followers',
    8 => 'album.like',
    9 => 'album.album',
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
    'work/{id}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'photos',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^work\\/([^\\/]+)$/i',
    ),
    'u/{id}' => 
    array (
      'params' => 
      array (
        'app' => 'space',
        'm' => 'index',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^u\\/([^\\/]+)$/i',
    ),
    '{id}/all' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'space',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^([^\\/]+)\\/all$/i',
    ),
    '{id}/friends' => 
    array (
      'params' => 
      array (
        'app' => 'friend',
        'm' => 'friends',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^([^\\/]+)\\/friends$/i',
    ),
    '{id}/followers' => 
    array (
      'params' => 
      array (
        'app' => 'friend',
        'm' => 'followers',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^([^\\/]+)\\/followers$/i',
    ),
    '{id}/like' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'like',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^([^\\/]+)\\/like$/i',
    ),
    '{id}/albums' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'album',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^([^\\/]+)\\/albums$/i',
    ),
  ),
);