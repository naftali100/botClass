<?php
// נייבא את הספריה
require "botClass.php"; // שימו לב לשים את הנתיב המלא של הקובץ

$bot = new Bot($token); // אפשר לשים את הטוקן בתוך הבקשה ע"י הגדרת פרמטר בתוך הגדרת ה kebhook
// דוגמא: 
// https://your-site/bot-file?token=23431231:fdsraseraefd-sdfsdczsdvzd
// ואז במקום טוקן לשים $_REQUEST['token'];

// כל המשתנים כמו $chat_id מוגדרים בקובץ הספריה
// כדאי ללכת לשם ולדפדף ברשימה של המשתנים ולהכיר אותם
// אפשר גם להגדיר משתנים משלכם

// data מכיל את המידע שחוזר מליחצה על כפתור
if($data != null){
    $bot->sendMessage($cid, "לחצת על כפתור!");
    $bot->editMessageText($cid, $m_id, "טקסט חדש להודעה שלחצו שם על הכפתור");
}
// התאמה מלאה לטקסט
switch($message){
    case "hello":
        $bot>sendMessage($chat_id, "כתבת hello");
    break;
    case "שלום":
        $bot->sendMEssage(ME, "שלום גם לך", $bot->keyboard([ ["כפתור"=>"מידע שחוזר בליחצה"] ]), $m_id); // ME מוגדר ע"י הספריה לכו לשם ותשנו בשורה הראשונה
    break;
    case "מחק":
        $bot->deleteMessage($cid, $m_id);
        $bot->sendMessage($cid, "ההודעה ששלחת נמחקה");
    default:
        $bot->sendMessage($cid, "הי! ברוך הבא לרובוט שלי. זוהי הודעת ברירת מחדל", null, $m_id);
}

// התאמה ע"פ טקסט שנמצא בתוך ההודעה ע"פ regex אפשר לבדוק איך התנאי שלכם עובד באתר
// http://regex101.com
if(preg_match('/טקסט/',$message, $m)){
    $bot->sendMessage($cid, "בתוך ההודעה ששלחתם היה את האותיות 'טקסט'");
}

// הורידו את ההערה כאן וגם בסוף הקובץ של הספריה כדי לראות איך השליחת שגיאות לעצמכם בטלגרם עובדת
// $bot->send($cid)

// כל הקוד הזה מצריך אותכם לערוך כל פעם את הקוד כדי להוסיף פקודות 
// בהמשך יעלה מדריך איך לאכסן פקודות בקובץ או איך להתחבר למסד נתונים ולשמור םקודות שם
// לתמיכה נוספת אפשר להיכנס לקבוצה https://t.me/help_PHP_1
// נכתב ע"י @naftali100 אין לגשת לפרטי לתיכה בשום פנים! רק בקבוצה!
// מומלץ לעבור על ההערות בספריה
// 