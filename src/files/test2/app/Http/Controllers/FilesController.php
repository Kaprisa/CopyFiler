<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FilesController extends Controller
{
    //const EXCLUDES = ['node_modules', 'vendor'];

    public function getDirectories()
    {
        $res = [
            'public' => $this->getDir(public_path()),
            'storage' => $this->getDir(storage_path()),
            'database' => $this->getDir(database_path()),
            'resources' => $this->getDir(resource_path()),
            'app' => $this->getDir(app_path()),
        ];

        return response()->json($res, 200);
    }

    public function getFile(Request $request) {
        if (File::exists($request->path)) {
            $file = File::get($request->path);
            return response()->json($file, 200);
        }
        return response()->json([ 'errors' => [ 'Невозможно открыть файл' ]], 404);
    }

    public function removeFile(Request $request)
    {
        if (File::exists(public_path($request->path))) {
            $file = File::delete(public_path($request->path));
            return null;
        }
        return response()->json([ 'errors' => [ 'Невозможно удалить файл' ]], 404);
    }

    private function getDir($path)
    {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
        $r = array();
        foreach ($it as $file_info) {
            $name = $file_info->getFileName();
            if ( $name === '.' || $name === '..') continue;
            $path = $file_info->isDir()
                ? array($name => array())
                :
                array(collect(
                    [
                        'name' => $name,
                        'ext' => $file_info->getExtension(),
                        'size' => $file_info->getSize(),
                        'path' => str_replace(public_path(), '', $file_info->getPath()).'/'.$name
                    ]
                ));
            for ($depth = $it->getDepth() - 1; $depth >= 0; $depth--) {
                $path = array($it->getSubIterator($depth)->current()->getFilename() => $path);
            }
            $r = array_merge_recursive($r, $path);
        }
        return $r;
    }
}
