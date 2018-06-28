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
        $data = $this->goods->index();
        $categorylist=[];
        foreach ($category as $v){
           $res = Db::table('shop_goodsinfo')->where('categoryid',$v['categoryid'])->limit(6)->select();
          $categorylist[]=[$v['category_name'],$res];
        }
        return $this->fetch('index/index',['category'=>$category,'data'=>$data,'categorylist'=>$categorylist]);
    }
}
