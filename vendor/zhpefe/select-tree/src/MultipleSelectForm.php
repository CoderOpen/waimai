<?php

namespace Zhpefe\SelectTree;

use Encore\Admin\Form\Field\MultipleSelect;

class MultipleSelectForm extends MultipleSelect
{
    protected $view = 'select-tree::multipleselect';

    protected $url = null;
    protected $top_id = 0;

    public function ajax($url, $idField = 'id', $textField = 'text')
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
            'top_id' => $this->top_id,
            'url' => $this->url,
        ];
        if( ! $this->url ){
            Handler::error('Error', 'select-tree: You need $form->multiple_select_tree(column,label)->ajax()');
        }
        return parent::render()->with(compact('vars'));
    }
}