# Wowhead-API

PHP library for turning Wowhead pages into useful data

<a href="http://www.wowhead.com/">Wowhead</a> is the best source of WoW data; The Battlenet API has promise, but is lacking in many places and Blizzard is not actively working on it.

However, Wowhead does not offer an API. There is a defacto API through the XML item pages, but there are so many other aspects to the Wowhead database. This library presents a sensible API, achieved by screen-scraping the HTML pages. Over time, it will stop working as they change their pages. I'll endeavour to keep updating it.


## Usage

    $cfg = array(
        'http_timeout' => 5,
    );
    include('lib_http.php');
    include('lib_json.php');
    include('lib_wowhead.php');

    $ret = wowhead_achievement(2537);

    # outputs "Mountain o' Mounts is worth 10 points"
    echo "{$ret['name']} is worth {$ret['points']} points\n";


If you're using <a href="https://github.com/exflickr/flamework">Flamework</a>, just drop lib_wowhead.php into your <code>include</code> folder.

Functions always return a hash with an <code>ok</code> key, in the Flamework style.


## Functions

    $ret = wowhead_achievement($id); # Achievement data

More to come...
