@extends('layouts.shop')
@section('title','我的微商城')

@section('content')
    
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="prosearch"><input type="text" /></form>
      </div>
     </header>
     <ul class="pro-select" >
      <li class="pro-selCur" type="1"><a href="javascript:;">新品</a></li>
      <li type="2"><a href="javascript:;">库存</a></li>
      <li type="3"><a href="javascript:;">价格</a></li>
     </ul><!--pro-select/-->
     <div class="prolist">
      <input type="hidden" name="cate_id" id="cate_id" value="{{$cate_id}}">
      
      <div id="show">
      @foreach($goodsInfo['goodsInfo'] as $k=>$v)
       <dt><a href="{{url('/goods/detail',['goods_id'=>$v->goods_id])}}"><img src="{{config('app.img_url')}}{{$v->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="{{url('/goods/detail',['goods_id'=>$v->goods_id])}}">{{$v->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v->shop_price}}</strong> <span>¥{{$v->market_price}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>

      @endforeach
      </div>
     </div><!--prolist/-->

       @include('public/footer');
    <script>
       
        $('.pro-select>li').click(function(){
            var cate_id = $('#cate_id').val();
            console.log(cate_id);
            var _this = $(this);
            var type = $(this).attr('type');
            _this.addClass('pro-selCur').siblings('li').removeClass('pro-selCur'); 
            $.post(
                "{{url('/goods/goodstype')}}",
                {type:type,cate_id:cate_id},
                function(res){
                    $('#show').html(res);
                }
            );
        });
        
    </script>
     @endsection 
  