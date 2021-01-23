<?php
namespace Zhpefe\SelectTree;

use Encore\Admin\Grid\Filter\Presenter\Presenter;

class FilterPresenter extends Presenter
{
    public function view() : string
    {
        return 'select-tree::filter';
    }
}