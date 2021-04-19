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

    public function handlePathFile($file)
    {
        $disk = config('badaso.storage.disk');
        $path = '';
        if (!is_null($file) && $file != '') {
            $file_exists = Storage::disk($disk)->exists($file);
            if ($file_exists) {
                if ($disk != 'public' && $disk != 'local') {
                    $path = Storage::disk($disk)->url($file);
                } else {
                    $path = Storage::disk($disk)->path($file);
                }
            }
        }

        return $path;
    }

    public function handleViewFile($file)
    {
        try {
            $path = $this->handlePathFile($file);
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                $headers = get_headers($path, 1);
                $type = $headers['Content-Type'];
                header("Content-type:$type");
                ob_clean();
                readfile($path);

                return;
            }
            $mime = mime_content_type($path);
            header("Content-type:$mime");
            ob_clean();
            readfile($path);
        } catch (Exception $e) {
            \Log::debug($e->getMessage());
            $path = public_path('badaso-images/badaso.png');
            $mime = mime_content_type($path);
            header("Content-type:$mime");
            ob_clean();
            readfile($path);
        }
    }
}
