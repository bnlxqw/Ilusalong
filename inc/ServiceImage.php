<?php
class ServiceImage {
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
