@extends('layouts.shop')
@section('title','注册')

@section('content')
   
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form action="" method="" class="reg-login" onsubmit="return false">
     @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
        </ul>
        </div>
        @endif   
      <h3>已经有账号了？点此<a class="orange" href="/login/login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" id="email" placeholder="输入手机号码或者邮箱号" /><span id="email_msg" style="color:red" ></span></div>
       <div class="lrList2"><input type="text" id="email_code" placeholder="输入短信验证码" /> <button id="sendEmail">获取验证码</button><span id="code_msg" style="color:red"></span></div>
       <div class="lrList"><input type="text" id="email_pwd" placeholder="设置新密码（6-18位数字或字母）" /><span id="pwd_msg" style="color:red"></span></div>
       <div class="lrList"><input type="text" id="email_repwd" placeholder="再次输入密码" /><span id="repwd_msg" style="color:red"></span></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" id="submit" value="立即注册" />
      </div>
     </form><!--reg-login/-->
    @include('public/footer')
    <script>
    $(function(){
            //点击获取验证码
            $('#sendEmail').click(function(){
                //获取邮箱
                var email = $('#email').val();
                var reg = /^\w+@\w+\.com$/;
                var flag = 0;
                if(email==''){
                    $('#email_msg').html('邮箱不能为空');
                    return false;
                }else if(!reg.test(email)){
                    $('#email_msg').html('邮箱格式不正确');
                    return false;
                }else{
                   $.ajax({
                       url:'sendEmail',
                       type:'post',
                       data:{email,email},
                       async:false,
                       success:function(res){
                           if(res.code==1){
                               flag = 1;
                               $('#email_msg').html('邮箱已存在');
                           }
                       }
                   });
                   if(flag==1){
                       return false;
                   }
                }
            });

            //失去焦点验证码
            $('#email_code').blur(function(){
                var email_code = $('#email_code').val();
                // console.log(email_code);
                if(email_code==''){
                    $('#code_msg').html('验证码不能为空');
                    return false;
                }
            });

            //验证密码
            $('#email_pwd').blur(function(){
                var email_pwd = $('#email_pwd').val();
                var reg = /^[a-z0-9]{6,18}$/;
                if(email_pwd==''){
                    $('#pwd_msg').html('密码不能为空');
                    return false;
                }else if(!reg.test(email_pwd)){
                    $('#pwd_msg').html('密码必须为6-18位数字或字母');
                    return false;
                }
            });

            //验证重复密码
            $('#email_repwd').blur(function(){
                var email_pwd = $('#email_pwd').val();
                var email_repwd = $('#email_repwd').val();
                if(email_pwd!=email_repwd){
                    $('#repwd_msg').html('重复密码和密码必须一致');
                }
            });

            //点击注册
            $('#submit').click(function(){
                //获取邮箱
                var email = $('#email').val();
                console.log(email);
                var reg = /^\w+@\w+\.com$/;
                var flag = 0;
                if(email==''){
                    $('#email_msg').html('邮箱不能为空');
                    return false;
                }else if(!reg.test(email)){
                    $('#email_msg').html('邮箱格式不正确');
                    return false;
                }else{
                   $.ajax({
                       url:'sendmail',
                       type:'post',
                       data:{email,email},
                       async:false,
                       success:function(res){
                           if(res.code==1){
                               flag = 1;
                               $('#email_msg').html('邮箱已存在');
                           }
                       }
                   });
                   if(flag==1){
                       return false;
                   }
                }
                
                //验证验证码
                var email_code = $('#email_code').val();
                if(email_code==''){
                    $('#code_msg').html('验证码不能为空');
                    return false;
                }

                //验证密码
                var email_pwd = $('#email_pwd').val();
                var reg = /^[a-z0-9]{6,18}$/;
                if(email_pwd==''){
                    $('#pwd_msg').html('密码不能为空');
                    return false;
                }else if(!reg.test(email_pwd)){
                    $('#pwd_msg').html('密码必须为6-18位数字或字母');
                    return false;
                }

                //验证重复密码
                var email_pwd = $('#email_pwd').val();
                var email_repwd = $('#email_repwd').val();
                if(email_pwd!=email_repwd){
                    $('#repwd_msg').html('重复密码和密码必须一致');
                    return false;
                }

                var data = {};
                data.u_email = email;
                data.u_pwd = email_pwd;
                data.u_code = email_code;
                data.u_repwd = email_repwd;
                // console.log(data);

                $.post(
                    "regsubmit",
                    data,
                    function(res){
                        if(res.code==1){
                            alert(res.msg);
                            location.href="{{url('login/login')}}";
                        }else if(res.code==2){
                            alert(res.msg);
                        }
                    },
                    'json'
                );

            });



        })
    </script>

@endsection

