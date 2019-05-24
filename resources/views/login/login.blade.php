@extends('layouts.shop')
@section('title','登陆')

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
     <form class="reg-login" onsubmit="return false">
      <h3>还没有三级分销账号？点此<a class="orange" href="reg.html">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号" id="email"/></div>
       <div class="lrList"><input type="password" placeholder="输入密码" id="pwd"/></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" id="btn" value="立即登录" />
      </div>
     </form><!--reg-login/-->
    @include('public/footer');

    <script>
        $(function(){
            $('#btn').click(function(){
                var email = $('#email').val();
                var pwd = $('#pwd').val();
                $.post(
                    "logindo",
                    {email:email,pwd:pwd},
                    function(res){
                        if(res.code==1){
                            alert(res.msg);
                            location.href="{{url('/user/index')}}";
                        }else if(res.code==2){
                            alert(res.msg);
                        }
                    },
                    'json'
                );
            });
        });
    </script>


    @endsection   
    
</html>
