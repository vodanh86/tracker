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
        $grid->column('long', __('Long'));
        $grid->column('location_name', __('Địa điểm'))->filter('like');
        $grid->column('datetime', __('Thời gian'))->filter('range', 'date');

        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });

        $grid->model()->orderBy('created_at', 'desc');
        $grid->disableFilter();
        $grid->actions(function ($actions) {
            $blockDelete = $actions->row->block_delete;
            if ($blockDelete === 1) {
                $actions->disableDelete();
            }
        });
        $grid->fixColumns(0, -1);
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
        $show->field('long', __('Long'));
        $show->field('location_name', __('Địa điểm'));
        $show->field('datetime', __('Thời gian'));

        $show->field('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $show->field('updated_at', __('Ngày cập nhật'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
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
        $form->number('long', __('Long'));
        $form->text('location_name', __('Địa điểm'));
        $form->datetime('datetime', __('Thời gian'));

        return $form;
    }
}