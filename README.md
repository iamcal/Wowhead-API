# Wowhead-API

PHP library for turning Wowhead pages into useful data

## Usage

    $cfg = array(
        'http_timeout' => 5,
    );
    include('lib_http.php');
    include('lib_wowhead.php');

    $ret = wowhead_achievement(2537);

    # outputs "Mountain o' Mounts is worth 10 points"
    echo "{$ret['name']} is worth {$ret['points']} points\n";


If you're using <a href="https://github.com/exflickr/flamework">Flamework</a>, just drop lib_wowhead.php into your <code>include</code> folder.

Functions always return a hash with an <code>ok</code> key, in the Flamework style.


## Functions

    $ret = wowhead_achievement($id); # Achievement data

More to come...
