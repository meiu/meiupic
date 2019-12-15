<?php return array (
  'mods' => 
  array (
    0 => 'album.cate',
    1 => 'album.search',
    2 => 'album.index',
    3 => 'album.sets_photos',
    4 => 'space.index',
    5 => 'album.space',
    6 => 'friend.friends',
    7 => 'friend.followers',
    8 => 'album.space_like',
    9 => 'album.space_profile',
    10 => 'album.space_sets',
    11 => 'album.album_detail',
    12 => 'album.sets_view',
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
    'sets/{id}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'sets_photos',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^sets\\/([^\\/]+)$/i',
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
    'u{id}/works' => 
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
      'regex' => '/^u([^\\/]+)\\/works$/i',
    ),
    'u{id}/friends' => 
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
      'regex' => '/^u([^\\/]+)\\/friends$/i',
    ),
    'u{id}/followers' => 
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
      'regex' => '/^u([^\\/]+)\\/followers$/i',
    ),
    'u{id}/like' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'space_like',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^u([^\\/]+)\\/like$/i',
    ),
    'u{id}/profile' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'space_profile',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^u([^\\/]+)\\/profile$/i',
    ),
    'u{id}/sets' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'space_sets',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^u([^\\/]+)\\/sets$/i',
    ),
    'work/{id}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'album_detail',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'id',
      ),
      'regex' => '/^work\\/([^\\/]+)$/i',
    ),
    'sets/{set_id}/{id}' => 
    array (
      'params' => 
      array (
        'app' => 'album',
        'm' => 'sets_view',
        'set_id' => '@@',
        'id' => '@@',
      ),
      'needreplace' => 
      array (
        0 => 'set_id',
        1 => 'id',
      ),
      'regex' => '/^sets\\/([^\\/]+)\\/([^\\/]+)$/i',
    ),
  ),
);