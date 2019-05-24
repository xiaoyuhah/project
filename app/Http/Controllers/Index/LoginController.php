<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LoginController extends Controller
{
    //登陆
    public function login(){
        return view('login/login');
    }
    public function logindo(){
        $data = request()->all();
        $email = $data['email'];
        $user = DB::table('user')->where('u_email',$email)->first();
        if(!$user){
            return ['code'=>2,'msg'=>'用户名不存在'];exit;
        }
        if($user->u_pwd!=md5($data['pwd'])){
            return ['code'=>2,'msg'=>'用户名或密码错误'];exit;
        }
        session(['u_id'=>$user->u_id]);
        return ['code'=>1,'msg'=>'登陆成功'];

    }


    //注册
    public function register(){
        return view('login/register');
    }

    public function sendEmail(){
        $email = request()->email;
        $count = DB::table('user')->where('u_email',$email)->count();
        if($count){
            return ['code'=>1,'msg'=>'邮箱已经存在'];
        }

        //发送邮件
        $code = rand(100000,999999);
        $this->send($email,$code);
        session(['email'=>$email,'code'=>$code,'send_time'=>time()]);
    }

    public function sendmail(){
        $email = request()->email;
        $count = DB::table('user')->where('u_email',$email)->count();
        if($count){
            return ['code'=>1,'msg'=>'邮箱已经存在'];
        }
    }

    public function send($email,$code){
        \Mail::raw($code ,function($message)use($email){
        //设置主题
            $message->subject("欢迎注册会员");
        //设置接收方
            $message->to($email);
        });
    } 

    public function regsubmit(){
        $u_email = request()->u_email;
        $u_code = request()->u_code;
        $u_pwd = request()->u_pwd;
        $u_repwd = request()->u_repwd;

        $validatedData = request()->validate([
            'u_email' => 'required|unique:user|max:100',
            'u_code' => 'required',
            'u_pwd' => 'required',
            'u_repwd' => 'required',

        ]);

        $code=session('code');
        if($code!=$u_code){
            return ['code'=>2,'msg'=>'验证码错误'];exit;
        }

        $data['u_email']=$u_email;
        $data['u_code']=$u_code;
        $data['u_pwd'] = md5($u_pwd);
        $data['create_time']=time();
        $res = DB::table('user')->insert($data);
        if($res){
            return ['code'=>1,'msg'=>'注册成功'];
        }else{
            return ['code'=>2,'msg'=>'注册失败'];
        }

    }

    //退出
    public function logout(){
        $res=request()->session()->forget('u_id');
        
        if(!$res){
            return redirect('/');
            // return ['code'=>1,'msg'=>'退出成功'];
        }
    }

    public function test(){
        dump(session('email'));
        dump(session('code'));
        dump(session('send_time'));
        dump(session('u_id'));
    }
}
