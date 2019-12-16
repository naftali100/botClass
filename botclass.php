<?php 
// הספריה נכתבה ע"י יהודה אייזינברג ונערכה ע"י נפתלי
// https:/t.me/naftali100
// ניתן להשתמש בה לשימוש לא מסחרי
// מקור:
// https://github.com/YehudaEi/Telegram-Bots

define("ME", 227774988); // הכניסו את האידי שלכם בשביל ניהול שגיאות
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Jerusalem');

$update = json_decode(file_get_contents('php://input'), true); 
if(($update == null && !defined('BOT')) ||  __FILE__ == "botClass.php" ){
    http_response_code(403);
    // include "/var/www/errors/403.html"; כאן אפשר לייבא קובץ שיוצג למי שינסה להיכנס לקובץ
    die();
}
if(isset($update['message'])){
    $chat_idD = $update["message"]["from"]["id"]                         ?? null;
    $chat_id = $update["message"]["chat"]["id"]                          ?? null;
    $cid = $update["message"]["chat"]["id"]                              ?? null;
    $ctype = $update["message"]["chat"]["type"]                          ?? null;
    $userName = $update["message"]["chat"]["username"]                   ?? null;
    $firstName = $update["message"]["from"]["first_name"]                ?? null;

    $rfid = $update['message']['reply_to_message']['forward_from']['id'] ?? null;

    $fwdFrom = $update['message']['forward_from_chat']['id']             ?? null;
    $fwdId = $update['message']['forward_from']['id']                    ?? null;

    //$lastName = $update["message"]["from"]["last_name"];
    $userName = $update["message"]["from"]["username"]                   ?? null;
    $message = $update["message"]["text"]                                ?? null;
    $message_id = $update["message"]["message_id"]                       ?? null;
    $m_id = $update["message"]["message_id"]                             ?? null;
    //$rtmi =  
    $rtmt = $update['message']['reply_to_message']['text']               ?? null;
    //photo
    $tphoto = $update['message']['photo']                                ?? null;
    if(!empty($tphoto))
        $phid = $update['message']['photo'][count($tphoto)-1]['file_id'] ?? null;
    //audio
    $auid = $update['message']['audio']['file_id']                       ?? null;
    $duration = $update['message']['audio']['duration']                  ?? null;
    $autitle = $update['message']['audio']['title']                      ?? null;
    $performer = $update['message']['audio']['performer']                ?? null;
    //document
    $did = $update['message']['document']['file_id']                     ?? null;
    $dfn = $update['message']['document']['file_name']                   ?? null;
    //video
    $vidid = $update['message']['video']['file_id']                      ?? null;
    //voice 
    $void = $update['message']['voice']['file_id']                       ?? null;
    //video_note
    $vnid = $update['message']['video_note']['file_id']                  ?? null;
    //contact
    $conph = $update['message']['contact']['phone_number']               ?? null;
    $conf = $update['message']['contact']['first_name']                  ?? null;
    $conl = $update['message']['contact']['last_name']                   ?? null;
    $conid = $update['message']['contact']['user_id']                    ?? null;
    //location
    $locid1 = $update['message']['location']['latitude']                 ?? null;
    $locid2 = $update['message']['location']['longitude']                ?? null;
    //Sticker
    $stid = $update['message']['sticker']['file_id']                     ?? null;
    //Venue
    $venLoc1 = $update['message']['venue']['location']['latitude']       ?? null;
    $venLoc2 = $update['message']['venue']['location']['longitude']      ?? null;
    $venTit = $update['message']['venue']['title']                       ?? null;
    $venAdd = $update['message']['venue']['address']                     ?? null;
    //all media
    $cap = $update['message']['caption']                                 ?? null;

    //Inline
    $inlineQ = $update["inline_query"]["query"]                          ?? null;
    $InlineQId = $update["inline_query"]["id"]                           ?? null;
    $inline_mid = $update["callback_query"]["inline_message_id"]         ?? null;

    //reply to message
    $remid = $update['message']['reply_to_message']['message_id']        ?? null;
    $refid = $update['message']['reply_to_message']['document']['file_id']?? null;
    $remt = $update['message']['reply_to_message']['text']               ?? null;
    $reuid = $update['message']['reply_to_message']['from']['id']        ?? null;

    $data = null;
//EditMessage
}
elseif(isset($update['edited_message'])){
    $message = $update['edited_message']['text'] ?? null;
    $chatId = $update['edited_message']['chat']['id'];
    $ctype = $update["edited_message"]["chat"]["type"];
    $messageId = $update['edited_message']['message_id'];
}
//CallBeck
elseif(isset($update['callback_query'])){
    $callId = $update["callback_query"]["id"];
    $message = $update["callback_query"]["message"]["text"] ?? $update["callback_query"]["message"]["caption"] ?? null;
    $data = $update["callback_query"]["data"];
    $chat_id = $update["callback_query"]["from"]["id"];
    $cid = $update["callback_query"]["from"]["id"];
    $message_id = $update["callback_query"]["message"]["message_id"];
    $m_id = $update["callback_query"]["message"]["message_id"];
    $chat_idD = $update["callback_query"]["message"]["from"]["id"];
    $ctype = $update["callback_query"]["message"]["chat"]["type"];
    $rtmi = $update["callback_query"]["message"]["reply_to_message"]["message_id"]  ?? null;
    $rtmt = $update["callback_query"]["message"]["reply_to_message"]["text"]        ?? null;
    $buttons = $update["callback_query"]["message"]["reply_markup"]["inline_keyboard"]  ?? null;
}

