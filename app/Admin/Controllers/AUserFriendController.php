<?php

namespace App\Admin\Controllers;

use App\Models\AlertModel;
use App\Models\UserFriendModel;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AUserFriendController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý danh sách bạn bè';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserFriendModel());
        $grid->column('user.name', __('Tên người dùng'))->filter('like');
        $grid->column('friend.name', __('Tên bạn'))->filter('like');
        $grid->column('nickname', __('NickName'))->filter('like');

        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->vndate();
        $grid->column('updated_at', __('Ngày cập nhật'))->vndate();
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
        $show = new Show(UserFriendModel::findOrFail($id));
        $show->field('user.name', __('Tên người dùng'));
        $show->field('friend.name', __('Tên bạn'));
        $show->field('nickname', __('NickName'));

        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $show->field('created_at', __('Ngày tạo'))->vndate();
        $show->field('updated_at', __('Ngày cập nhật'))->vndate();
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

        $form = new Form(new UserFriendModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('user_friend');
            $userId = $form->model()->find($id)->getOriginal("user_id");

            $listRemainUsers = (new UtilsCommonHelper)->listAllUserWithoutCurrentUser($userId);
            $friendId = $form->model()->find($id)->getOriginal("friend_id");

            $form->select('user_id', __('Tên người dùng'))->options($userOption)->default($userId);
            $form->select('friend_id', __('Tên bạn'))->options($listRemainUsers)->default($friendId);
        } else {
            $form->select('user_id', __('Tên người dùng'))->options($userOption)->required();
            $form->select('friend_id', __('Tên bạn'))->options()->required()->disable();
        }
        $form->text('nickname', __('NickName'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        $urlOtherUser = env('APP_URL') . '/api/user/get-other-user';
//        $urlRemainUser = env('APP_URL') . '/api/user/get-remain-user';

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