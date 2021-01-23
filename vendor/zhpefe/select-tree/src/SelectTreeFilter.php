<?php
namespace Zhpefe\SelectTree;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Filter\AbstractFilter;
use Encore\Admin\Exception\Handler;

class SelectTreeFilter extends AbstractFilter
{
    protected $top_id = 0;
    protected $url = '';
    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label = $label;
        $this->id = 'select-tree-' . uniqid();
        $this->setPresenter(new FilterPresenter());
    }

    public function ajax($url)
    {
        $this->url = $url;
        return $this;
    }

    public function topID($id)
    {
        $this->top_id = $id;
        return $this;
    }

    public function render()
    {
        $vars = [
            'id' => $this->id,
            'top_id' => $this->top_id,
            'url' => $this->url,
        ];
        if( ! $this->url ){
            Handler::error('Error', 'select-tree: You need $filter->select_tree(column,label)->ajax(url)');
        }
        return parent::render()->with(compact('vars'));
    }
}