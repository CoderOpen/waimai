<?php
/**
 * 2020年06月09日 19:15
 */

/**
 * 2019年07月24日 21:39
 */


namespace App\Admin\Controllers\ChiHe;

use App\Models\ChiHe\Link;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Layout\Content;

class LinkController extends AdminController
{
    protected $title = '友链管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $localAppId = request()->get('local_app_id', 0);
        $csrf    = csrf_token();
        $script  = <<<EOT
        $('.grid-row-delete').unbind('click').click(function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            swal({
                title: "确认删除",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    $.ajax({
                        method: 'post',
                        url: '/admin/series-product/delete/' + id + '?type=' + type, 
                        data:{
                            _token:"$csrf"
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');
                            console.log(data);
                            if(data.code == 1){
                                swal(data.msg, '', 'success');
                            }else{
                                swal(data.msg, '', 'error');
                            }
                        }
                    });
                }
            });
        });
EOT;
        Admin::script($script);

        $comment     = (new Link());
        $showStatus    = Link::showStatus();
        $grid        = new Grid($comment);
        //$grid       = $grid->model()->with('local_app');
        //$grid->disableFilter();
        $grid->column('id', 'ID')->sortable();
        $grid->disableExport(); //禁止导出
        $grid->disableRowSelector(); //禁止行checkbox选择
        $grid->disableColumnSelector(); //禁止显示列显示选择器
        //$grid->disableActions();
        //$grid->disableCreateButton();

        //$grid->disableActions();//$grid->column('pictures', '图片')->editable();
        $grid->column('app_id', '友链应用ID');
        $grid->column('app_name', '友链应用名称');
        $grid->column('path', '友链应用路径')->editable();
        $grid->column('description', '描述');
        $grid->column('status', '显示与否')->editable()->select($showStatus);
        $grid->column('sort', '排序')->editable();
        $grid->column('created_at', '创建时间')->editable();
        $grid->column('updated_at', '更新时间')->editable();

        //$grid->column('updated_at', '修改时间');
        //$grid->column('remark', '备注')->editable();


        $grid->actions(function (Grid\Displayers\Actions $actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            $type = request()->get('type', 1);

            //$actions->append("<a class='btn btn-xs action-btn grid-row-edit' data-id='{$actions->getKey()}' data-type='{$type}'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;");
            //$actions->append("<a class='btn btn-xs action-btn grid-row-delete' data-id='{$actions->getKey()}' data-type='{$type}'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;");

            $actions->disableView();
            // prepend一个操作
        });
        $grid->tools(function (Grid\Tools $tools) use ($localAppId) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
                //$actions->disableView();

            });
        });

        $grid->filter(function ($filter) use ($showStatus,$localAppId) {
            $filter->expand(); //展开搜索操作
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            $filter->column(8, function ($filter) use ($showStatus, $localAppId) {
                // 在这里添加字段过滤器
                $filter->like('app_name', '友链应用名称');
                $filter->equal('created_at', '创建日期')->date();
                $filter->equal('status', '是否展示')->select($showStatus);
            });
        });
        $grid->model()->orderBy('id', 'desc');
        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $showStatus    = Link::showStatus();
        $url         = request()->header('referer');
        $query       = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $param);
        $form = new Form(new Link());

        $userTable  = (new Link())->getTable();
        $connection = 'mysql';
        $form->tools(function (Form\Tools $tools) {
            // 去掉`列表`按钮
            $tools->disableList();
            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->display('id', 'ID');
        $form->text('app_id', '友链应用ID');
        $form->text('app_name', '友链应用名称');
        $form->textarea('description', '友链应用')
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},description,{{id}}"]);
        $form->image('logo', '友链应用LOGO');
        $form->select('status', '是否展示')->options($showStatus)->default(1);
        $form->number('sort', '排序')->required('integer');
        $form->text('path', '友链应用路径');
        $form->datetime('created_at', '创建时间');
        $form->datetime('updated_at', '更新时间');
        //$form->text('specs', '规格')->rules('required');

        $form->saving(function (Form $form) {
            //$form->image_url;
            //$form->model()->created_date = date("Y-m-d", strtotime($form->created_at));
        });
        return $form;
    }
}
