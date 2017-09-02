<?php
include 'config.inc.php';
function check_user($user){
    global $user;
    $curl = curl_init();  
    curl_setopt($curl,CURLOPT_URL,"https://twitter.com/{$user}");
    curl_setopt($curl,CURLOPT_HEADER,1);
    curl_setopt($curl,CURLOPT_NOBODY,1);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_TIMEOUT,10);
    curl_exec($curl);  
    if (curl_getinfo($curl,CURLINFO_HTTP_CODE) == '404'){
        $user_status = false;
    }else{
        $user_status = true;
    }
    curl_close($curl);  
    return  $user_status;  
}
$path = '';
$user =  '';
$hashtag = '';
$keyword = '';
$feed ='';
if ( isset( $_GET['path'] ) && !empty( $_GET['path'] ) ) {
   $path = $_GET['path'];//twitrss.org/user or twitrss.org/hashtag/tag
}
if ( isset( $_GET['user'] ) && !empty( $_GET['user'] ) ) {
$user = $_GET['user'];//search?user=
}
if ( isset( $_GET['hashtag'] ) && !empty( $_GET['hashtag'] ) ) {
$hashtag = $_GET['hashtag'];//search?hashtag=
}
if ( isset( $_GET['q'] ) && !empty( $_GET['q'] ) ) {
$keyword = $_GET['q'];//search?q=gamdeals
}
if ( isset( $_GET['feed'] ) && !empty( $_GET['feed'] ) ) {
$feed = $_GET['feed'];//search?q=gamdeals
}
if (!empty($path))
{
// format of path /user/feed, hashtag/gamedeals/feed
    $temp = explode( "/", trim($path) );
    if ($temp[0] == "hashtag") {
        $hashtag = $temp[1];
        if ($temp[2] == "feed") {
            $feed = 1;
        }
    } else {
        $user = $temp[0];
        if ($temp[1] == "feed") {
             $feed = 1;
        }
    }
}
if(!empty($hashtag)){
    $rss_page = file_get_contents(perl_hashtag_api_url."?term={$hashtag}");
    if (empty($feed)) {
        header("Content-Type: text/html; charset=utf-8");
	echo "feed";
    }
    echo $rss_page;
}else if (!empty($user)){
    if (!check_user($user)){
        header("Content-Type: text/html; charset=utf-8");
        echo "not found";
        exit;
    }
    $rss_page = file_get_contents(perl_api_url."?user={$user}");
    if (empty($feed)) {
        header("Content-Type: text/html; charset=utf-8");
	echo "feed";
    }
    echo $rss_page;
} else {
    header("Content-Type: text/html; charset=utf-8");
    echo "not found";
}
