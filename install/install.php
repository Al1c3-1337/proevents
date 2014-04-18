<?php

// Fri 18 Apr 2014 11:50:13 AM PDT
// built with mkinstaller 2012041901 by Manuel Mollar (mm AT nisu.org) http://mkInstaller.nisu.org/
// do not remove this Copyright

$ilic='
Copyright (c) 2006-2009 Manuel Mollar
mm AT nisu.org http://mkInstaller.nisu.org/
This software is released under a MIT License.
http://www.opensource.org/licenses/mit-license.php
';

error_reporting(E_ALL ^ E_NOTICE);
ini_set(track_errors,true);
$lg=detLang($_REQUEST["lang"],"en");
foreach(array("gzdeflate"=>"zlib", ) as $f => $p)
  if (!function_exists($f))
    salir(sprintf(st("Lfne"),$f,$p));
$hhost=$_SERVER["HTTP_HOST"];
if ($exsfn=$_REQUEST["scfn"])
  $sfn=$exsfn;
else if ($hhost)
  $sfn=$_SERVER["SCRIPT_FILENAME"];
else
  $sfn=$argv[0];
$fin=@fopen($sfn,"rb") or
  salir(st("Eali"));
$pidi=strpos(fread($fin,64637),"\n?>")+3;

if ($i=$_GET["img"]) {
  $imgs=unserialize(tkInBuff(5240));
  header("Content-type: image/".$imgs[$i]["t"]);
  exit(tkInBuff($imgs[$i]["i"]));
}
$pidd=$pidi+6120;
$files=unserialize(tkInBuff(5844));
$dsdb=unserialize(tkInBuff(5960));
$dvars=unserialize(tkInBuff(5984));
$plans=array('en'=>3008,);
$css=4840;
$uniq="mkI535173e4dc2d6";
if ($j=$_REQUEST["jmp"]) {
  foreach($files as $fic)
    if ($fic["j"] == $j)
      $simu or eval("?>".trim(tkInBuff($fic["po"])));
  die();
}
if ($info) {
  $imgs=unserialize(tkInBuff(5240));
  $dopt=unserialize(tkInBuff(6008));
}
if ($info)
  return;

$simu or $simu=$_REQUEST["simu"];
$tby=$twr=$taa=0; $ma=""; $smo=0.5; $buf=false;


loadPi();
if ($argv[1]) {
  $cml=true;
  $brk="\n";
  $vo=array();
  foreach($dvars as $var => $def) {
    if (isset($def["vl"]))
      $val=$def["vl"];
    else {
      $d=$def["df"];
      if ($def["ml"] and is_array($d))
	if($dlg=$d[$lg])
	  $d=$dlg;
	else
	  $d=current($d);
      if (is_array($d)) {
        if ($se=$def["se"]) {
	  $d=array_keys($d);
	  $se=$d[$se-1];
	}
	else
          $se=key($d);
        eval('$val='."$se;");
      }
      else
        eval('$val='.$d.";");
    }
    $vo[$var]=$val;
  }
  for($i=1; $i<$argc; $i++) {
    list($var,$val)=explode("=",$argv[$i]);
    if ($val)
      $vo[dechex(crc32($var))]=$val;
    else
      $o[$var]=true;
  }
  $ve=$v=$vo;
  if (!$simu)
    foreach($dvars as $var => $def) {
      $val=$v[$var];
      if ($def["ev"]) {
        if (@eval('$val='.$val.";") === false)
          salir(st("ExIn")."\n".$val."\n".$php_errormsg);
        $ve[$var]=$val;
      }
      if (!$ve[$var] && $o["intc"]) {
        echo $def["va"],": ";
	$ve[$var]=substr(fgets(STDIN),0,-1);
      }
    }
  $v=$ve;
  foreach($dvars as $var => $def) {
    if ((($val=$v[$var]) === "") and ($em=$def["em"]))
      $v[$var]=$val=$v[$em];
    if ($def["st"])
      $v[$var]=var_export($val,true);
  }
  $doit=true;
}
else if ($_REQUEST["doit"]) {
  $brk="<br>";
  $cml=(!$_REQUEST["jsav"]);
  if (!$cml) {
    ob_start(); $buf=true;
  }
  $o=&$_REQUEST;
  if ($vo=$_REQUEST["v"])
    foreach($vo as $var => $val) {
      if (get_magic_quotes_gpc())
        $vo[$var]=stripslashes($val);
    }
  $ve=$v=$vo;
  if (!$simu)
    foreach($dvars as $var => $def) {
      $val=$v[$var];
      if ($def["ev"]) {
        if (@eval('$val='.$val.";") === false)
          salir(st("ExIn")."<br>".$val."<br>".$php_errormsg,true);
        $ve[$var]=$val;
      }
    }
  $v=$ve;
  foreach($dvars as $var => $def) {
    if ((($val=$v[$var]) === "") and ($em=$def["em"]))
      $v[$var]=$val=$v[$em];
    if ($def["st"])
      $v[$var]=var_export($val,true);
  }
  $doit=true;
}
else
  $doit=false;

