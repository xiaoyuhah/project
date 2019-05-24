@extends('layouts.shop')
@section('title','我的微商城')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
      @if(!$info)
        <tr onClick="window.location.href='/address/add'">
            <td class="dingimg" width="75%" colspan="2">新增收货地址</td>
            <td align="right"><img src="/index/images/jian-new.png" /></td>
        </tr>
      @else
       @foreach($info as $k=>$v)
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr address_id="{{$v->address_id}}" is_default="1">
        <td><input type="radio" name="dan" class="radio"></td>
        <td class="dingimg" width="20%" >收货人姓名：{{$v->address_name}}</td>
        <td class="dingimg" width="20%" >收货人电话：{{$v->address_tel}}</td>
        <td class="dingimg" width="40%" >收货人地址：{{$v->province->name}}{{$v->city->name}}{{$v->area->name}}</td>
        <td align="right"><img src="/index/images/jian-new.png" /></td>
        <td class="default"><button>设为默认</button></td>
       </tr>
       @endforeach
     @endif
        
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right"><span class="hui" value="1" id="pay">网上支付</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
      
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
       
      @foreach($cartInfo as $k=>$v)
       <tr class="goods_id" goods_id={{$v->goods_id}}>
        <td class="dingimg" width="15%"><img src="{{config('app.img_url')}}{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：{{date('Y-m-d',$v->create_time)}}</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->shop_price*$v->buy_number}}</strong></th>
       </tr>
       @endforeach
       
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$count}}</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥0.00</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$count}}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan" id="order">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    <script>
        //点击提交订单
        $('#order').click(function(){
            //获取当前选中的收货地址id
            var address_id = $('.radio:checked').parents('tr').attr('address_id');
            if(!address_id){
                alert('请务必选择一个收货地址');
                return false;
            }
            var pay = $('#pay').attr('value');
            var goods_id = '';
            $('.goods_id').each(function(index){
                goods_id +=$(this).attr('goods_id')+',';
            });
            goods_id = goods_id.substr(0,goods_id.length-1);
            $.post(
                "{{url('order/confirmOrder')}}",
                {address_id:address_id,pay_type:pay,goods_id:goods_id},
                function(res){
                    alert(res.msg);
                    location.href="/order/success/"+res.order_id;
                },
                'json'
            );
        });

        // //设为默认
        // $('.default').click(function(){
        //     var default = $('.default').parents('tr').attr('is_default');
        //     console.log(default);
        // });
    </script>
   @endsection