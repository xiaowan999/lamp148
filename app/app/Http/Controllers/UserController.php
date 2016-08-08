<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller {

    //后台用户添加
    public function getAdd() {
        //解析用户添加模板
        return view('user.add');
    }

    /**
     * 处理表单提交添加数据
     */
    public function postInsert(Request $request) {
//查看所有的提交的数据
//        dd($request->all());
//手动率检测用户名不能为空
//         if(!$request->input('username')){
//             //直接返回
//             return back()->with('error','用户名不能为空');
//             //with向session中闪存错误信息 $_SESSION['error] = '......';
//         }
//Laravel中内置的表单验证  自动闪存
        $this->validate($request, [
            'username' => 'required|regex:/\w{6,18}/',
//                   'username' => "required|regex:['\w{6,18}']",
            'password' => 'required',
            'repassword' => 'required|same:password',
            'email' => 'required|email',
            'phone' => 'required|regex:/1[3,8]\d{9}/',
                ], [
            'username.required' => '用户名不能为空', 'username.regex' => '用户名必须是6到18位字母数字下划线',
            'password.required' => '密码不能为空',
            'repassword.required' => '确认密码不能为空',
            'repassword.same' => '您的两次密码不一致',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确'
        ]);

//        //提取其他数据
        $data = $request->only([ 'username', 'password', 'phone', 'email']);
//查询处理图片上传
        $data['pic'] = $this->upload($request);
        //随机token
        $data['token'] = str_random(50);
        //状态为1 未激活
        $data['status'] = 1;
        //密码加密
        $data['password'] = Hash::make($data['password']);

//执行数据插入
        $res = DB::table('users')->insert($data);
        //判断
        if ($res) {
            return redirect('/admin/user/index')->with('success', '添加成功');
        } else {
            return back()->with('error', '数据添加失败');
        }
    }

    /**
     * 用户列表
     * 
     */
    public function getIndex(Request $request) {

//查询所有数据
//        $users = DB::table('users')->get();
//获取分页大小
        $num = $request->input('num', 10);
//查询数据库 使用分页
//         $users = DB::table('users')->where('username','like','%'.$request->input('keywords').'%')->paginate($num);
//         if($request->input('keywords')){
//             $users = DB::table('users')->where('username','like','%'.$request->input('keywords').'%')->paginate($num);
//         }else{
//             $users = DB::table('users')->paginate($num);
//         }
        //在laravel 中的高级where 条件
        $users = DB::table('users')->where(function($query) use($request) {
                    if ($request->input('keywords')) {
                        $query->where('username', 'like', '%' . $request->input('keywords') . '%');
                    };
                })->paginate($num);

//        dd($users);
        //解析模板
        return view('user.index', ['users' => $users, 'request' => $request->all()]);
    }

    /**
     * 修改
     */
    public function getEdit(Request $request) {

        $id = $request->input('id');
//通过id查询
        $user = DB::table('users')->where('id', $id)->first();
//                dd($user);
        //解析模板 分配变量
        return view('user.edit', ['user' => $user]);
    }

    /*
     * 执行数据修改
     */

    public function postUpdate(Request $request) {
//        dd($request->all());
        $data = $request->except(['_token', 'id']);
        $id = $request->input('id');
        $res = DB::table('users')->where('id', $id)->update($data);
        //判断
        if ($res) {
            return redirect('/admin/user/index')->with('success', '数据修改成功');
        } else {
            return back()->with('error', '数据修改失败');
        }
    }

    /**
     * ajax删除用户
     */
    public function postAjaxdelete(Request $request) {
        $id = $request->input('id');
        //执行数据删除
        $res = DB::table('users')->where('id', $id)->delete();
        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 图片上传操作
     */
    public function upload(Request $request) {
        // dd($request->all());
        if ($request->hasFile('pic')) {
            //文件名称
            $name = md5(time() + rand(111111, 8999999));
            //文件后缀名获取
            $suffix = $request->file('pic')->getClientOriginalExtension();
            $arr = [ 'jpg', 'png'];
            //判断文件上传类型

            if (!in_array($suffix, $arr)) {
                echo '上传文件不符合要求';
                die;
            }
            //将指定文件移动到指定位置
            $request->file('pic')->move('./uploads/', $name . '.' . $suffix);
            return '/uploads/' . $name . '.' . $suffix;
        }
    }

}