if ($doit) {
  $time=time();
  if (!$cml) {
    header("Pragma: no-cache"); header("Cache-control: no-cache");
    echo str_replace("{CSS}",tkInBuff($css),tkInBuff(5492));
    echo "<script> try { parent.chStep('2'); } catch(e) {} </script>";
    echo "<tr><td id=ftd>\n";
  }
  $simu and loge(st("Sosi"));
  if (!$o["nrun"])
    foreach($files as $fic) {
      if ($fic["x"] == "f") {
	$nf=$fic["n"];
	loge(sprintf(st("Pefs"),$nf));
        loge(sprintf(st("Eefs"),$nf));
	$f=trim(tkInBuff($fic["po"]));
	parsea($f);
        $simu or eval("?>$f");
      }
    }
  foreach($files as $if => $fic)
    if ($fic["j"] or $fic["x"] == "f")
      unset($files[$if]);
  @ob_end_flush(); $buf=false;
  if (!$cml)
    echo "<tr><td id=gtd>\n";
  dataOpen($sfn);
  $prt=true; $run=array();
  foreach($files as $if => $fic) {
    $nf=$fic["n"];
    if ($cnd=$fic["c"]) {
      $vv=dechex(crc32($cnd["v"]));
      $vv=$v[$vv];
      if (function_exists("preg_match"))
        $ndc=!preg_match("/".$cnd["e"]."/",$vv);
      else
        $ndc=true;
    }
    else
      $ndc=false;
    $ndo=($ndc or $simu);
    if ($l=$fic["l"]) {
      if (!$ndo) {
        @unlink($nf);
        symlink($l,$nf);
      }
    }
    else if ($fic["d"])
      $ndo or crd("$nf/.",$fic["a"][0]);
    else {
      if ($fic["p"]) {
	$f=tkBuff();
	if ($fic["x"])
	  $ndc or loge(sprintf(st("Pefs"),$nf));
        else
	  $ndc or loge(sprintf(st("Pyce"),$nf));
	parsea($f);
	if (function_exists("preg_replace"))
	  $f=preg_replace(array("#^([ \t]*//[ \t]+)%$lg%:[ \t]+#m","#^[ \t]*//[ \t]+%..%:[ \t].*\n#m"),array("\\1",""),$f);
        $prt=false; ob_start();
        if (!@eval("return true; function $uniq$if() {?> $f <?php }") and
            !@eval("return true; function $uniq$if() {?> $f }"))
          salir(sprintf(st("Epds"),$nf)."<xmp>$f</xmp>".ob_get_clean(),true);
	ob_end_clean();
	if ($fic["x"])
	  $run[$nf]=$f;
	else if (!$ndo) {
          crd($nf);
	  $h=@fopen($nf,"wb") or salir(sprintf(st("Npcf"),$nf).$brk.$php_errormsg,true);
	  (@fwrite($h,$f) !== false) or salir(sprintf(st("Npef"),$nf).$brk.$php_errormsg,true);
          fclose($h);
	  @chmod($nf,$fic["a"][0]);
	  @touch($nf,$fic["a"][1]);
        }
      }
      else if (!$fic["x"]) {
        $ndo or crd($nf);
        $ndo or $h=@fopen($nf,"wb") or salir(sprintf(st("Npcf"),$nf).$brk.$php_errormsg,true);
        $ta=$fic["ta"];
        $ndc or loge(sprintf(st("Cefs"),$nf));
        while (true) {
          $bf=tkBuff();
          $ndo or (@fwrite($h,$bf) !== false) or salir(sprintf(st("Npef"),$nf).$brk.$php_errormsg,true);
          $ta-=strlen($bf);
	  if (!$ta)
	    break;
        }
        if (!$ndo) {
	  fclose($h);
	  @chmod($nf,$fic["a"][0]);
	  @touch($nf,$fic["a"][1]);
	}
      }
    }
  }
  $tby=2111; $twr=1;
  loge(st("Inco"));
  if (!$cml)
    echo "<tr><td class=rtd>\n";
  if (!$o["nrun"])
    foreach($run as $nf => $f) {
      loge(sprintf(st("Eefs"),basename($nf)));
      $simu or eval("?>$f");
    }
  loge(sprintf(st("Tito"),time()-$time));
  $simu or savePi();
  if (!$cml)
    echo"</table>\n<script>try { parent.chStep('3'); } catch(e) {} </script></body>\n</html>";
}
else if($hhost) {
  header("Pragma: no-cache"); header("Cache-control: no-cache");
  if ($_REQUEST["wk"]) {
    ob_start(); $buf=true;
    echo str_replace("{CSS}",tkInBuff($css),tkInBuff(5492));
    echo "<script> try { parent.chStep('1'); } catch(e) {} </script>";
    $cml=false; 
    echo "<tr><td id=ftd>\n";
    $simu and loge(st("Sosi"));
    
    if (!@touch($tmf=uniqid("mktest"))) {
      loge(sprintf(st("Anpc"),getcwd()),true);
      loge($php_errormsg);
    }
    @unlink($tmf);
    foreach($files as $if => $fic) {
      if ($fic["x"] == "l")
	$simu or eval("?>".trim(tkInBuff($fic["po"])));
    }
    @ob_end_flush(); $buf=false;
    echo "<tr><td id=gtd>\n".
      "<form method=post id=for>\n".
      "<script>document.write('<input type=hidden name=jsav value=1>');</script>\n";
    echo "<table id=tab>\n";
    foreach($dvars as $id => $def) {
      $mg=&$def["lg"][$lg];
      if (!$mg)
        $mg=&$def["lg"]["en"];
      $ty=strtolower($def["ty"]);
      if ($sp=&$def["sp"][$lg])
        echo "  <tr><td id=sp$id class=sep colspan=2>$sp";
     if ($ty != "hidden")
        echo "  <tr><td id=td$id class=lab>$mg: ";
      $sz=$def["sz"]; $vs=$def["vs"]; $d=$def["df"];
      if ($def["ml"] and is_array($d))
        if($dlg=$d[$lg])
          $d=$dlg;
        else
          $d=current($d);
      if (is_array($d)) {
	if ($def["mp"])
          echo "<td id=ti$id class=tse><select id=in$id size=\"$sz\" multiple name=\"v[$id][]\" class=sel>";
	else
          echo "<td id=ti$id class=tse><select id=in$id size=\"$sz\" name=\"v[$id]\" class=sel>";
        $vse=$def["vl"] or $se=$def["se"]; $c=1;
        foreach($d as $v=>$t) {
	  $ok=false;
	  eval('$val='.$v.';$ok=true;');
	  if (!$ok)
            echo "<xmp>$v</xmp>";
	  if (!$t)
	    $t=$val;
	  $val=htmlspecialchars($val,ENT_COMPAT);
	  $sel=(($vse == $val or $se == $c) ? " selected" : "");
	  $c++;
          echo "<option$sel value=\"$val\">$t";
        }
        echo "</select>\n";
      }
      else {
        if ($ty != "hidden")
          if ($vs)
	    echo "<td id=ti$id class=ttx>";
	  else
            echo "<td id=ti$id class=tin>";
        switch ($ty) {
	  case "text":
	  case "checkbox":
	  case "password":
	  case "hidden": $ty=" type=$ty"; break;
	  case "readonly": $ty=" type=text readonly"; break;
	}
	$ok=false;
	if (isset($def["vl"]))
	  $val=$def["vl"]; 
	else {
	  eval('$val='.$d.';$ok=true;');
	  if (!$ok)
	    echo "<xmp>$d</xmp>";
	}
	$val=htmlspecialchars($val,ENT_COMPAT);
	if ($vs)
	  echo "<textarea id=in$id rows=$vs cols=$sz class=txt name=\"v[$id]\">$val</textarea>\n";
	else {
	  if ($sz)
	    $sz=" size=$sz";
	  echo "<input id=in$id class=inp name=\"v[$id]\" value=\"$val\"$ty$sz>\n";
	}
      }
    }
    echo "  <tr><td id=tidoit colspan=2 class=tsu><input id=indoit class=sub type=submit name=doit value=\"".htmlentities(st("Inst"),ENT_COMPAT,"ISO-8859-1")."\">\n";
    echo "</table>";
    echo "</form>\n<script>mv(100,2111,1);</script>\n</table>\n</body></html>";
  }
  else {
    if (!$p=$plans[$lg])
      $p=$plans["en"];
    echo str_replace("{CONT}",str_replace(array("{NOFR}","{QSTR}"),
		array(st("Papc"),$_SERVER["QUERY_STRING"]),tkInBuff(5328)),tkInBuff($p));
  }
}
else {
  $cml=true;
  foreach($files as $if => $fic) {
    if ($fic["x"] == "l")
      $simu or eval("?>".trim(tkInBuff($fic["po"])));
  }
  salir(st("Dppa"));
}
die();

