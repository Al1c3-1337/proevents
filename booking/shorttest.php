<?
$mobile = "1514 1465046";
$mobile = preg_replace("/[^0-9]/", "", $mobile);
print($mobile);
print("\n");
$mobile = preg_replace("/^(?:0|\+49)?([0-9]*)$/","0$1", $mobile);
print($mobile);
print("\n");
?>
