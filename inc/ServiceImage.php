<?php
class ServiceImage {
    public static function readUploadedImage($fieldName, &$error) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            $error = 'The image upload failed. Please try again with a smaller image.';
            return false;
        }

        if ($_FILES[$fieldName]['size'] > 512 * 1024) {
            $error = 'The optimized image must be 512 KB or smaller. Select another image or reduce its size.';
            return false;
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES[$fieldName]['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedTypes, true)) {
            $error = 'Choose a valid JPEG, PNG, GIF, or WebP image.';
            return false;
        }

        $image = file_get_contents($_FILES[$fieldName]['tmp_name']);
        if ($image === false) {
            $error = 'The uploaded image could not be read.';
            return false;
        }
        return $image;
    }

    public static function dataUri($image) {
        if ($image === null || $image === '') {
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $image);
        finfo_close($finfo);
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        if (!in_array($mime, $allowedTypes, true)) {
            return '';
        }

        return 'data:' . $mime . ';base64,' . base64_encode($image);
    }
}
?>
