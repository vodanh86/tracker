<?php

namespace App\Admin\Controllers;

use App\Models\AlertModel;
use App\Models\HistoryLocationModel;
use App\Models\UserFriendModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AHistoryLocationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý vị trí (history-location)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new HistoryLocationModel());
        $grid->column('user.name', __('Tên người dùng'))->filter('like');
        $grid->column('lat', __('Lat'));
        $grid->column('long', __('Longtitude'));
        $grid->column('location_name', __('Địa điểm'))->filter('like');
        $grid->column('datetime', __('Thời gian'))->vndate()->filter('range', 'date');

        $grid->column('created_at', trans('admin.created_at'))->vndate();
        $grid->column('updated_at', trans('admin.updated_at'))->vndate();

        $grid->model()->orderBy('created_at', 'desc');
        $grid->disableFilter();
        $grid->fixColumns(0, -1);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
//            $actions->disableEdit();
//            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(HistoryLocationModel::findOrFail($id));
        $show->field('user.name', __('Tên người dùng'));
        $show->field('lat', __('Lat'));
        $show->field('long', __('Longtitude'));
        $show->field('location_name', __('Địa điểm'));
        $show->field('datetime', __('Thời gian'))->vndate();
        $show->field('created_at', __('Ngày tạo'))->vndate();
        $show->field('updated_at', __('Ngày cập nhật'))->vndate();

        $show->panel()
            ->tools(function ($tools) {
//                $tools->disableEdit();
//                $tools->disableList();
                $tools->disableDelete();
            });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        //danh sach user
        $userOption = (new UtilsCommonHelper)->listAllUser();
        $userDefault = $userOption->keys()->first();
        $form = new Form(new HistoryLocationModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('history_location');
            $userId = $form->model()->find($id)->getOriginal("user_id");
            $form->select('user_id', __('Tên người dùng'))->options($userOption)->default($userId);
        } else {
            $form->select('user_id', __('Tên người dùng'))->options($userOption)->required();
        }
        $form->number('lat', __('Lat'))->required();
        $form->number('long', __('Longtitude'));
        $form->text('location_name', __('Địa điểm'));
        $form->datetime('datetime', __('Thời gian'));

        return $form;
    }
}