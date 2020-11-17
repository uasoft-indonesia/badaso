<?php

namespace Uasoft\Badaso\Controllers;

use Illuminate\Http\Request;

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
}