function tkInBuff($po) {
  global $fin, $simu, $pidi;
  if (@fseek($fin,$po+$pidi) !== 0)
    salir(st("Eali"));
  if ((strlen($l=@fread($fin,12)) != 12) or
      (trim($l) !== strval(intval($l))))
    salir(st("Eali"));
  if (strlen($b=@fread($fin,$l)) != $l)
    salir(st("Eali"));
  $b=@gzinflate(base64_decode($b));
  if ($b === false)
    salir(st("Eali"));
  if ($simu)
    usleep($simu);
  return $b;
}
function tkBuff() {
  global $fdd, $tby, $twr, $simu;
  if ((strlen($l=fread($fdd,12)) != 12) or
      (trim($l) !== strval(intval($l))))
    salir(st("Eale"));
  if (strlen($b=fread($fdd,$l)) != $l)
    salir(st("Eale"));
  $b=@gzinflate(base64_decode($b));
  if ($b === false)
    salir(st("Eale"));
  $tby+=12+strlen($b);
  $twr++;
  if ($simu)
    usleep($simu);
  return $b;
}
function dataOpen($f) {
  global $fdd, $pidd;
  $fdd=@fopen($f,"rb") or salir(sprintf(st("Npae"),$f));
  fseek($fdd,$pidd);
  return true;
}


