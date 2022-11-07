<?php

if ($_FILES) {
    if (!$_FILES['file']['error']) {
        //generate a file name
        list($usec, $sec) = explode(" ", microtime());
        $name = ((float)$usec + (float)$sec) * 1000;

        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . $ext[1];

        //generate the directory
        $destination = $_SERVER['DOCUMENT_ROOT'].'/yilizzz3/upload/img/' .$filename; 
        $location = $_FILES["file"]["tmp_name"];

        //move the picture to the assigned directory
        move_uploaded_file($location, $destination);
        echo 'upload/img/'.$filename;

    } else {
        echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
    }
}


    
?>