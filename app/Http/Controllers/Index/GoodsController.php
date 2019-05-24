<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GoodsController extends Controller
{
    public function list(){
        $cate_id = request()->id;
        $goodsInfo = $this->getFloorInfo($cate_id);
        // dd($cate_id);
        // dd($goodsInfo);
        return view('/goods/list',compact('goodsInfo','cate_id'));
    }

    
    public function getFloorInfo($cate_id,$type=''){
        $info=[];
        //1、获取顶级楼层数据
        $where = [
            ['cate_id','=',$cate_id],
            ['is_show','=',1]
        ];
        $info['topInfo']=DB::table('category')->where($where)->select('cate_id','cate_name')->first();
        //2、获取顶级分类下的二级分类
        $where1 = [
            ['parent_id','=',$cate_id],
            ['is_show','=',1]
        ];
        $info['secondInfo']=DB::table('category')->where($where1)->select('cate_id','cate_name')->get();
        //3、获取商品数据
        $cateInfo = DB::table('category')->where('is_show',1)->get();
        $c_id = $this->getCateId($cateInfo,$cate_id);
        // dd($c_id);
        if($type==''){
            $where2 = [
                ['is_on_sale','=',1],
                ['is_new','=',1]
            ];  
            $info['goodsInfo']=DB::table('goods')->where($where2)->whereIn('cate_id',$c_id)->get();
        }else if($type==2){
            $where2 = [
                ['is_on_sale','=',1]
            ];
            $info['goodsInfo']=DB::table('goods')->where($where2)->whereIn('cate_id',$c_id)->orderby('goods_number')->get();
            
        }else if($type=3){
            $where2 = [
                ['is_on_sale','=',1]
            ];
            $info['goodsInfo']=DB::table('goods')->where($where2)->whereIn('cate_id',$c_id)->orderby('shop_price')->get();
        }
        
        return $info;
    }

    public function getCateId($cateInfo,$parent_id){
        static $id=[];
        foreach($cateInfo as $k=>$v){
            if($v->parent_id==$parent_id){
                $id[]=$v->cate_id;
                $this->getCateId($cateInfo,$v->cate_id);
            }
        }
        return $id;
    }

    //新品库存查询
    public function goodstype(){
        $type = request()->type;
        $cate_id = request()->cate_id;
        $goodsInfo = $this->getFloorInfo($cate_id,$type);
        // dd($goodsInfo);
        return view('/goods/goods',['goodsInfo'=>$goodsInfo]);  
    }

    //商品详情
    public function detail($id){
        $data = DB::table('goods')->where('goods_id',$id)->first();
        return view('/goods/detail',['data'=>$data]);
    }

}
