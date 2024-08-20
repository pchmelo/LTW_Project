<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        exit('POST request method required');
    }

    if ($_POST['csrf'] !== $_SESSION['csrf']) {
        die("CSRF token validation failed");
    }

    if (empty($_FILES)) {
        exit('$_FILES is empty - is file_uploads set to "Off" in php.ini?');
    }

    if($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        switch($_FILES['image']['error']) {
            case UPLOAD_ERR_PARTIAL:
                exit('File upload was not completed');
                break;
            case UPLOAD_ERR_NO_FILE:
                exit('Zero-length file uploaded');
                break;
            case UPLOAD_ERR_EXTENSION:
                exit('File upload stopped by extension');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                exit('Exceeded filesize limit');
                break;
            case UPLOAD_ERR_INI_SIZE:
                exit('Exceeded filesize limit');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                exit('No upload folder available');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                exit('Error writing to disk');
                break;
            default:
                exit('Unknown error');
                break;
        }
    }

    if ($_FILES["image"]["size"] > 1048576) {
        exit('File too large (max 1MB)');
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($_FILES["image"]["tmp_name"]);

    $mime_types = ["image/gif", "image/png", "image/jpeg"];

    if ( ! in_array($_FILES["image"]["type"], $mime_types)) {
        exit("Invalid file type");
    }


    $uploads_dir = __DIR__ . "/../assets/uploads/";

    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);    
    }

    $pathinfo = pathinfo($_FILES["image"]["name"]);
    $base = $pathinfo["filename"];

    $base = "success_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);
    $filename = $base . "." . $pathinfo["extension"];
    $destination = $uploads_dir . $filename;

    if ($_FILES["image"]["error"] > 0) {
        echo "Error: " . $_FILES["image"]["error"] . "<br>";
    } else if (!is_dir(dirname($destination))) {
        echo "Error: Destination directory does not exist.<br>";
    }

    $i = 1;

    while (file_exists($destination)) {
        $filename = $base . "-" . $i . "." . $pathinfo["extension"];
        $destination = $uploads_dir . $filename;
        $i++;
    }
    
    if ($_FILES["image"]["error"] > 0) {
        echo "Error: " . $_FILES["image"]["error"] . "<br>";
    } else if (!is_dir(dirname($destination))) {
        echo "Error: Destination directory does not exist.<br>";
    } else if (!is_writable(dirname($destination))) {
        echo "Error: Destination directory is not writable.<br>";
    } else if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
        echo "Error: Could not move uploaded file.<br>";
    } else {
        echo $filename; 
    }