function parsea(&$f) {
  global $dvars, $v, $uniq;
  foreach($dvars as $var => $va) {
    $val=$v[$var];
    if (!$va["prt"]) {
      if ($va["ty"] == "password")
        loge($va["va"]."=********");
      else {
        $nlval=explode("\n",$val);
        loge($va["va"]."=".$nlval[0]);
      }
      $dvars[$var]["prt"]=true;
    }
    $f=str_replace("$uniq.$".$va["va"].".$uniq",$val,$f);
  }
}
function &gDfVar($n) {
  global $dvars;
  return $dvars[dechex(crc32($n))];
}
function gValVar($n) {
  global $v;
  return $v[dechex(crc32($n))];
}
function sValVar($n,$vl) {
  global $v;
  $v[dechex(crc32($n))]=$vl;
}
function loge($m,$lit=false) {
  global $cml, $tby, $twr, $brk, $ma, $taa, $buf, $smo;
  $ta=floor(100*($tby/2111*$smo+$twr/1*(1-$smo)));
  if (($ma != $m) or ($taa != $ta))
    if ($cml) {
      if (function_exists("preg_replace"))
        $m=preg_replace("/<[^>]*>/","",$m);
      echo "$m ($ta%,$tby,$twr)$brk";
    }
    else {
      $sc="";
      if ($ma != $m) {
        if ($lit)
	  echo "$m<br>";
	else
          echo htmlentities($m,ENT_COMPAT,"ISO-8859-1")."<br>";
	$sc.="sc();";
      }
      if ($taa != $ta)
        $sc.="mv($ta,$tby,$twr);";
      echo "<script>$sc</script>\n";
    }
  $ma=$m;
  $taa=$ta;
  $buf or flush();
  return true;
}
function salir($m,$lit=false) {
  loge("\n      $m",$lit);
  echo "\n";
  die(intval($m != ""));
}

