<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Category;
use think\request;
use app\index\model\Goodsinfo;
class Goods extends Controller
{  
    //首页显示商品
    public function index()
    { 
       return $data = Db::table('shop_goodsinfo')->order('goodsid')->select();
        // return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
        // $this->assign('data',$data);
    }   

    //显示商品详情
    public function showDetail(Request $request)
    {
        $goodsid = $request->param('goodsid');
        //echo $goodsid;
        $goodsinfo = Db::table('shop_goodsinfo')->where('goodsid',$goodsid)->select();
        //dump($goodsinfo);
         $category = Db::table('shop_category')->select();
        return $this->fetch('goods/product',['goodsinfo'=>$goodsinfo,'category'=>$category]);
    }

    //根据关键词查询商品
    public function searchGoods(Request $request)
    {  $search = $request->param('search');
       //echo $search;
       $categorylist = Db::table('shop_goodsinfo')->where('goods_name','like',"%$search%")->select();
        //dump($categorylist);
         $category = Db::table('shop_category')->select();//查询数组分类
        return $this->fetch('goods/categorylist_1',['categorylist'=>$categorylist,'category'=>$category]);
    }

    //分类页面详情
    public function categoryList(Request $request)
    {
       $categoryid = $request->param('categoryid');
      // echo $categoryid;
       $categorylist = Db::table('shop_goodsinfo')->where('categoryid',$categoryid)->paginate(1);
       //dump($categorylist);
       $category = Db::table('shop_category')->select();//查询数组分类
       
        //print_r($category);
       // return $this->fetch('index/index',['category'=>$category]);//返回到首页，并将分类传给首页
        return $this->fetch('goods/categorylist',['category'=>$category,'categorylist'=>$categorylist]);
       
      // return $this->fetch('goods/categorylist');
      
    }
     //显示导航栏分类   
    public function category()
    {
        
    }

}
