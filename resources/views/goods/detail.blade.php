@extends('layouts.shop')
@section('title','我的微商城')

@section('content')
    
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">

      <img src="{{config('app.img_url')}}{{$data->goods_img}}" />
      
     </div><!--sliderA/-->
     <table class="jia-len">
     <input type="hidden" id="goods_number" value="{{$data->goods_number}}">
     <input type="hidden" id="goods_id" value="{{$data->goods_id}}">
      <tr>
       <th><strong class="orange">￥{{$data->shop_price}}</strong></th>
       <td>
        <button id="less">-</button><input type="text" class="spinnerExample" id="buy_number" value="1"/><button id="add">+</button>
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$data->goods_name}}</strong>
        <p class="hui">{{$data->description}}</p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>
     <div class="height2"></div>
    
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="{{config('app.img_url')}}{{$data->goods_img}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><a href="javascript:;" id="addCart">加入购物车</a></td>
      </tr>
     </table>
    <script>
            //点击加号
            $('#add').click(function(){
                var _this = $(this);
                var buy_number = parseInt($('#buy_number').val());
                var goods_number = $('#goods_number').val();
                if(buy_number>=goods_number){
                    $('#buy_number').val(goods_number);
                    //加号失效
                    _this.prop('disabled',true)
                }else{
                    // parseInt(buy_number)+=1;
                    buy_number = parseInt(buy_number)+1;
                    $('#buy_number').val(buy_number);
                    //减号生效
                    $('#less').prop('disabled',false);
                }
            });

            //点击减号
            $('#less').click(function(){
                var _this = $(this);
                var buy_number = $('#buy_number').val();
                var goods_number = $('#goods_number').val();
                if(buy_number<=1){
                    $('#buy_number').val(1);
                    //减号失效
                    _this.prop('disabled',true)
                }else{
                    buy_number = parseInt(buy_number)-1;
                    $('#buy_number').val(buy_number);
                    //加号生效
                    $('#add').prop('disabled',false);
                }
            });

            //失去焦点
            $('#buy_number').blur(function(){
                _this=$(this);
                var buy_number = parseInt($('#buy_number').val());                
                var goods_number = $('#goods_number').val();
                var reg = /^\d+$/;
                if(buy_number==''||buy_number<=1||!reg.test(buy_number)){
                    $('#buy_number').val(1);
                }else if(buy_number>=goods_number){
                    $('#buy_number').val(goods_number);
                }else{
                    $('#buy_number').val(parseInt(buy_number));
                }
            });

            //点击加入购物车
            $('#addCart').click(function(){
                var goods_id = $('#goods_id').val();
                var buy_number = $('#buy_number').val();
                // console.log(goods_id,buy_number);
                $.post(
                    "/cart/addcart",
                    {goods_id:goods_id,buy_number:buy_number},
                    function(res){
                        if(res.code==2){
                            alert(res.msg);
                            location.href="{{url('/')}}";
                        }else if(res.code==1){
                            alert(res.msg);
                            location.href="{{url('cart/cartlist')}}";
                        }
                    },
                    'json'
                );
            });

    </script>

     @endsection 