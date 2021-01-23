<?php

namespace Zhpefe\SelectTree;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid\Filter;
use Illuminate\Support\ServiceProvider;

class SelectTreeServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(SelectTree $extension)
    {
        if (! SelectTree::boot()) {
            return ;
        }
        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'select-tree');
        }
        Admin::booting(function () {
            Form::extend('selectTree', SelectTreeForm::class);
            Form::extend('multipleSelectTree', MultipleSelectForm::class);
            Filter::extend('selectTree', SelectTreeFilter::class);
        });
    }
}