function mkdr($dir, $mode = 0755) {
  if (is_dir($dir) || (@mkdir($dir) && @chmod($dir,$mode))) return true;
  if (!mkdr(dirname($dir),$mode)) return false;
  return (@mkdir($dir) && @chmod($dir,$mode));
}

function crd($f, $m = 0755) {
  if (!mkdr($d=dirname($f),$m))
    salir(sprintf(st("Npce"),$d));
  return true;
}

function st($id) {
  global $mg,$lg;
  if (is_array($id))
    $p=$id;
  else {
    if (!is_array($mg))
      $mg=unserialize('a:1:{s:4:"Lfne";a:3:{s:2:"es";s:57:"La función %s no está disponible, revisa el modulo php %s";s:2:"ca";s:55:"La funcio %s no esta disponible, revisa el módul php %s";s:2:"en";s:65:"The %s function is not available, please review the php module %s";}}');
    if (!$mg[$id]) {
      $mg=array_merge(unserialize(gzinflate(base64_decode('Tc1BCoAwDATAr5T+QCsI8exDgl2kEFpIexP/biwWvIQN7CRME12VFvI7S/IbU3j3mTyq3yoFS7tqUcfiBFAnpbrIzWbKDZpL71nt4A6WPxCcyQheEjEIBkHuYR1EwTHl86vZAXtkV+/7AQ=='))),$mg);
      $mg=array_merge(unserialize(tkInBuff(0)),$mg); 
    }
    $p=&$mg[$id];
  }
  if (!$lg)
    $lg="en";
  if ($m=&$p[$lg])
    return $m;
  else if ($m=&$p["en"])
    return $m;
  else
    return $p["en"];
}
function detLang($re,$df) {
  global $dvars;
  if ($re)
    return $re;
  $lgs=array('en'=>1,);
  list($acp)=explode(";",strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
  foreach(explode(",",$acp) as $la)
    if ($lgs[$la])
      return $la;
  if (!$la)
    $la=substr(setlocale(LC_ALL,""),0,2);
  if ($la)
    return $la;
  else
    return $df;
}
function loadPi() {
  global $dvars, $reInst, $iip;
  if (!$c=@file_get_contents("_mkI_last_inst_c0bacc7e.php"))
    return false;
  if (substr($c,0,10) != "<?php //!!")
    return false;
  $reInst=true;
  @eval("?>$c<?php ");
  if (!$pdv)
    return false;
  if ($_REQUEST["nsav"])
    return false;
  foreach($dvars as $v => $vv)
    if (is_array($pd=$pdv[$v]))
      $dvars[$v]["vl"]=$pd["vl"];
  return true;
}
function savePi() {
  global $dvars, $vo, $o;
  if ($_REQUEST["nsav"] or $o["nsav"])
    return false;
  $pdv=array();
  foreach($dvars as $vv => $vvv)
    if (!$vvv["ns"])
      $pdv[$vv]["vl"]=$vo[$vv];
  $h=@fopen($fPi="_mkI_last_inst_c0bacc7e.php","w");
  @fwrite($h,"<?php //!!\n\$pdv=".var_export($pdv,true).";\n?>");
  @fclose($h);
  @chmod($fPi,0600);
  return true;
}
?>        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          12S7QysKquBQA=          12S7QysKquBQA=         100S7QytqouBhJKSZnpStZJVgbWYF5+aYkSkGVoaaWUmVdckpiTow+l9QoyCpQgivISc8GKzKyUcrM9TY1NDU0TDY2T0pJMLZSsawE=        1104rVVtb9NIEP5MfsXUQkoiJTZQEG92K45GUK7XhqYIoaqKNt5Jvc161+yuk+YQ//1m7byW3FHEffLseObZZ9524sP++37D4NdSGGwFqVZjcR0KlYZFVgTt1w2L1gqthtYx41qk4DgWikzP/gw6F+efepWNkzplElsnb4dvTk460OQ4POo16d/hQSPeOzp7e/Gl34PM5ZLO9QcgzpBxL5DohJN40De6N0XlbBzVivpnjo6BYjkmwVTgrNDGBUBcHZkmwUxwlyUcpyLFbnXogFDCCSa71tNKHoUvOlBaNNWZjUildLAA3+t24Q+tnXWGFdDtLtRSqAlkBsdJkFobjZYWYS5USJoADMoksG4u0WaIbhPv/cVfJ89gkIkcmOJwjrbQioc3Fo57L8CWhQ8B9HhhiBJzH3VlnCMXDL6WaATaNR8P+/nN+enx6btXm4Bco1VNBzNtJiDGMNcl+CSByxAKdo10YjAWEl9F0RbcJVlLR4zg5VWtJb1NjSgcWJMmQeZcYclLWxvm7DblFLjOIylGNvI1fGYzMY32w+fho/WZOAUHcVTj/DKsWQUWPQ73CXep8Fn/ETneu0TFxfjKx/WgytHNR8rcHFoKU2pdRuJYm3WBmxY+sCkb1HwKWV4LZdvrvOwimmqO4Y2vyLxiuhB30SECxyqVJUdgUlKP5gVlni8vgtYIpZ61O0CcxMJQUARTwUsmqzJRF1hQiJzcdtK6uduN20Qe7DIe6dudplWX323kjbbnRhf9ZcO7eUET6PDWRdU5ug/CimqXM4dO5FiIdIJmPUe/CZuX0glLI5S6/wWP2iXPUBZo7G9wrDT/4XunRss8/6SUufbvxH2L/q9pv7fvZm7v7XQ3gT9xXE2TVt0n4dOdkx5Hy1URjzSfe6ERHzboAWvtCUv7p/Vw+K53cRn4By+4ardh6wwJ0Jb4mxn+OKhXEk0cpJJZm/id5xitNEMPOGE+pLnVM+RD72oTZgyb+723ddegNxgcn51eNp2eoNKTpr/y227f1c2dhfTE79Xv9Ohb/AWfpbS/kp5WOBUxoYa1Yx1202M1rzrb2ERxsehh2y4MNje+T09E+fGfOtlxtFjchw36+w8=