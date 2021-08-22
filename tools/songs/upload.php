<?php
include "../../incl/lib/connection.php";
require_once "../../incl/lib/exploitPatch.php";
if ($_FILES && $_FILES['filename']['error']== UPLOAD_ERR_OK)
{
    
$file_type = $_FILES['filename']['type'];
$allowed = array("audio/mpeg", "audio/ogg", "audio/mp3");
if(!in_array($file_type, $allowed)) {
    $er = "You can upload only audios!";
} else {
    $maxsize = 8388608;
    if($_FILES['filename']['size'] >= $maxsize) {
        $er = "Max file size is 8mb";
    } else {
        $length = 10;
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        move_uploaded_file($_FILES['filename']['tmp_name'], "$string.mp3");
        $hash = "";
      $servername = $_SERVER['SERVER_NAME'];
        $song = "http://$servername/database/tools/songs/$string.mp3";
	    $query = $db->prepare("INSERT INTO songs (name, authorID, authorName, size, download, hash)
		VALUES (:name, '9', :author, :size, :download, :hash)");
		$query->execute([':name' => $string, ':download' => $song, ':author' => "Custom song", ':size' => "-0", ':hash' => $hash]);
		$er = "Song reuploaded: <b>ID ".$db->lastInsertId()."</b>";
        
    }
    
    
}

} else {

}
?>
<p>
                <?php
                if($er == "") {
                    echo "Upload music";
                } else {
                    echo $er;
                }
                ?>
              </p>
              <form method="post" action="upload.php" enctype='multipart/form-data'>
Choose file: <input type='file' name='filename' size='10' />
</form>
