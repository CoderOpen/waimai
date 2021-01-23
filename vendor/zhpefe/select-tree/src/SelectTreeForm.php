<?php

namespace Zhpefe\SelectTree;

use Encore\Admin\Form\Field;
use Encore\Admin\Exception\Handler;

class SelectTreeForm extends Field
{
    protected $view = 'select-tree::select';

    /**
     * SelectTree constructor.
     *
     * @param array $column
     * @param array $arguments
     */

    protected $url = null;
    protected $top_id = 0;
    protected $config = [];

    public function __construct($column, $arguments)
    {
        $this->column = $column;
        $this->label = empty($arguments) ? $column : current($arguments);
        $this->id = 'select-tree-' . uniqid();
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
        $this->attribute('data-value', implode(',', (array) $this->value()));
        $vars = [
            'id' => $this->id,
            'top_id' => $this->top_id,
            'url' => $this->url,
        ];
        if( ! $this->url ){
            Handler::error('Error', 'select-tree: You need $form->select_tree(column,label)->ajax()');
        }
        return parent::render()->with(compact('vars'));
    }
}