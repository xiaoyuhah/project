@extends('layouts.shop')
@section('title','我的微商城')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form class="reg-login" onsubmit="return false">
      <div class="lrBox">
       <div class="lrList"><input type="text" placeholder="收货人" id="address_name"/></div>
       <div class="lrList"><input type="text" placeholder="详细地址" id="address_detail"/></div>
       <div class="lrList">
        <select class="changearea" id="province">
        <option value="">省份</option>
        @foreach($provinceInfo as $k=>$v)
         <option value="{{$v->id}}">{{$v->name}}</option>
        @endforeach
        </select>
       </div>
       <div class="lrList" >
        <select class="changearea" id="city">
         <option>区县</option>
        </select>
       </div>
       <div class="lrList">
        <select class="changearea" id="area">
         <option>详细地址</option>
        </select>
       </div>
       <div class="lrList"><input type="text" placeholder="手机" id="address_tel"/></div>
       <div class="lrList2"><input type="text" placeholder="设为默认地址" /> <button id="is_default" value="2">设为默认</button></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" id="submit" value="保存" />
      </div>
     </form><!--reg-login/-->
     
     @include('public/footer');
     <script>
        $('.changearea').change(function(){
            var _this=$(this);
            _this.parent('div').nextAll("div").find('select').html("<option>请选择</option>");            
            var id = _this.val();
            // console.log(id);
            $.post(
                "{{url('address/getArea')}}",
                {id:id},
                function(res){
                    var _option="<option>请选择</option>"
                    for(var i=0;i<res.length;i++){
                        _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                    }
                    _this.parent('div').next("div").find('select').html(_option);
                },
                'json'
            );
        });

        //点击设为默认
        $('#is_default').click(function(){
            var _this=$(this);
            _this.attr('value',1);
        });


        $('#submit').click(function(){
            var data = {};
            data.is_default = $('#is_default').attr('value');
            data.address_name = $('#address_name').val();
            data.address_detail = $('#address_detail').val();
            data.address_tel = $('#address_tel').val();
            data.province = $('#province').val();
            data.city = $('#city').val();
            data.area = $('#area').val();
            if(data.address_name==''){
                alert('收货人名称不能为空');
                return false;
            }
            $.post(
                "{{url('address/addressadd')}}",
                data,
                function(res){
                    if(res.code==1){
                        alert(res.msg);
                    }
                },
                'json'
            );
        });
     </script>
@endsection