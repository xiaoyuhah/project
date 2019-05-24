<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CartController extends Controller
{
    //加入购物车
    public function addcart(){
        $goods_id = request()->goods_id;
        $buy_number = request()->buy_number;
        if(!request()->session()->get('u_id')){
            return ['code'=>2,'msg'=>'请登陆后在加入购物车'];
        }
        //加入购物车
        $u_id = session('u_id');
        $cartwhere = [
            ['goods_id','=',$goods_id],
            ['u_id','=',$u_id],
            ['is_del','=',1]
        ];
        $cartInfo = DB::table('cart')->where($cartwhere)->first();
        if(!empty($cartInfo)){
            //检测库存 累加
            $buy_number = $cartInfo->buy_number;
            $res=$this->checkGoodsNumber($buy_number,$goods_id,$buy_number);
            if(!$res){
                return ['code'=>2,'msg'=>'库存不足'];
            }
            $where = [
                ['goods_id','=',$goods_id],
            ];
            $result = DB::table('cart')->where($where)->update(['buy_number'=>$buy_number+$cartInfo->buy_number]);
        }else{
            //检测库存 添加
            $res=$this->checkGoodsNumber($buy_number,$goods_id);
            if(!$res){
                return ['code'=>2,'msg'=>'库存不足'];
            }
            $info = [
                'goods_id'=>$goods_id,
                'buy_number'=>$buy_number,
                'u_id'=>$u_id,
            ];
            $res = DB::table('cart')->insert($info);
        }
        if($res){
            return ['code'=>1,'msg'=>'加入购物车成功'];
        }else{
            return ['code'=>2,'msg'=>'加入购物车失败'];
        }
    }

    //检测库存
    public function checkGoodsNumber($buy_number,$goods_id,$already_num=0){
        $num = $buy_number+$already_num;
        $goods_number=DB::table('goods')->where('goods_id',$goods_id)->select('goods_number')->first();
        // dd($goods_number);
        if($num>$goods_number->goods_number){
            return false;
        }else{
            return true;
        }
    }

    //购物车列表
    public function cartlist(){
        if(!request()->session()->get('u_id')){
            return ['code'=>2,'msg'=>'请登陆后在加入购物车'];
        }
        //从数据库中取出信息
        $u_id = session('u_id');
        $where = [
            ['u_id','=',$u_id],
            ['is_del','=',1]
        ];
        $cartInfo = DB::table('cart')
                    ->join('goods','cart.goods_id','=','goods.goods_id')
                    ->where($where)
                    ->select('cart.goods_id','shop_price','goods_img','buy_number','goods_name','goods_number','create_time')
                    ->get();
                    //dd($cartInfo);
        //$cartInfo->create_time=date('Ymd',$cartInfo->create_time);
        $count = DB::table('cart')
                    ->join('goods','cart.goods_id','=','goods.goods_id')
                    ->where($where)
                    ->select('cart.goods_id','shop_price','goods_img','buy_number','goods_name','goods_number','create_time')
                    ->count();
        return view('/cart/cartlist',['cartInfo'=>$cartInfo,'count'=>$count]);
    }

    //改变数据库购买数量
    public function changeNumber(){
        $goods_id = request()->goods_id;
        $buy_number = request()->buy_number;
        $u_id = session('u_id');
        $res = $this->checkGoodsNumber($buy_number,$goods_id);
        if(!$res){
            return ['code'=>2,'msg'=>'库存不足'];
        }        
        $where = [
            ['u_id','=',$u_id],
            ['goods_id','=',$goods_id],
            ['is_del','=',1]
        ];
      //  DB::connection()->enableQueryLog();  
        $res = DB::table('cart')->where($where)->update(['buy_number'=>$buy_number]);
        // dd($res);
        //    $logs = DB::getQueryLog(); //执行
      //  dd($logs);
        if($res){
            return ['code'=>1,'msg'=>''];
        }else{
            return ['code'=>2,'msg'=>'修改失败'];
        }
    }

    //小计
    public function getTotal(){
        $goods_id = request()->goods_id;
        if(empty($goods_id)){
            echo 0;exit;
        }
        $goodsWhere=[
            ['goods_id','=',$goods_id],
            ['is_on_sale','=',1]
        ];
        $shop_price=DB::table('goods')->where($goodsWhere)->select('shop_price')->first();
        //购买数量
        $u_id = session('u_id');
        $cartWhere=[
            ['goods_id','=',$goods_id],
            ['u_id','=',$u_id]
        ]; 
        $buy_number=DB::table('cart')->where($cartWhere)->select('buy_number')->first();
        echo $shop_price->shop_price*$buy_number->buy_number;
    }

    //重新获取总价
    public function getCount(){
       $goods_id = request()->goods_id;
       $goods_id = explode(',',$goods_id);
        //    dd($goods_id);
       $u_id = session('u_id');
       $cartWhere = [
            ['u_id','=',$u_id],
            ['is_del','=',1],
        ];
        $info = DB::table('cart')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where($cartWhere)
            ->whereIn('goods.goods_id',$goods_id)
            ->select('shop_price','buy_number')
            ->get();
        // dd($info);
        $count = 0;
        foreach($info as $k=>$v){
            $count+=$v->shop_price*$v->buy_number;
        }
        echo $count;
    }

    //提交订单
    public function confirm($id){
        $goods_id = explode(',',$id);
        $u_id = session('u_id');
        $where = [
            ['u_id','=',$u_id],
            ['is_del','=',1]
        ];
        $cartInfo = DB::table('cart')
                    ->join('goods','cart.goods_id','=','goods.goods_id')
                    ->where($where)
                    ->whereIn('goods.goods_id',$goods_id)
                    ->select('cart.goods_id','shop_price','goods_img','buy_number','goods_name','goods_number','create_time')
                    ->get();
        // dd($cartInfo);
        //总价
        $count = 0;
        foreach($cartInfo as $k=>$v){
            $count += $v->buy_number*$v->shop_price;
        }
        // dd($count);

        //查询收货地址
        $addressWhere = [
            ['u_id','=',$u_id],
            ['is_del','=',1],
        ];
        $info = DB::table('address')->where($addressWhere)->get();
        foreach($info as $k=>$v){
            $info[$k]->province = DB::table('area')->where('id',$v->province)->select('name')->first();
            $info[$k]->city = DB::table('area')->where('id',$v->city)->select('name')->first();
            $info[$k]->area = DB::table('area')->where('id',$v->area)->select('name')->first();
        }
        // dd($info);
        return view('/cart/confirm',['cartInfo'=>$cartInfo,'count'=>$count,'info'=>$info]);
    }

    


}
