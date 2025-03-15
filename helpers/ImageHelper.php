<?php
class ImageHelper {
    private static $base_path;
    private static $upload_dir;
    private static $default_image = "no-image.jpg";

    public static function init() {
        // Set base path
        self::$base_path = dirname(dirname(__FILE__)); // Gets parent directory of helpers
        self::$upload_dir = self::$base_path . "/assets/news/";

        // Create upload directory if it doesn't exist
        if (!file_exists(self::$upload_dir)) {
            mkdir(self::$upload_dir, 0777, true);
        }

        // Copy default image if it doesn't exist
        $default_image_path = self::$upload_dir . self::$default_image;
        if (!file_exists($default_image_path)) {
            $source = self::$base_path . "/assets/" . self::$default_image;
            if (file_exists($source)) {
                copy($source, $default_image_path);
            }
        }
    }

    public static function getImageUrl($image_name) {
        if (!empty($image_name)) {
            $image_path = self::$upload_dir . $image_name;
            if (file_exists($image_path)) {
                // Convert to web path
                return str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $image_path));
            }
        }
        // Return default image path
        return str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', self::$upload_dir . self::$default_image));
    }

    public static function uploadImage($file, $old_image = null) {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Create unique filename
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = self::$upload_dir . $new_filename;

        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            return false;
        }

        // Check file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Delete old image if exists
            if ($old_image && $old_image != self::$default_image) {
                $old_image_path = self::$upload_dir . $old_image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            return $new_filename;
        }

        return false;
    }

    public static function deleteImage($image_name) {
        if ($image_name && $image_name != self::$default_image) {
            $image_path = self::$upload_dir . $image_name;
            if (file_exists($image_path)) {
                return unlink($image_path);
            }
        }
        return false;
    }
}

// Initialize image system
ImageHelper::init();
?> 