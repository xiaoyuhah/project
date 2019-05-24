<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class IndexController extends Controller
{
    public function index(){
        $imgs = DB::table('goods')->where('is_new',1)->limit(4)->orderby('goods_id','desc')->select('goods_img')->get();
        $where = [
            ['parent_id','=',0],
            ['is_show','=',1]
        ];
        $topInfo = DB::table('category')->where($where)->select('cate_id','cate_name')->get();
        $goods = DB::table('goods')->where('is_hot',1)->limit(8)->orderby('goods_id','desc')->get();
        $newgoods =  DB::table('goods')->where('is_on_sale',1)->limit(4)->orderby('shop_price')->get();
        return view('index.index',compact('imgs','topInfo','goods','newgoods'));
    }

  
 
}
