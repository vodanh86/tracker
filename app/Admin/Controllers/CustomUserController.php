<?php

namespace App\Admin\Controllers;

use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Encore\Admin\Controllers\UserController;

class CustomUserController extends UserController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.administrator');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminUser());

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', trans('admin.username'))->filter('like');
        $grid->column('name', trans('admin.name'))->filter('like');
        $grid->column('email', trans('admin.email'))->filter('like');
        $grid->column('device_id', trans('Device id'))->filter('like');
        $grid->column('share_code', trans('Share code'))->filter('like');
        $grid->column('language', trans('Language'))->using(Constant::CUSTOMER_TYPE)->filter('like');
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });
        $grid->filter(function($filter){
            $filter->where(function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });   
            }, 'Role');
        });
        

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AdminUser::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('admin.username'));
        $show->field('name', trans('admin.name'));
        $show->field('roles', trans('admin.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
        $show->field('permissions', trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new AdminUser());

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->text('email', trans('admin.email'));
        $form->text('device_id', trans('Device id'));
        $form->text('share_code', trans('Share code'));
        $form->select('language', trans('Language'))->options(Constant::CUSTOMER_TYPE);
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);
        //$form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}