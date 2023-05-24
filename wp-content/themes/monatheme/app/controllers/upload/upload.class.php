<?php

class Mona_upload
{

    public function mona_upload_image($file, $wtm = false)
    {

        require_once(get_template_directory() . '/app/controllers/upload/watermark.class.php');
        if ($wtm) {
            $water = new Mona_Watermark_Uploads();

            $file = $water->handle_file($file);
        }
        // var_dump($file);

        if (!function_exists('wp_handle_sideload')) {



            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        if (!function_exists('wp_handle_upload')) {



            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $overrides = array(
            'test_form' => false,
            // Setting this to false lets WordPress allow empty files, not recommended
            // Default is true
            'test_size' => true,
        );
        // var_dump($file, $overrides);
        $file_return = wp_handle_sideload($file, $overrides);
        // var_dump($file_return);
        return $this->mona_crete_attachment($file_return);
    }

    public function mona_upload_image_base64($file, $wtm = false)
    {
        $upload_dir = wp_upload_dir();
        // @new
        $upload_path = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;

        $img = $file['base'];

        unset($file['base']);
        if (!preg_match('/data:([^;]*);base64,(.*)/', $img, $matches)) {
            die("error");
        }
        // Decode the data
        $decoded = base64_decode($matches[2]);
        // @new
        $image_upload = file_put_contents($upload_path . $file['name'], $decoded);

        $file['tmp_name'] = $upload_path . $file['name'];

        
        return $this->mona_upload_image($file);
    }

    public function mona_upload_file($file)
    {

        if (!function_exists('wp_handle_sideload')) {

            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        if (!function_exists('wp_handle_upload')) {

            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $overrides = array(
            'test_form' => false,
            // Setting this to false lets WordPress allow empty files, not recommended
            // Default is true
            'test_size' => true,
        );

        $file_return = wp_handle_upload($file, $overrides);

        return $file_return;
    }

    function upload_user_file($file = array())
    {

        require_once(ABSPATH . 'wp-admin/includes/admin.php');

        $file_return = wp_handle_upload($file, array('test_form' => false));

        if (isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
            return false;
        } else {

            $filename = $file_return['file'];

            $attachment = array(
                'post_mime_type' => $file_return['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $file_return['url']
            );

            $attachment_id = wp_insert_attachment($attachment, $file_return['url']);

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            if (0 < intval($attachment_id)) {
                return $attachment_id;
            }
        }

        return false;
    }

    public function mona_crete_attachment($file)
    {

        $attachment = array(
            'post_mime_type' => $file['type'],
            'post_title' => sanitize_file_name(basename($file['file'])),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $upload_dir = wp_upload_dir();

        $filepatch = $file['file'];

        $attach_id = wp_insert_attachment($attachment, $filepatch);

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attach_data = wp_generate_attachment_metadata($attach_id, $filepatch);

        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }
}
