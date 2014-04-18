<?PHP
function sendToken($mobile, $token) {
$request = "";
// Benutzername
// User name
$param["id"] = "";
// Passwort
// Password
$param["pw"] = "";
// SMS Type
// Type of text message
$param["type"] = "4";
//Empfänger mit Semikolon getrennt
// Recipient(s) separated by semicolons
$param["empfaenger"] = $mobile;
// Absender
// Sender
$param["absender"] = "";
// Inhalt der SMS
// Content of SMS
$param["text"] = "Ihr Reservierungscode lautet: ".$token;
foreach($param as $key=>$val){
$request.= $key."=".urlencode($val);
//append the ampersand (&) sign after each paramter/value pair
$request.= "&"; }
$url = "http://www.smskaufen.com/sms/gateway/sms.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_GET, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
$response = curl_exec($ch);
curl_close($ch);
if (substr_count ( $response, "100") == 1 || substr_count ( $response, "101") == 1) 
{
return true;
} else {
return false;
}
}
function sendConfirmation($mail,$name,$firstname,$eventname,$location,$datestart,$dateend,$places,$mobile) {

$placetemp = array();
foreach ($places as $place) {
$placetemp[] = $place +1;
}
$placestring=implode(",",$placetemp);
$betreff = 'Deine Reservierung - '.$eventname;
$nachricht = "Hallo ".$firstname."!\r\n\r\nDu hast folgende Platzreservierung vorgenommen:\r\nName: ".$name."\r\nVorname: ".$firstname."\r\nE-Mail: ".$mail."\r\nMobil: ".$mobile."\r\nVeranstaltung: ".$eventname."\r\nOrt: ".$location."\r\nReserviert: ".$placestring."\r\n\r\nPS: Bitte beachte, dass du diese Reservierung bei uns stornieren musst, falls du den Termin nicht wahrnehmen willst. Andernfalls werden wir dich von zukuenftigen Reservierungen ausschliessen.\r\n\r\nViele Gruesse,\r\nHello World";
$mail_sender='John Doe <booking@johndoe.com>';
$header = 'From: '.$mail_sender. "\r\n" .
    'Reply-To: '.$mail_sender."\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($mail, $betreff, $nachricht, $header);

$betreff = 'Neue Reservierung - '.$eventname;
$nachricht = "Hallo ".$firstname."!\r\n\r\nEs wurde folgende Platzreservierung vorgenommen:\r\nName: ".$name."\r\nVorname: ".$firstname."\r\nE-Mail: ".$mail."\r\nMobil: ".$mobile."\r\nVeranstaltung: ".$eventname."\r\nOrt: ".$location."\r\nReserviert: ".$placestring;
$mail_sender='John Doe <booking@johndoe.com>';
$header = 'From: '.$mail_sender. "\r\n" .
    'Reply-To: '.$mail_sender."\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail('info@johndoe.com', $betreff, $nachricht, $header);
}
?>