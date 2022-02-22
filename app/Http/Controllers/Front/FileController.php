<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\File as R;
use App\Http\Requests\Request;
use App\Travel\Files\File;
use App\Travel\Files\Requests\GetHistoryRequest;
use App\Travel\Files\Services\FileService;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use Storage;

class FileController extends Controller
{
    public $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function upload(R\UploadFileRequest $request)
    {
        $file = $request->file('file');

        $name = $file->getClientOriginalName();
        $hashName = $file->hashName();
        $size = $file->getSize();
        $extension = $file->getClientOriginalExtension();

        $disk = Storage::disk('file');

        $dir = date('Y') . '/' . date('m');
        $disk->putFile($dir, $file);

        $new_file = File::query()->create([
            'extension' => $extension,
            'name' => $name,
            'hash_name' => $hashName,
            'size' => $size,
            'url' => $disk->url("$dir/$hashName"),
            'path' => $disk->path("$dir/$hashName"),
        ]);

        return [
            'status_code' => 200,
            'response' => $new_file
        ];
    }
    public function find(\Illuminate\Http\Request $request)
    {
        $file_id = $request->get('id');
        $file = File::query()->find($file_id);

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename={$file['name']}");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        readfile($file['path']);
        die();
    }

    public function download(R\DownloadFileRequest $request)
    {
        $file_id = $request->get('file_id');

        $file = File::query()->find($file_id);

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=${$file['name']}");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        $header = [
            'File-Name' => urlencode($file['name']),
            'Cache-Control' => 'public',
            'Content-Disposition' => 'attachment; filename ' . urlencode($file['name']),
            'Content-Type' => 'application/zip',
            'Content-Transfer-Encoding' => 'binary',
        ];
        readfile($file['path']);
        die();

        return response()->download($file['path'], null, $header);
    }

    public function getFileHistory(GetHistoryRequest $request)
    {
        try {
            return response()->json(['file_history' => $this->service->getHistoryByProject($request)], 200);

        } catch (NotFoundGuaranteeProjectErrorException $e) {
            return response()->json([], 422);
        }
    }
}
