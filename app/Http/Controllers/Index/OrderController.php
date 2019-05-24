<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class OrderController extends Controller
{
    //提交订单
    public function confirmOrder(){
        $goods_id = request()->goods_id;
        $goods_id = explode(',',$goods_id);
        $address_id = request()->address_id;
        $pay_type = request()->pay_type;
        $u_id = session('u_id');
        //开启事务
        DB::beginTransaction();
        try{
            if(empty($goods_id)){
                throw new \Exception('商品id不能为空');
            }
            if(empty($address_id)){
                throw new \Exception('收货地址不能为空');
            }
            if(empty($pay_type)){
                throw new \Exception('支付方式不能为空');
            }
            //获取订单号
            $order_no = time().rand(100,999).$u_id;
            // dd($order);
            //获取总价
            $where = [
                ['u_id','=',$u_id],
                ['is_del','=',1]
            ];
            // dd($where);
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
            //给订单表添加数据
            $order['order_no'] = $order_no;
            $order['order_acount']= $count;
            $order['u_id']=$u_id;
            $order['pay_type']=$pay_type;
            $order['create_time']=time();
            $order_id = DB::table('order')->insertGetId($order);
            if(!$order_id){
                throw new \Exception('订单地址写入失败');
            }

            //给订单地址表添加数据
            $addressWhere = [
                ['u_id','=',$u_id],
                ['is_del','=',1],
                ['address_id','=',$address_id]
            ];
            $addressInfo = DB::table('address')->where($addressWhere)->first();
            $addressInfo->order_id = $order_id;
            // dd($addressInfo);
            $addressInfo=get_object_vars($addressInfo);
            // dd($addressInfo);
            unset($addressInfo['address_id']);
            unset($addressInfo['is_default']);
            $res2 = DB::table('order_address')->insert($addressInfo);
            // dd($res2);
            if(!$res2){
                throw new \Exception('订单地址写入失败');
            }
            
            //订单商品详情表添加数据
            $goodWhere = [
                ['u_id','=',$u_id],
                ['is_del','=',1],
            ];
            $goodsInfo = DB::table('goods')
                        ->join('cart','goods.goods_id','=','cart.goods_id')
                        ->where($goodWhere)
                        ->whereIn('goods.goods_id',$goods_id)
                        ->select('goods.goods_id','goods_img','buy_number','goods_name')
                        ->get();
            // dd($goodsInfo);
            // $goodsInfo = get_object_vars($goodsInfo);
            // dd($goodsInfo);
            $goodsInfo = json_decode(json_encode($goodsInfo),true);
            // dd($goodsInfo);
            foreach($goodsInfo as $k=>$v){
                $goodsInfo[$k]['order_id']=$order_id;
                $goodsInfo[$k]['u_id']=$u_id;
            }
            // dd($goodsInfo);
            $res3 = DB::table('order_detail')->insert($goodsInfo);
            // dd($res3);
            if(!$res3){
                throw new \Exception('订单详情写入失败');
            }

            $goodsInfo = DB::table('goods')
                        ->join('cart','goods.goods_id','=','cart.goods_id')
                        ->where($goodWhere)
                        ->whereIn('goods.goods_id',$goods_id)
                        ->select('goods.goods_id','buy_number','goods_number')
                        ->get();
            $goodsInfo = json_decode(json_encode($goodsInfo),true);
            // dd($goodsInfo);
            //修改库存
            // foreach($goodsInfo as $k=>$v){
            //     $goods_number = $v['goods_number']-$v['buy_number'];
            //     $res4 = DB::table('goods')->where('goods_id',$v['goods_id'])->update(['goods_number'=>$goods_number]);
            //     if($res4){
            //         throw new \Exception('修改库存失败');
            //     }
            // }
            // dd($goods_id);
            foreach($goodsInfo as $k=>$v){
                foreach($goods_id as $key=>$val){
                    if($v['goods_id']==$val){
                        $v['goods_number'] = $v['goods_number']-$v['buy_number'];
                        $res4 = DB::table('goods')->where('goods_id',$val)->update(['goods_number'=>$v['goods_number']]);
                    }
                }
            }
            if(!$res4){
                throw new \Exception('修改库存成功');
            }

            //清除购物车数据
            $cartWhere = [
                ['u_id','=',$u_id]
            ];
            $res5 = DB::table('cart')->where($cartWhere)->whereIn('goods_id',$goods_id)->update(['is_del'=>2]);
            // $res5 = false;
            if(!$res5){
                throw new \Exception('清除购物车数据失败');
            }
            //提交
            DB::commit();
            return [
                'code'=>1,
                'msg'=>'下单成功',
                'order_id'=>$order_id
            ];

        }catch(EXception $e){
            DB::rollBack();
            return [
                'code'=>2,
                'msg'=>'下单失败'
            ];
            report($e);
            return false;
        }
    }

    //订单成功页面
    public function success($id){
        // echo $id;
        $orderInfo = DB::table('order')->where('order_id',$id)->first();
        // dd($orderInfo);
        return view('/order/success',['orderInfo'=>$orderInfo]);
    }

     // 测试支付宝
     public function alipay($id)
     {
         $orderInfo = DB::table('order')->where('order_id',$id)->first();
        //  dd($orderInfo);
         if(empty($orderInfo)){
             echo '订单信息写入失败';
         }
        $config = config('pay');
        // dd(app_path('libs\alipay\pagepay\service/AlipayTradeService.php'));
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        require_once app_path('libs/alipay/pagepay/buildermodel/    AlipayTradePagePayContentBuilder.php');
         
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $orderInfo->order_no;

            //订单名称，必填
            $subject = '雨恬';

            // 通过订单号查询订单金额

            //付款金额，必填
            $total_amount = $orderInfo->order_acount;

            //商品描述，可空
            $body = '';

            //构造参数
            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);

            $aop = new \AlipayTradeService($config);

            /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
            */
            $response = $aop->pagePay($payRequestBuilder,config('pay.return_url'),config('notify_url'));

            //输出表单
            var_dump($response);
            }

    public function returnUrl()
    {
        $config = config('pay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');


        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        // dd($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        //商户订单号
        $where['order_no'] = htmlspecialchars($_GET['out_trade_no']);
        $where['order_acount'] = htmlspecialchars($_GET['trade_no']);
            
        $count = DB::table('order')->where($where)->count();
        //支付宝交易号
        $trade_no = htmlspecialchars($_GET['trade_no']);
        
        if($count){
            echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题";exit;
        }

        if(config('pay.seller_id')!=htmlspecialchars($_GET['seller_id'])){
            echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,商户id不匹配";exit;

        }
        if(config('pay.app_id')!=htmlspecialchars($_GET['app_id'])){
            echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,appid不匹配";exit;

        }
        echo "验证成功<br />支付宝交易号：".$trade_no;

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
        //验证失败
        echo "验证失败";
        }
    }

    // 电脑
    public function malipay($id)
    {
        $orderInfo = DB::table('order')->where('order_id',$id)->first();
        //  dd($orderInfo);
            if(empty($orderInfo)){
                echo '订单信息写入失败';
            }
        $config = config('pay');
        // dd($config);
        require_once app_path('libs/malipay/alipay.trade.wap.pay-PHP-UTF-8/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        require_once app_path('libs/malipay/alipay.trade.wap.pay-PHP-UTF-8/wappay/service/AlipayTradeService.php');
        // if (!empty($_POST['WIDout_trade_no'])&& trim($_POST['WIDout_trade_no'])!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $orderInfo->order_no;

            //订单名称，必填
            $subject = '雨恬';

            //付款金额，必填
            $total_amount = $orderInfo->order_acount;

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;
        // }
    }

    public function nofitipay()
    {
        $config = config('pay');
        dd($config);
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_POST;
        
        // dd($arr);
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            //请在这里加上商户的业务逻辑程序代

            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号
        $where['order_no'] = htmlspecialchars($_POST['out_trade_no']);
        $where['order_acount'] = htmlspecialchars($_POST['total_amount']);
            
        $count = DB::table('order')->where($where)->count();
        //支付宝交易号
        $trade_no = htmlspecialchars($_POST['trade_no']);
        
        Log::info("支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题");

        Log::info("支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,商户id不匹配");

        Log::info("支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,appid不匹配");

        // if($count){
        //     echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题";exit;
        // }

        // if(config('pay.seller_id')!=htmlspecialchars($_POST['seller_id'])){
        //     echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,商户id不匹配";exit;

        // }
        // if(config('pay.app_id')!=htmlspecialchars($_POST['app_id'])){
        //     echo "支付宝交易号：".$trade_no."订单号：".$where['order_no']."订单金额：".$where['total_amount']."此订单有问题,appid不匹配";exit;

        // }
        echo "验证成功<br />支付宝交易号：".$trade_no;

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序			
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                // 修改订单状态、支付状态、库存状态等业务逻辑
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
}

?>