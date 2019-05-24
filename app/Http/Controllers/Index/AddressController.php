<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AddressController extends Controller
{
    // public function __construct(){
    //     if(!request()->session()->get('u_id')){
    //        redirect('/');
    //     }
    // }
    public function add(){

        //查询所有的省份信息
        $provinceInfo = $this->getAreaInfo(0);
        return view('/address/add',['provinceInfo'=>$provinceInfo]);
    }

    public function getAreaInfo($pid){
        $where=[
            ['pid','=',$pid]
        ];
        $areaInfo = DB::table('area')->where($where)->get();
        return $areaInfo;
    }

    //获取省份
    public function getArea(){
        $id = request()->id;
        $info = $this->getAreaInfo($id);
        echo json_encode($info);
    }

    public function addressadd(){
        $data = request()->all();
        // dd($data);
        $data['u_id'] = session('u_id');
        //入库
        if($data['is_default']==1){
            $where = [
                ['u_id','=',$data['u_id']],
                ['is_del','=',1]
            ];
            DB::table('address')->where($where)->update(['is_default'=>2]);
        }
        $res = DB::table('address')->insert($data);
        if($res){
            return ['code'=>1,'msg'=>'添加收货地址成功'];
        }else{
            return ['code'=>2,'msg'=>'添加失败'];
        }
    }
}