//channels updates 
elseif(isset($update['channel_post'])){
    $chat_id = $update["channel_post"]["chat"]["id"] ?? null;
    $cid = $update["channel_post"]["chat"]["id"] ?? null;
    $message = $update["channel_post"]["text"] ?? null;
    $ctype = "channel";
    $m_id = $update["channel_post"]["message_id"] ?? null;
    $title = $update["channel_post"]["chat"]["title"] ?? null;
}

class Bot
{
    private $BotToken;
    private $BotId;
    private $BotName;
    private $BotUserName;
    private $Debug;
    private $beautifi = false;
    private $update = null;
    private $webHook = null;
    private $webPagePreview = true;
    private $Notification = false;
    private $ParseMode = null;

    
    public function __construct($token){
        $botInfo = json_decode(file_get_contents("https://api.telegram.org/bot".$token."/getMe"), true);
        if($botInfo['ok'] == true && $botInfo['result']['is_bot'] == true){
            
            $this->BotId = $botInfo['result']['id'];
            $this->BotName = $botInfo['result']['first_name'];
            $this->BotUserName = "@".$botInfo['result']['username'];
        }else{
            $this->sendMessage(ME, "bot not exist\n\n".$token);
            die();
        }
        $this->BotToken = $token;
    }

    //Setters && Getters
        //Debug Mode
    public function GetDebug(){
        return $this->Debug;
    }
    public function SetDebug($val){
        $this->Debug = $val;
    }
        //WebHook
    public function GetWebHook(){
        return $this->webHook;
    }
    public function setBotWebHook($bot,$link){
        $res = file_get_contents("https://api.telegram.org/bot".$bot."/setwebhook?url=".$link."?token=".$bot);
        return $res;
    }
    public function SetWebHook($val){
        $this->webHook = $val;
        return $this->Request('setwebhook', array( "url" => $val))['ok'];
    }
    public function DetWebHook(){
        $this->webHook = NULL;
        return $this->Request('setwebhook', array("url"))['ok'];
    }
        //Updates - BETA!
    public function SetUpdate($update){
        $this->Update = $update;
        //if($this->Debug)
            //$this->logging($update, false, "Update input:", true);
    }
    public function GetUpdate(){
        return $this->Update;
    }
        //WebPagePreview Mode
    public function GetWebPagePreview(){
        return $this->webPagePreview;
    }
    public function SetWebPagePreview($val){
        $this->webPagePreview = $val;
    }
        //Notification Mode
    public function GetNotification(){
        return $this->Notification;
    }
    public function SetNotification($val){
        $this->Notification = $val;
    }
        //ParseMode Mode
    public function GetParseMode(){
        return $this->ParseMode;
    }
    public function SetParseMode($val){
        $val = strtolower($val);
        if("markdown" == $val || "html" == $val)
            $this->ParseMode = $val;
    }

