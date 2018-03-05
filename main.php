<?php 

date_default_timezone_set('Asia/Kolkata');
define('BOT_TOKEN', 'ENTER_BOT_TOKEN_HERE');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
$API_KEY="ENTER_API_KEY_HERE";	
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];
$c5 = $update["message"]["text"];
$c6 = $update["message"]["date"];
$getpinof=urlencode($c5);
$sc5=strtolower($c5);
$c2 = $update["message"]["from"]["first_name"];	
$c3 = $update["message"]["from"]["last_name"];	


if($sc5=="hi" || $sc5=="help" || $sc5=="support" || $sc5=="hello" ||  $sc5=="/start")
{
$reply=urlencode("Hi ".$c2.", \n<b>I am Text Summarizer Bot!</b> \nI will summarize the text for you.just send me the text and i will summarize it for you.");
$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$reply."&parse_mode=HTML";
file_get_contents($sendto);
}
else{

$reply= urlencode("Just a moment ".$c2."ðŸ˜Š,Summarizing text...");
$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$reply."&parse_mode=HTML";
file_get_contents($sendto);

$filename="TXTSUM-".$chatID."-".time().".txt";
file_put_contents($filename,$c5);
$htmxl= file_get_contents('http://api.intellexer.com/summarize?apikey='.$API_KEY.'&conceptsRestriction=10&loadConceptsTree=true&returnedTopicsCount=2&summaryRestriction=10&textStreamLength=1500&url='.$filename.'&useCache=false'); 

$jsona=json_decode($htmxl,true);
$getcount=count($jsona[items]);
$getsumtext="";
for($i=0;$i<$getcount;$i++)
{
$getrank = ($jsona[items][$i][rank]);
if($getrank>"0")
{
$getsumtext .= "<b> >>".preg_replace('/\[.*\]/', '', $jsona[items][$i][text])."</b>\n\n";
}
}
$gettopic= ($jsona[topics][0]);
$getopic2= substr($gettopic, 0, strpos($gettopic, "."));
$reply= urlencode("Detected Topic:<b>".$getopic2."</b>\n\nSummarized Text:\n".$getsumtext);
$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$reply."&parse_mode=HTML";
file_get_contents($sendto);


$mask = 'TXTSUM-'.$chatID.'*.*';
array_map('unlink', glob($mask));
}


?>
