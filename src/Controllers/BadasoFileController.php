<?php

namespace Uasoft\Badaso\Controllers;

use Illuminate\Http\Request;
use Uasoft\Badaso\Helpers\ApiResponse;
use UniSharp\LaravelFilemanager\Controllers\DeleteController;
use UniSharp\LaravelFilemanager\Controllers\ItemsController;
use UniSharp\LaravelFilemanager\Controllers\UploadController;

class BadasoFileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $files = $request->input('files', []);

        return $this->handleUploadFiles($files);
    }

    public function downloadFile(Request $request)
    {
        $file = $request->input('file', []);

        return $this->handleDownloadFile($file);
    }

    public function deleteFile(Request $request)
    {
        $file = $request->input('file', []);

        $this->handleDeleteFile($file);
    }

    public function viewFile(Request $request)
    {
        $file = $request->input('file', []);

        return $this->handleViewFile($file);
    }

    /**
     * @param  int  $page  => current page number
     * @param  string  $working_dir  => working directory
     * @param  string  $sort_type  => sorting type: name | date
     * @return mixed
     */
    public function browseFileUsingLfm(Request $request)
    {
        $item = new ItemsController();
        $file = $item->getItems();

        return ApiResponse::success(json_decode(json_encode($file)));
    }

    public function uploadFileUsingLfm(Request $request)
    {
        $upload = new UploadController();
        $file = $upload->upload();

        if (key_exists('error', $file->original)) {
            return ApiResponse::failed($file);
        }

        return ApiResponse::success(json_decode(json_encode($file)));
    }

    public function deleteFileUsingLfm(Request $request)
    {
        $delete = new DeleteController();
        $file = $delete->getDelete();

        return ApiResponse::success(json_decode(json_encode($file)));
    }

    public function availableMimetype()
    {
        return ApiResponse::success(config('lfm.folder_categories'));
    }
}