    //SendRequest
    private function Request($method, $data =[] ==null){
        $BaseUrl = "https://api.telegram.org/bot".$this->BotToken."/".$method;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BaseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch ,CURLOPT_POSTFIELDS, $data);
       
        $res = curl_exec($ch);
        if(curl_error($ch)){
            //if($this->Debug)
               // $this->logging(curl_error($ch), "Curl: ".$method, false, false, $data);
            curl_close($ch);
        }else{
            curl_close($ch);
            $res = json_decode($res, true);
            if(!$res['ok']){
                return $this->error_heandler($res);
            }
            //if($this->Debug)
                //$this->logging($res, "Curl: ".$method, true, true, $data);
            return $res;
        }
    }
   
    //Logging
    public function logging($data, $method = null, $success = false, $array = false, $helpArgs = null){
        if($array)
            $data = json_encode($data, ($this->beautifi ? JSON_PRETTY_PRINT : "" ) | JSON_UNESCAPED_UNICODE);
        if(isset($helpArgs)){
            $data .= "args:";
            foreach ($helpArgs as $name => $val)
                $data .= " [".$name."] = " . isset($val)?$val:"null";
        }
        file_put_contents("log.log", date(DATE_RFC850).": ".str_pad($this->BotUserName, 20).": ".($success ? str_pad("Success!", 10) : str_pad("error", 10)).($method?str_pad(" Method: \"".$method."\"", 30):"")." ---> ".$data." <---\n\n", FILE_APPEND | LOCK_EX);
    }

    //Methods
    public function sendMessage($id, $text, $replyMarkup = null, $replyMessage = null){
        $data["chat_id"] = $id;
        $data["text"] = $this->text_adjust($text);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_web_page_preview"] = $this->webPagePreview;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendMessage", $data);
    }
    public function forwardMessage($id, $fromChatId, $messageId){
        $data["chat_id"] = $id;
        $data["from_chat_id"] = $fromChatId;
        $data["disable_notification"] = $this->Notification;
        $data["message_id"] = $messageId;
        return $this->Request("forwardMessage", $data);
    }
    public function sendFile($id, $message_id, $file, $caption = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $message_id;
        $data["file"] = $file;
        $data["caption"] = $caption;
        return $this->Request("sendFile",$data);
    }
    public function sendPhoto($id, $photo, $caption = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["photo"] = $photo;
        $data["caption"] = $caption;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendPhoto", $data);
    }
    public function sendAudio($id, $audio, $caption = null, $duration = null, $performer = null, $title = null, $replyMessage = null, $thumb = null, $replyMarkup = null){ 
        $data["chat_id"] = $id;
        $data["audio"] = $audio;
        $data["caption"] = $caption;
        $data["duration"] = $duration;
        $data["performer"] = $performer;
        $data["title"] = $title;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["thumb"] = $thumb;
        $data["reply_markup"] = $replyMarkup;
        $data["parse_mode"] = $this->ParseMode;
        return $this->Request("sendAudio", $data);
    }
    public function sendDocument($id, $document, $caption = null, $replyMessage = null, $replyMarkup = null, $thumb = null){
        $data["chat_id"] = $id;
        $data["document"] = $document;
        $data["caption"] = $caption;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        $data["thumb"] = $thumb;
        return $this->Request("sendDocument", $data);
    }
    public function sendSticker($id, $sticker, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["sticker"] = $sticker;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendSticker", $data);
    }
    public function sendVideo($id, $video, $duration = null, $width = null, $height = null, $caption = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["video"] = $video;
        $data["duration"] = $duration;
        $data["width"] = $width;
        $data["height"] = $height;
        $data["caption"] = $caption;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->RequestFile("sendVideo", $data);
    }
    public function sendVoice($id, $voice, $duration = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["voice"] = $voice;
        $data["duration"] = $duration;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->RequestFile("sendVoice", $data);
    }
    public function sendLocation($id, $latitude, $longitude, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["latitude"] = $latitude;
        $data["longitude"] = $longitude;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendLocation", $data);
    }
    public function sendVenue($id, $latitude, $longitude, $title, $address, $foursquare = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["latitude"] = $latitude;
        $data["longitude"] = $longitude;
        $data["title"] = $title;
        $data["address"] = $address;
        $data["foursquare_id"] = $foursquare;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendVenue", $data);
    }
    public function sendContact($id, $phoneNumber, $firstName, $lastName = null, $replyMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["phone_number"] = $phoneNumber;
        $data["first_name"] = $firstName;
        $data["last_name"] = $lastName;
        $data["disable_notification"] = $this->Notification;
        $data["reply_to_message_id"] = $replyMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("sendContact", $data);
    }
    public function sendChatAction($id, $action){
        if(!in_array($action, ["typing", "upload_photo", "record_video", "upload_video", "record_audio", "upload_audio", "upload_document", "find_location"]))
            return false;
        $data["chat_id"] = $id;
        $data["action"] = $action;
        return $this->Request("sendChatAction", $data);
    }
    public function getUserProfilePhotos($uId, $offset = null, $limit = null){
        $data["user_id"] = $uId;
        $data['offset'] = $offset;
        $data['limit'] = $limit;
        return $this->Request("getUserProfilePhotos", $data);
    }
    public function kickChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("kickChatMember", $data);
    }
    public function unbanChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("unbanChatMember", $data);
    }
    public function getFile($fileId){
        $data["file_id"] = $fileId;
        return $this->Request("getFile", $data);
    }
    public function leaveChat($id){
        $data["chat_id"] = $id;
        return $this->Request("leaveChat", $data);
    }
    public function getChat($id){
        $data["chat_id"] = $id;
        return $this->Request("getChat", $data);
    }
    public function getChatAdministrators($id){
        $data["chat_id"] = $id;
        return $this->Request("getChatAdministrators", $data);
    }
    public function getChatMembersCount($id){
        $data["chat_id"] = $id;
        return $this->Request("getChatMembersCount", $data);
    }
    public function getChatMember($id, $uId){
        $data["chat_id"] = $id;
        $data["user_id"] = $uId;
        return $this->Request("getChatMember", $data);
    }
    public function answerCallbackQuery($callback, $text = null, $alert = false, $url = null){
        $data["callback_query_id"] = $callback;
        $data["text"] = $text;
        $data["show_alert"] = $alert;
        $data["url"] = $url;
        return $this->Request("answerCallbackQuery", $data);
    }
    public function editMessageText($id = null, $messageId = null, $text, $replyMarkup = null, $inlineMessage = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["text"] = $this->text_adjust($text);
        $data["parse_mode"] = $this->ParseMode;
        $data["disable_web_page_preview"] = $this->webPagePreview;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageText", $data);
    }
    public function editMessageCaption($id = null, $messageId = null, $inlineMessage = null, $caption = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["caption"] = $this->text_adjust($caption);
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageCaption", $data);
    }
    public function editMessageMedia($id = null, $messageId = null, $inlineMessage = null, $media = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["media"] = $media;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageMedia", $data);
    }
    public function editMessageReplyMarkup($id = null, $messageId = null, $inlineMessage = null, $replyMarkup = null){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        $data["inline_message_id"] = $inlineMessage;
        $data["reply_markup"] = $replyMarkup;
        return $this->Request("editMessageReplyMarkup", $data);
    }
    public function deleteMessage($id, $messageId){
        $data["chat_id"] = $id;
        $data["message_id"] = $messageId;
        return $this->Request("deleteMessage", $data);
    }
    public function answerInlineQuery($inlineMessage, $res, $cacheTime = null, $isPersonal = null, $nextOffset = null, $switchPmText = null, $switchPmParameter = null){
        $data["inline_query_id"] = $inlineMessage;
        $data["results"] = $res;
        $data["cache_time"] = $cacheTime;
        $data["is_personal"] = $isPersonal;
        $data["next_offset"] = $nextOffset;
        $data["switch_pm_text"] = $switchPmText;
        $data["switch_pm_parameter"] = $switchPmParameter;
        return $this->Request("answerInlineQuery", $data);
    }
    public function createNewStickerSet($id, $name, $title, $sticker, $emoji){
        $data["user_id"] = $id;
        $data["name"] = $name;
        $data["title"] = $title;
        $data["png_sticker"] = $sticker;
        $data["emojis"] = $emoji;
        return $this->Request("createNewStickerSet", $data);
    }
    public function uploadStickerFile($id, $sticker){
        $data["user_id"] = $id;
        $data["png_sticker"] = $sticker;
        return $this->Request("uploadStickerFile", $data);
    }
    public function addStickerToSet($id, $name, $sticker, $emoji){
        $data["user_id"] = $id;
        $data["name"] = $name;
        $data["png_sticker"] = $sticker;
        $data["emojis"] = $emoji;
        return $this->Request("addStickerToSet", $data);
    }
    public function deleteStickerFromSet($sticker){
        return $this->Request("deleteStickerFromSet", $sticker);
    }
    public function text_adjust($text){
        $type = gettype($text);
        if($type == "array")
            $text = json_encode($text,TRUE | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        elseif($type == "NULL")
            $text = "text is NULL";

        if(strlen($text) > 4048)
            $text = "message is too long. https://del.dog/".$this->toarr($this->post("https://del.dog/documents", $text))["key"];
        elseif($text == '')
            $text = "message empty";

        if($this->ParseMode == "markdown" && preg_match_all('/(@|http)\S+_\S*/', $text, $m) != 0){
            foreach($m[0] as $username){
                $text = str_replace($username, str_replace('_', "\_", $username), $text);
            }
        }
        return $text;
    }
    // פונקציה ליצירת מקלדות
    // מקבלת מערך של מערכים כל מערך הוא שורה ובתוכה מערכים שכל אחד מהם הוא כפתור
    // דוגמא:
    // [ ["text"=>"date], ["text" => "date]]
    // יצור מקלדת של שתי שורות כשבכל שורה כפתור
    public function keyboard($data){
        $a = [];
        $c = [];
        foreach($data as $row){
            foreach($row as $key => $value){
                array_push($c, ['text'=>$key,'callback_data'=>"$value"]);
            }
            array_push($a, $c);
            $c = [];
        }
        return json_encode(array('inline_keyboard' => $a)); 
    }
    // טיפול בשיגיאות - פונקציה זו נקראת מתי שיש שגיאה בשליחת הודעה עם פירוט מלא של ההודעה
    // כדי לקבל רק את תיאור השגיאה שלחו לעצמכם רק את 
    // $respons['description']
    public function error_heandler($respons){
        if($respons['error_code'] == 429){
            $this->sendMessage(ME, "הצפה חכה ".$respons['parameters']['retry_after']. " שניות");
            die();
        }
        foreach (debug_backtrace() as $key => $value) {
            if($key == 0)
                continue;
            if($value['function'] == "error_heandler"){
                $this->sendMessage(ME, "לולאת שגיאות");
                $this->sendMessage(ME, $respons['description']);
                die();
            }
        }
        global $update;
        $respons["call_by"] = debug_backtrace()[2]['function'];
        $respons["from_line"] = debug_backtrace()[2]['line'];
        $respons["_"] = "error output";
        $respons['update'] = $update;
        $debug = debug_backtrace();
        $cid = $debug[1]['args'][1]['chat_id'];
        return $this->sendMessage(ME, $respons);
    }

    function post($url, $postVars = array()){
        
        if(gettype($postVars) == "array"){
            $postVars = http_build_query($postVars);
        }
        //Create an $options array that can be passed into stream_context_create.
        $options = array(
            'http' =>
                array(
                    'method'  => 'POST', //We are using the POST HTTP method.
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postVars //Our URL-encoded query string.
                )
        );
        //Pass our $options array into stream_context_create.
        //This will return a stream context resource.
        $streamContext  = stream_context_create($options);
        //Use PHP's file_get_contents function to carry out the request.
        //We pass the $streamContext variable in as a third parameter.
        $result = file_get_contents($url, false, $streamContext);
        //If $result is FALSE, then the request has failed.
        if($result === false){
            //If the request failed, throw an Exception containing
            //the error.
            $error = error_get_last();
            //throw new Exception('POST request failed: ' . $error['message']);
        }
        //If everything went OK, return the response.
        return $result;
    }

    function toarr($json){
        if($json == null){
            return [];
        }
        return json_decode($json, true);
    }
}
// הוציאו מהערה אם אתם רוצים לקבל את כל השיגאות בקוד לטלגרם
/*
set_exception_handler("error_handler");
function error_handler($e){
    global $bot;
    $r["file"] = $e->getFile();
    $r["error"] = $e->getMessage();
    $r["line"] = $e->getLine();
    $bot->sendMessage(ME, $r);
}
*/