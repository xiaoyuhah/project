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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(/index/images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" id="allbox" /> 全选</a></td>
       </tr>
       @foreach($cartInfo as $k=>$v)
       <tr  goods_number="{{$v->goods_number}}" goods_id="{{$v->goods_id}}">
        <td width="4%"><input type="checkbox" class="box"/></td>
        <td class="dingimg" width="15%"><img src="{{config('app.img_url')}}{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <h3>单价：{{$v->shop_price}}</h3>
         
         <time>{{date('Y-m-d',$v->create_time)}}</time>
        </td>
        <td align="left">
            <input  style="float:right;width:30px;" type="button" class="add" value="+"/>
            <input  style="float:right;width:30px;" type="text"  value="{{$v->buy_number}}" class="spinnerExample buy_number" />
            <input  style="float:right;width:30px;" type="button" class="less" value="-"/>
        </td>
       </tr>
       <tr>
        <th colspan="4"><strong class="orange">¥{{$v->shop_price*$v->buy_number}}</strong></th>
        
       </tr>
       @endforeach
      </table>
     </div><!--dingdanlist/-->
     
    
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" id="count">¥0</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan" id="confirm">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    <script>
             //点击加号
             $('.add').click(function(){
                _this = $(this);

                //1、文本框数量+1
                var buy_number=parseInt(_this.next('input').val());
                var goods_number=_this.parents('tr').attr('goods_number');
                var goods_id=_this.parents('tr').attr('goods_id');
                // console.log(goods_number);
                // console.log(goods_id);
                if(buy_number>=goods_number){
                    _this.next('input').val(goods_number);
                }else{
                    buy_number = buy_number+1;
                    _this.next('input').val(buy_number);
                }
                var flag=0;
                $.ajax({
                    type:'post',
                    url:"{{'/cart/changeNumber'}}",
                    data:{buy_number:buy_number,goods_id:goods_id},
                    async:false,
                    success:function(res){
                        if(res.code==2){
                            alert(res.msg);
                            flag=2;
                        }
                    },
                    dataType:'json'
                });
                if(flag==2){
                    return false;
                }
                //2、重新获取小计
                getTotal(goods_id,_this);
                
                //3、当前行的复选框选中
                checkedTr(_this);
                //4、重新获取总价
                getCount();
            });

             //点击减号
             $('.less').click(function(){
                _this=$(this);

                var buy_number = parseInt(_this.prev('input').val());
                var goods_id = _this.parents('tr').attr('goods_id');
                if(buy_number<=1){
                    _this.prev('input').val(1);
                }else{
                    buy_number=buy_number-1;
                    _this.prev('input').val(buy_number);
                }
                var flag=0;
                $.ajax({
                    type:'post',
                    url:"{{'/cart/changeNumber'}}",
                    data:{buy_number:buy_number,goods_id:goods_id},
                    async:false,
                    success:function(res){
                        if(res.code==2){
                            alert(res.msg);
                            flag=2;
                        }
                    },
                    dataType:'json'
                });
                if(flag==1){
                    return false;
                }
                //当前复选框选中
                checkedTr(_this);
                //获取小计
                getTotal(goods_id,_this);
                //重新获取总价
                getCount();
            });
            
             //失去焦点
             $('.buy_number').blur(function(){
                var _this = $(this);
                var buy_number = parseInt(_this.val());
                var goods_id = _this.parents('tr').attr('goods_id');
                var goods_number = _this.parents('tr').attr('goods_number');
                var reg=/^\d+$/;
                if(buy_number<=1||buy_number==''||!reg.test(buy_number)){
                    _this.val(1);
                    buy_number=1;
                }else if(buy_number>goods_number){
                    _this.val(goods_number);
                    buy_number=goods_number;
                }else{
                    _this.val(buy_number);
                }
                //改变数量
                var flag=0;
                $.ajax({
                    type:'post',
                    url:"{{'/cart/changeNumber'}}",
                    data:{buy_number:buy_number,goods_id:goods_id},
                    async:false,
                    success:function(res){
                        if(res.code==2){
                            alert(res.msg);
                            flag=2;
                        }
                    },
                    dataType:'json'
                });
                if(flag==1){
                    return false;
                }
                //当前行复选框选中
                checkedTr(_this);
                //获取小计
                getTotal(goods_id,_this);
                //重新获取总价
                getCount();
            });

            //点击复选框
            $('.box').click(function(){
                //重新获取总价
                getCount();
            })

            //点击全选全不选
            $('#allbox').click(function(){
                var _this = $(this);
                var status = _this.prop('checked');
                $('.box').prop('checked',status);

                //获取商品总价
                getCount();
            });
            
            

            //重新获取小计
            function getTotal(goods_id,_this){
                // alert(111);
                $.post(
                    "{{url('/cart/getTotal')}}",
                    {goods_id:goods_id},
                    function(res){
                        _this.parents('tr').next('tr').text('￥'+res);
                    }
                );
            }
            //当前复选框选中
            function checkedTr(_this){
                _this.parents('tr').find("input[class='box']").prop('checked',true);
            }

            //重新获取总价
            function getCount(){
                var goods_id = "";
                $('.box:checked').each(function(index){
                    goods_id+=$(this).parents('tr').attr('goods_id')+',';
                });
                goods_id=goods_id.substr(0,goods_id.length-1);
                // console.log(goods_id);
                $.post(
                    "{{url('/cart/getCount')}}",
                    {goods_id:goods_id},
                    function(res){
                        $('#count').text("￥"+res);
                        // console.log(res);
                    }
                );
            }

            //点击确认结算
            $('#confirm').click(function(){
                var len = $('.box:checked').length;
                // console.log(len);
                if(!len){
                    alert('请至少选择一件商品进行结算');
                    return false;
                }

                //获取选中的商品id
                var goods_id = '';
                $('.box:checked').each(function(index){
                    goods_id += $(this).parents('tr').attr('goods_id')+',';
                });
                goods_id = goods_id.substr(0,goods_id.length-1);
                // console.log(goods_id);
                location.href="/cart/confirm/"+goods_id;
                
            });
    

    </script>
    @endsection 