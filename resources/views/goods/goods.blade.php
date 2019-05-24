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