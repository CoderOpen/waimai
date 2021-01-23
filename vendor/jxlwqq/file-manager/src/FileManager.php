<?php

namespace Jxlwqq\FileManager;

use Encore\Admin\Extension;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Plugin\ListWith;

class FileManager extends Extension
{
    public $name = 'file-manager';

    public $views = __DIR__ . '/../resources/views';


    public $menu = [
        'title' => 'FileManager',
        'path' => 'file-manager',
        'icon' => 'fa-file',
    ];

    protected $fileTypes = [
        'image' => 'png|jpg|jpeg|tmp|gif',
        'word' => 'doc|docx',
        'ppt' => 'ppt|pptx',
        'excel' => 'xls|xlsx',
        'pdf' => 'pdf',
        'code' => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        'zip' => 'zip|tar\.gz|rar|rpm',
        'txt' => 'txt|pac|log|md',
        'audio' => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        'video' => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg',
    ];

    public $path;
    public $storage;

    public function __construct($path = '/')
    {
        $this->path = $path;

        $this->initStorage();
    }

    private function initStorage()
    {
        $disk = static::config('disk');

        $this->storage = Storage::disk($disk);
    }

    public function download()
    {
        $path_parts = pathinfo($this->path);
        $filename = $path_parts['basename'];
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        copy($this->storage->url($this->path), $temp_file);
        return response()->download($temp_file, $filename);
    }

    public function upload($files = [])
    {
        foreach ($files as $file) {
            $this->storage->putFile($this->path, $file);
        }

        return true;
    }


    public function exists()
    {
        return $this->storage->exists($this->path);
    }

    public function newFolder($name)
    {
        $path = rtrim($this->path, '/') . '/' . trim($name, '/');

        return $this->storage->makeDirectory($path);
    }

    public function delete($path)
    {
        $paths = is_array($path) ? $path : func_get_args();

        foreach ($paths as $path) {
                $this->storage->delete($path);
        }

        return true;
    }

    public function move($new)
    {
        return $this->storage->move($this->path, $new);
    }


    public function ls()
    {
        $files = $this->storage->files($this->path);

        $directories = $this->storage->directories($this->path);

        return $this->formatDirectories($directories)
            ->merge($this->formatFiles($files))
            ->sort(function ($item) {
                return $item['name'];
            })->all();
    }

    public function formatFiles($files = [])
    {
        $files = array_map(function ($file) {
            return [
                'download' => route('file-manager-download', compact('file')),
                'icon' => '',
                'name' => $file,
                'preview' => $this->getFilePreview($file),
                'isDir' => false,
                'size' => '',
                'link' => route('file-manager-download', compact('file')),
                'url' => $this->storage->url($file),
                'time' => '',
            ];
        }, $files);

        return collect($files);
    }

    public function formatDirectories($dirs = [])
    {
        $url = route('file-manager-index', ['path' => '__path__', 'view' => request('view')]);

        $preview = "<a href=\"$url\"><span class=\"file-icon text-aqua\"><i class=\"fa fa-folder\"></i></span></a>";

        $dirs = array_map(function ($dir) use ($preview) {
            return [
                'download' => '',
                'icon' => '',
                'name' => $dir,
                'preview' => str_replace('__path__', $dir, $preview),
                'isDir' => true,
                'size' => '',
                'link' => route('file-manager-index', ['path' => '/' . trim($dir, '/'), 'view' => request('view')]),
                'url' => $this->storage->url($dir),
                'time' => '',
            ];
        }, $dirs);

        return collect($dirs);
    }

    public function navigation()
    {
        $folders = explode('/', $this->path);

        $folders = array_filter($folders);

        $path = '';

        $navigation = [];

        foreach ($folders as $folder) {
            $path = rtrim($path, '/') . '/' . $folder;

            $navigation[] = [
                'name' => $folder,
                'url' => route('file-manager-index', ['path' => $path]),
            ];
        }

        return $navigation;
    }

    public function urls()
    {
        return [
            'path' => $this->path,
            'index' => route('file-manager-index'),
            'move' => route('file-manager-move'),
            'delete' => route('file-manager-delete'),
            'upload' => route('file-manager-upload'),
            'new-folder' => route('file-manager-new-folder'),
        ];
    }

    public function getFilePreview($file)
    {
        switch ($this->detectFileType($file)) {
            case 'image':
                $url = $this->storage->url($file);
                if ($url) {
                    $preview = "<span class=\"file-icon has-img\"><img src=\"$url\" alt=\"Attachment\"></span>";
                } else {
                    $preview = '<span class="file-icon"><i class="fa fa-file-image-o"></i></span>';
                }

                break;
            case 'pdf':
                $preview = '<span class="file-icon"><i class="fa fa-file-pdf-o"></i></span>';
                break;

            case 'zip':
                $preview = '<span class="file-icon"><i class="fa fa-file-zip-o"></i></span>';
                break;

            case 'word':
                $preview = '<span class="file-icon"><i class="fa fa-file-word-o"></i></span>';
                break;

            case 'ppt':
                $preview = '<span class="file-icon"><i class="fa fa-file-powerpoint-o"></i></span>';
                break;

            case 'excel':
                $preview = '<span class="file-icon"><i class="fa fa-file-excel-o"></i></span>';
                break;

            case 'txt':
                $preview = '<span class="file-icon"><i class="fa fa-file-text-o"></i></span>';
                break;

            case 'code':
                $preview = '<span class="file-icon"><i class="fa fa-code"></i></span>';
                break;

            case 'audio':
                $preview = '<span class="file-icon"><i class="fa fa-file-audio-o"></i></span>';
                break;
            case 'video':
                $preview = '<span class="file-icon"><i class="fa fa-file-video-o"></i></span>';
                break;

            default:
                $preview = '<span class="file-icon"><i class="fa fa-file"></i></span>';
        }

        return $preview;
    }

    protected function detectFileType($file)
    {
        $extension = File::extension($file);

        foreach ($this->fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", $extension) !== 0) {
                return $type;
            }
        }

        return false;
    }
}
