<?php error_reporting(E_ERROR);
//error_reporting(E_ALL); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Quick Installer</title>
<style type="text/css">
/* Unzip v.1.0.1   23.11.2011 */
body, td {
	font-size: 14px;
	font-family:Arial, Helvetica, sans-serif;
	color: #4e4e4e;
	background:#fff;
	text-align:center;
}
.wrapper {
	text-align:left;
	background:#f4f6f5;
	padding:0px 0 20px;
	width:700px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	margin:30px auto;
}
.wrap {
	background:#ea433b;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius:5px;
	padding:5px;
	color:#fff;
}
.submit {
	border-top:1px solid #dbdbdb;
	padding-top:15px;
}
.submit input {
	margin-left:244px;
}
.indent {
	padding:16px 25px;
	border-top:3px solid #dfe1e0
}
.sect {
	overflow:hidden;
	margin-bottom:10px;
	width:100%;
}
.label {
	width:234px;
	float:left;
	text-align:left;
	margin-right:10px;
	line-height:42px;
	text-indent:100px
}
.field {
	width:350px;
	float:left;
}
.field span {
	font-size:10px
}
.css3button {
	cursor:pointer;
	font-family: Arial, Helvetica, sans-serif;
	font-weight:bold;
	font-size: 14px;
	color: #ffffff;
	padding: 10px 20px;
	background: -moz-linear-gradient(  top,  #54cbe9 0%,  #3790cf);
	background: -webkit-gradient(  linear, left top, left bottom,  from(#54cbe9),  to(#3790cf));
	border-radius: 18px;
	-moz-border-radius: 18px;
	-webkit-border-radius: 18px;
	border: 1px solid #ffffff;
	-moz-box-shadow:
 0px 0px 0px rgba(000, 000, 000, 0),  inset 1px 1px 2px rgba(255, 255, 255, 0.6);
	-webkit-box-shadow:
 0px 0px 0px rgba(000, 000, 000, 0),  inset 1px 1px 2px rgba(255, 255, 255, 0.6);
	text-shadow:
 1px 1px 0px rgba(000, 000, 000, 0.4),  0px 1px 0px rgba(255, 255, 255, 0.3);
	background:#54cbe9\9;
}
.css3input {
	font-size: 14px;
	color: #000000;
	padding: 10px;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius:5px;
	border: 1px solid #ccc;
	width:307px
}
.p3 {
	padding:7px;
}
a {
	color: #000066;
	text-decoration: none;
}
a:hover {
	color: #FF6600;
	text-decoration: underline;
}
.form_indent {
	padding-left:210px;
}
.small {
	font:normal 11px Arial, Helvetica, sans-serif
}
h1 {
	background:#ea433b;
	border-bottom:1px solid #be382f;
	-webkit-border-top-left-radius: 5px;
	-moz-border-top-left-radius: 5px;
	border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-top-right-radius: 5px;
	border-top-right-radius: 5px;
	text-indent:116px;
	color:#fff;
	font:bold 36px Arial, Helvetica, sans-serif;
	line-height:64px;
	margin:0;
	text-shadow:
 1px 2px 2px rgba(000, 000, 000, 0.4),  1px 2px 2px rgba(000, 000, 000, 0.3);
}
h1 div {
	border-bottom:1px solid #d23d36;
}
h1 div div {
	border-bottom:1px solid #e24137;
	height:64px;
}
.file_upload {
}
</style>
</head>
<body>
<?php // print_r ($_SERVER); ?>
<div class="wrapper">
    <h1><div><div>Quick Installer</div></div></h1>
    <div class="indent">
        <form name="myform" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" enctype="multipart/form-data" onSubmit="return check_uploadObject(this);">
            <?php
	if(!$_REQUEST["myaction"]):
?>
            <div class="sect">
                <div class="label"> Choose your zip file: </div>
                <div class="field">
                    <select name="zipfile" class="css3input">
                        <option value="" selected>- Please choose -</option>
                        <?php
                        $fdir = opendir('./');
                        while($file=readdir($fdir)){
                            if(!is_file($file)) continue;
                            if(preg_match('/\.zip$/mis',$file)){
                                echo "<option value='$file'>$file</option>\r\n";
                            }
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="sect">
                <div class="label"> Unzip to: </div>
                <div class="field">
                    <input name="todir" type="text" id="todir" value="" size="15" class="css3input">
                    <br>
                    <span>(Leave this field blank if you want to unzip into a current directory. Folder should be writable - 755 permissions)</span> </div>
            </div>
            <div class="sect submit">
                <input name="myaction" type="hidden" id="myaction" value="dounzip">
                <input type="submit" name="Submit" class="css3button" value="Unzip">
            </div>
            <?php

elseif($_REQUEST["myaction"]=="dounzip"):

class zip
{

 var $total_files = 0;
 var $total_folders = 0; 

 function Extract ( $zn, $to, $index = Array(-1) )
 {
   $ok = 0; $zip = @fopen($zn,'rb');
   if(!$zip) return(-1);
   $cdir = $this->ReadCentralDir($zip,$zn);
   $pos_entry = $cdir['offset'];

   if(!is_array($index)){ $index = array($index);  }
   for($i=0; $index[$i];$i++){
   		if(intval($index[$i])!=$index[$i]||$index[$i]>$cdir['entries'])
		return(-1);
   }
   for ($i=0; $i<$cdir['entries']; $i++)
   {
     @fseek($zip, $pos_entry);
     $header = $this->ReadCentralFileHeaders($zip);
     $header['index'] = $i; $pos_entry = ftell($zip);
     @rewind($zip); fseek($zip, $header['offset']);
     if(in_array("-1",$index)||in_array($i,$index))
     	$stat[$header['filename']]=$this->ExtractFile($header, $to, $zip);
   }
   fclose($zip);
   return $stat;
 }

  function ReadFileHeader($zip)
  {
    $binary_data = fread($zip, 30);
    $data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);

    $header['filename'] = fread($zip, $data['filename_len']);
    if ($data['extra_len'] != 0) {
      $header['extra'] = fread($zip, $data['extra_len']);
    } else { $header['extra'] = ''; }

    $header['compression'] = $data['compression'];$header['size'] = $data['size'];
    $header['compressed_size'] = $data['compressed_size'];
    $header['crc'] = $data['crc']; $header['flag'] = $data['flag'];
    $header['mdate'] = $data['mdate'];$header['mtime'] = $data['mtime'];

    if ($header['mdate'] && $header['mtime']){
     $hour=($header['mtime']&0xF800)>>11;$minute=($header['mtime']&0x07E0)>>5;
     $seconde=($header['mtime']&0x001F)*2;$year=(($header['mdate']&0xFE00)>>9)+1980;
     $month=($header['mdate']&0x01E0)>>5;$day=$header['mdate']&0x001F;
     $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
    }else{$header['mtime'] = time();}

    $header['stored_filename'] = $header['filename'];
    $header['status'] = "ok";
    return $header;
  }

 function ReadCentralFileHeaders($zip){
    $binary_data = fread($zip, 46);
    $header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);

    if ($header['filename_len'] != 0)
      $header['filename'] = fread($zip,$header['filename_len']);
    else $header['filename'] = '';

    if ($header['extra_len'] != 0)
      $header['extra'] = fread($zip, $header['extra_len']);
    else $header['extra'] = '';

    if ($header['comment_len'] != 0)
      $header['comment'] = fread($zip, $header['comment_len']);
    else $header['comment'] = '';

    if ($header['mdate'] && $header['mtime'])
    {
      $hour = ($header['mtime'] & 0xF800) >> 11;
      $minute = ($header['mtime'] & 0x07E0) >> 5;
      $seconde = ($header['mtime'] & 0x001F)*2;
      $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
      $month = ($header['mdate'] & 0x01E0) >> 5;
      $day = $header['mdate'] & 0x001F;
      $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
    } else {
      $header['mtime'] = time();
    }
    $header['stored_filename'] = $header['filename'];
    $header['status'] = 'ok';
    if (substr($header['filename'], -1) == '/')
      $header['external'] = 0x41FF0010;
    return $header;
 }

 function ReadCentralDir($zip,$zip_name){
	$size = filesize($zip_name);

	if ($size < 277) $maximum_size = $size;
	else $maximum_size=277;
	
	@fseek($zip, $size-$maximum_size);
	$pos = ftell($zip); $bytes = 0x00000000;
	
	while ($pos < $size){
		$byte = @fread($zip, 1); $bytes=($bytes << 8) | ord($byte);
		if ($bytes == 0x504b0506 or $bytes == 0x2e706870504b0506){ $pos++;break;} $pos++;
	}
	
	$fdata=fread($zip,18);
	
	$data=@unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size',$fdata);
	
	if ($data['comment_size'] != 0) $centd['comment'] = fread($zip, $data['comment_size']);
	else $centd['comment'] = ''; $centd['entries'] = $data['entries'];
	$centd['disk_entries'] = $data['disk_entries'];
	$centd['offset'] = $data['offset'];$centd['disk_start'] = $data['disk_start'];
	$centd['size'] = $data['size'];  $centd['disk'] = $data['disk'];
	return $centd;
  }

 function ExtractFile($header,$to,$zip){
	$header = $this->readfileheader($zip);
	
	if(substr($to,-1)!="/") $to.="/";
	if($to=='./') $to = '';	
	$pth = explode("/",$to.$header['filename']);
	$mydir = '';
	for($i=0;$i<count($pth)-1;$i++){
		if(!$pth[$i]) continue;
		$mydir .= $pth[$i]."/";
		if((!is_dir($mydir) && @mkdir($mydir,0777)) || (($mydir==$to.$header['filename'] || ($mydir==$to && $this->total_folders==0)) && is_dir($mydir)) ){
			@chmod($mydir,0777);
			$this->total_folders ++;
			echo "<input name='dfile[]' type='checkbox' value='$mydir' checked> <a href='$mydir' target='_blank'><strong>Directory: $mydir</strong></a><br>";
		}
	}
	
	if(strrchr($header['filename'],'/')=='/') return;	

	if (!($header['external']==0x41FF0010)&&!($header['external']==16)){
		if ($header['compression']==0){
			$fp = @fopen($to.$header['filename'], 'wb');
			if(!$fp) return(-1);
			$size = $header['compressed_size'];
		
			while ($size != 0){
				$read_size = ($size < 2048 ? $size : 2048);
				$buffer = fread($zip, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
			}
			fclose($fp);
			touch($to.$header['filename'], $header['mtime']);
		}else{
			$fp = @fopen($to.$header['filename'].'.gz','wb');
			if(!$fp) return(-1);
			$binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($header['compression']),
			Chr(0x00), time(), Chr(0x00), Chr(3));
			
			fwrite($fp, $binary_data, 10);
			$size = $header['compressed_size'];
		
			while ($size != 0){
				$read_size = ($size < 1024 ? $size : 1024);
				$buffer = fread($zip, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
			}
		
			$binary_data = pack('VV', $header['crc'], $header['size']);
			fwrite($fp, $binary_data,8); fclose($fp);
	
			$gzp = @gzopen($to.$header['filename'].'.gz','rb') or die("Failed to create directory");
			if(!$gzp) return(-2);
			$fp = @fopen($to.$header['filename'],'wb');
			if(!$fp) return(-1);
			$size = $header['size'];
		
			while ($size != 0){
				$read_size = ($size < 2048 ? $size : 2048);
				$buffer = gzread($gzp, $read_size);
				$binary_data = pack('a'.$read_size, $buffer);
				@fwrite($fp, $binary_data, $read_size);
				$size -= $read_size;
			}
			fclose($fp); gzclose($gzp);
		
			touch($to.$header['filename'], $header['mtime']);
			@unlink($to.$header['filename'].'.gz');
			
		}
	}
	
	$this->total_files ++;
	echo "<input name='dfile[]' type='checkbox' value='$to$header[filename]' checked> <a href='$to$header[filename]' target='_blank'>Files: $to$header[filename]</a><br>";

	return true;
 }

// end class
}

	set_time_limit(0);

	if(!$_POST["todir"]) $_POST["todir"] = ".";
	$z = new Zip;
	$have_zip_file = 0;
	function start_unzip($tmp_name,$new_name,$checked){
		global $_POST,$z,$have_zip_file;
		$upfile = array("tmp_name"=>$tmp_name,"name"=>$new_name);
		if(is_file($upfile["tmp_name"])){
			$have_zip_file = 1;
			echo "<br><div class='wrap'>In Process: <input name='dfile[]' type='checkbox' value='$upfile[name]' ".($checked?"checked":"")."> $upfile[name]</div><br><br>";
			if(preg_match('/\.zip$/mis',$upfile["name"])){
				$result=$z->Extract($upfile["tmp_name"],$_POST["todir"]);
				if($result==-1){
					echo "<br>File $upfile[name] error.<br>";
				}
				echo "<br>Done, Create $z->total_folders directory(s), $z->total_files file(s).<br><br><br>";
			}else{
				echo "<br>$upfile[name] is not a zip file.<br><br>";			
			}
			if(realpath($upfile["name"])!=realpath($upfile["tmp_name"])){
				@unlink($upfile["name"]);
				rename($upfile["tmp_name"],$upfile["name"]);
			}
		}
	}
	clearstatcache();
	
	start_unzip($_POST["zipfile"],$_POST["zipfile"],0);
	start_unzip($_FILES["upfile"]["tmp_name"],$_FILES["upfile"]["name"],1);

	if(!$have_zip_file){
		echo "<br>Please select or upload files.<br>";
	}
?>
            <input name="myaction" type="hidden" id="myaction" value="dodelete">
            <input name="button" type="button" class="css3button" value="go back" onClick="window.location='<?php $_SERVER["PHP_SELF"];?>';">
            <input type='button' class="css3button" value='Inverse' onclick='selrev();'>
            <input type='submit' class="css3button" onclick='return confirm("Delete the selected file?");' value='Delete the selected file'>
            <script language='javascript'>
function selrev() {
	with(document.myform) {
		for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			if(thiselm.name.match(/dfile\[]/))	thiselm.checked = !thiselm.checked;
		}
	}
}
alert('Completed.');
</script>
            <?php

elseif($_REQUEST["myaction"]=="dodelete"):
	set_time_limit(0);
	
	$dfile = $_POST["dfile"]; 
	echo "Deleting files...<br><br>";
	if(is_array($dfile)){
		for($i=count($dfile)-1;$i>=0;$i--){
			if(is_file($dfile[$i])){
				if(@unlink($dfile[$i])){
					echo "Deleted files: $dfile[$i]<br>";
				}else{
					echo "Delete file failed: $dfile[$i]<br>";
				}
			}else{
				if(@rmdir($dfile[$i])){
					echo "<strong>Deleted directory: $dfile[$i]</strong><br>";
				}else{
					echo "<strong>Failed to delete directory: $dfile[$i]</strong><br>";
				}				
			}
			
		}
	}
	echo "<br>Completed.<br><br><input type='button' value='go back' class='css3button' onclick=\"window.location='$_SERVER[PHP_SELF]';\"><br><br>
		 <script language='javascript'>('Completed.');</script>";

endif;
?>
        </form>
    </div>
</div>
</body>
</html>