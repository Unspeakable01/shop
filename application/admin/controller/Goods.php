<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\Db;
//商品信息类

class Goods extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('index/goods_manager');
    }

    //显示添加页面
    public function toAdd()
    {
        return $this->fetch('index/add');
    }

    //添加商品
    public function addGoods()
    {

    }

    //通过id删除和下架商品
    public function deleteGoodsById()
    {
        $id=Request::param('id');
        $data= Db::table('shop_goodsinfo')->where('goodsid',$id)->delete();     
       //echo $data;
        if($data==0){
            return 1;
        }
    }

    //打开商品修改页面
    public function toModifyGoods()
    {
        return $this->fetch('index/edit');
    }

    //修改商品信息
    public function modifyGoods()
    {

    }

    //删除成功提示
    public function tips()
    {
        return $this->fetch('index/tips');
    }

    
    



    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
