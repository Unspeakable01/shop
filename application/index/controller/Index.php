<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Category;
use app\index\controller\Goods;
class Index extends Controller
{
    public $getcategory;
    public $goods;
    public function __construct(\think\App $app = null) {
        parent::__construct($app);
        $this->getcategory = new Category();
        $this->goods=new Goods();
    }
    public function index()
    {
        $category = $this->getcategory->select();
        //dump($category);
        $data = $this->goods->index();
       // dump($data);
       // $categorylist = Db::table('shop_category')->join('shop_goodsinfo','shop_category.categoryid = shop_goodsinfo.categoryid')->select();
        //dump($categorylist);
        $categorylist=[];
        foreach ($category as $v){
           $res = Db::table('shop_goodsinfo')->where('categoryid',$v['categoryid'])->limit(6)->select();
          $categorylist[]=[$v['category_name'],$res];
        }
         //dump($categorylist);
        return $this->fetch('index/index',['category'=>$category,'data'=>$data,'categorylist'=>$categorylist]);
        // return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
        //$this->redirect('Goods/category');
        
    }
   
}
