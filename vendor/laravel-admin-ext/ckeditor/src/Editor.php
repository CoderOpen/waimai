<?php

namespace Encore\CKEditor;

use Encore\Admin\Form\Field\Textarea;

class Editor extends Textarea
{
    protected $view = 'laravel-admin-ckeditor::editor';

    protected static $js = [
        'vendor/laravel-admin-ext/ckeditor/ckeditor.js',
    ];

    public function render()
    {
        $config = (array) CKEditor::config('config');

        $config = json_encode(array_merge($config, $this->options));

        $this->script = <<<EOT
CKEDITOR.replace('{$this->id}', $config);
EOT;
        return parent::render();
    }
}
