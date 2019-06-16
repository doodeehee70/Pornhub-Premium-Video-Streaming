<?php
header('Content-Type: application/json');
function curlitf($url){
       $ch = curl_init();
$timeout = 12;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
 curl_setopt($ch, CURLOPT_ENCODING ,"");
curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //don't change!
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$htmlf = curl_exec($ch);
curl_close($ch);
return $htmlf;
}
function createCookie() {
$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
$ch = curl_init();
$timeout = 10;
curl_setopt($ch, CURLOPT_URL,"https://www.pornhubpremium.com/premium/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$htmlf = curl_exec($ch);
$internalErrors = libxml_use_internal_errors(true);
$dom = new DOMDocument();
@$dom->loadHTML($htmlf);
    return $dom->getElementsByTagName('input')[3]->getAttribute('value');
}
function loginx($token) {
  $email = ""; //pornhub email
  $passw = ""; //pornhub password
  if ($passw === "" || $email === "") {exit('set your pornhub email and password inside loginx() function');}
$url="https://www.pornhubpremium.com/front/authenticate";
$postinfo = "username=".$email."&password=".$passw."&remember_me=on&token=".$token."&from=pc_premium_login&segment=straight";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_USERAGENT,
    "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
$isf = curl_exec($ch);
}

function curfl($url){
$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
$data = curl_exec($ch);
$last = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);
if (strpos($last, '/login?redirect=') !== false) {
//echo "attempting login...";
loginx(createCookie()); //login again if session expires.
$data = curfl($url);
}
return $data;
}
if (strpos($_GET['u'], '.com/') !== false) {
  if (strpos($_GET['u'], 'viewkey=') !== false) {
    $fi = explode('viewkey=', $_GET['u']);
    $vidkey = $fi[1];

  } else {
    exit('viewkey not found in url');
  }
} else {
  $vidkey = $_GET['u'];
}
if (isset($_GET['f']) && !empty($_GET['f'])) {
  $formatx = $_GET['f']; //mp4, hls, dash, all
} else {
  $formatx = 'mp4'; //default format
}
$current_time = time();
$filexk = $vidkey.".txt"; //filename for cache
if(file_exists($filexk)) {

 $rie = file_get_contents($filexk);
 $fied = json_decode($rie, true);
 if (strpos($fied[0]['url'], '&hash=') !== false) {
 $isf = explode('ttl=', $fied[0]['url']);
 $Efsif = explode('&ri=', $isf[1]);
 $ttlx = $Efsif[0];
 if ($current_time < $ttlx) {
   echo $rie;
   exit();
 }
 }
}
$scriptx = "";
$internalErrors = libxml_use_internal_errors(true);
$dom = new DOMDocument();
@$dom->loadHTML(curfl('https://www.pornhubpremium.com/view_video.php?viewkey='.$vidkey));
$xpath = new DOMXPath($dom);
$nlist = $xpath->query("//meta[@property='og:title']");
$nlistx = $xpath->query("//meta[@property='og:image']");
$imagex = $nlistx[0]->getAttribute("content");
$titlek = $nlist[0]->getAttribute("content"); //video title
foreach($dom->getElementsByTagName('script') as $k => $js) {

       $scriptx .= $js->nodeValue; //append all js into variable.

}
$aiv = [];
$aiv['title'] = $titlek;
$aiv['poster'] = $imagex;
$soif = explode('"mediaDefinitions":[{"defaultQuality"', $scriptx);
$opsigj = '[{"defaultQuality"'.$soif[1];
$isii = explode('"}],"isVertical"', $opsigj);
$riir = $isii[0].'"}]';
$xirx = 0;
$isx = json_decode($riir, true);
foreach ($isx as $kk => $vid) {
    if ($vid['format'] !== $formatx) {
        unset($isx[$kk]); //removes unwanted formats
    } else {
      if ($vid['quality'] > 1080) {
        unset($isx[$kk]); //removes 4k video links.
      } else {
        $aiv['links'][$xirx]['url'] = $vid['videoUrl'];
        $aiv['links'][$xirx]['quality'] = $vid['quality'];
        $xirx = $xirx+1;
      }
    }

}
if (strpos($aiv['links'][0]['url'], '&hash=') !== false) {

  file_put_contents($vidkey.".txt", json_encode($aiv, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)); //save to cache file if the links are valid
echo json_encode($aiv, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
} else {
  //configure below if you want to retry when error is returned.
  //$jdid = curlitf('http://yoursite.com/ph.php?u='.$_GET['u']); //this might cause infinite loop. needs retry counter.
//echo $jdid;
}


?>
