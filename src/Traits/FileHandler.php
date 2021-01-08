<?php

namespace Uasoft\Badaso\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

trait FileHandler
{
    public function handleUploadFiles($files, $data_type = null, $custom_path = null)
    {
        $path_List = [];
        foreach ($files as $file) {
            $uuid = Uuid::generate(4);
            if (is_array($file) && array_key_exists('base64', $file)) {
                $encoded_file = $file['base64'];
                $decoded_file = base64_decode(explode(',', $encoded_file)[1]);
                $filename = '';
                if (array_key_exists('name', $file)) {
                    $filename = $uuid.'-'.$file['name'];
                }
                $filepath = 'uploads/';
                if (!is_null($data_type)) {
                    $filepath .= $data_type->slug.'/';
                }

                if (!is_null($custom_path)) {
                    $filepath .= $custom_path.'/';
                }

                Storage::disk(config('badaso.storage.disk', 'public'))->put($filepath.$filename, $decoded_file);

                $path_List[] = $filepath.$filename;
            } else {
                $path_List[] = $file;
            }
        }

        return $path_List;
    }

    public function handleDeleteFile($file)
    {
        return Storage::disk(config('badaso.storage.disk', 'public'))->delete($file);
    }

    public function handleDownloadFile($file)
    {
        return Storage::disk(config('badaso.storage.disk', 'public'))->download($file);
    }

    public function handleViewFile($file)
    {
        try {
            $file = Storage::disk(config('badaso.storage.disk', 'public'))->path($file);
            $mime = mime_content_type($file);
            header("Content-type:$mime");
            ob_clean();
            readfile($file);
        } catch (Exception $e) {
        }
    }
}
