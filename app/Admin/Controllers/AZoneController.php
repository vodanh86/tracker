<?php

namespace App\Admin\Controllers;

use App\Models\AlertModel;
use App\Models\UserFriendModel;
use App\Models\ZoneModel;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AZoneController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý vùng (zones)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ZoneModel());
        $grid->column('user.name', __('Tên người dùng'))->filter('like');
        $grid->column('friend.name', __('Tên bạn'))->filter('like');
        $grid->column('lat', __('Latitude'));
        $grid->column('long', __('Longtitude'));
        $grid->column('alert', __('Cảnh báo'));

        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', trans('admin.created_at'))->vndate();
        $grid->column('updated_at', trans('admin.updated_at'))->vndate();
        $grid->model()->orderBy('created_at', 'desc');
        $grid->disableFilter();
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
        $show = new Show(ZoneModel::findOrFail($id));
        $show->field('user.name', __('Tên người dùng'));
        $show->field('friend.name', __('Tên bạn'));
        $show->field('lat', __('Latitude'));
        $show->field('long', __('Longtitude'));
        $show->field('alert', __('Cảnh báo'));
        $show->field('status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", null);
        });
        $show->field('created_at', __('Ngày tạo'))->vndate();
        $show->field('updated_at', __('Ngày cập nhật'))->vndate();

//        $show->panel()
//            ->tools(function ($tools) {
//                $tools->disableEdit();
//                $tools->disableList();
//                $tools->disableDelete();
//            });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        //danh sach trang thai
        $statusOptions = (new UtilsCommonHelper)->commonCode("Core", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        //danh sach user
        $userOption = (new UtilsCommonHelper)->listAllUser();
        $userDefault = $userOption->keys()->first();

        $form = new Form(new ZoneModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('zone');
            $userId = $form->model()->find($id)->getOriginal("user_id");

            $listRemainUsers = (new UtilsCommonHelper)->listAllUserWithoutCurrentUser($userId);
            $friendId = $form->model()->find($id)->getOriginal("friend_id");

            $form->select('user_id', __('Tên người dùng'))->options($userOption)->default($userId);
            $form->select('friend_id', __('Tên bạn'))->options($listRemainUsers)->default($friendId);
        } else {
            $form->select('user_id', __('Tên người dùng'))->options($userOption)->required();
            $form->select('friend_id', __('Tên bạn'))->options()->required()->disable();
        }
        $form->number('lat', __('Latitude'));
        $form->number('long', __('Longtitude'));
        $form->number('alert', __('Cảnh báo'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        $urlOtherUser = env('APP_URL') . '/api/user/get-other-user';

        $script = <<<EOT
        $(function() {
            var selectedUser = $(".user_id");
            var selectedUserDOM = document.querySelector('.user_id');
            var selectedFriend = $(".friend_id");
            var selectedFriendDOM = document.querySelector('.friend_id');
            var optionsSelectedUser = {};
            var optionsSelectedFriend = {};

            selectedUser.on('change', function() {
                selectedFriend.empty();
                optionsSelectedFriend = {};

                var selectedUserId = $(this).val();
                if(!selectedUserId) return
                console.log('selectedUserId:' +selectedUserId)
                
                $.get("$urlOtherUser", { user_id: selectedUserId }, function (otherUsers) {
                    selectedFriendDOM.removeAttribute('disabled');        
         
                    $.each(otherUsers, function (index, cls) {

                        optionsSelectedFriend[cls.id] = cls.name;
                    });
                    
                    selectedFriend.empty();
                    selectedFriend.append($('<option>', {
                        value: '',
                        text: ''
                    }));
                    $.each(optionsSelectedFriend, function (id, name) {
                        selectedFriend.append($('<option>', {
                            value: id,
                            text: name
                        }));
                    });
                    console.log('selectedFriend:'+selectedFriend);
                    selectedFriend.trigger('change');
                });
            });
        });
        EOT;
        Admin::script($script);

        return $form;
    }
}