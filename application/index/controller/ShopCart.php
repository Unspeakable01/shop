<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Shopcart extends Controller

{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }   


    //订单确认
    public function orderSure(Request $request){
        //从session中取$userid
        $userid = 1;

        //获取用户id
        $addrid = $request->addrid;

        //定义一个orderid和total变量，在匿名函数外面需要调用
        $orderid = 0;
        $total = 0;

        //执行事务
        Db::transaction(function() use($userid,$addrid,&$orderid,&$total){
            //计算商品总价
            $total = Db::table('shop_cart')
            ->alias('c')
            ->join('shop_goodsinfo g','c.goodsid = g.goodsid')
            ->where('c.userid ='.$userid)
            ->field('sum(c.num*g.price) as total')
            ->select();
    
            //待插入的数据，先插入order表,再插入order_detail表
            $data_o  = ['total_price'=>$total[0]['total'],'status'=>0,'addrid'=>$addrid,'userid'=>$userid];
            // $data_od  = ;
            //插入数据并获得自增后的id
            $orderid = Db::name('order')->insertGetId($data_o);

            //获取购物车中的商品
            Db::execute("insert into shop_order_detail(`num`,`goodsid`,`orderid`) select `num`,`goodsid`,$orderid from shop_cart where userid=$userid");

            //删除已提交订单的购物车商品记录
            Db::name('cart')
            ->where('userid',$userid)
            ->delete();
        });

        //如果$orderid表示表插入成功且返回最新自增id
        if($orderid>0 && $total>0){
            return $this->fetch('buycar_three',['orderid'=>$orderid,'total'=>$total]);
        }else{
            return $this->error('订单生成失败');
        }
       
    } 
    //清空购物车
    public function clearShopCart()
    {
        //从session中获取userid
        $userid = 2;
        $result = Db::name('cart')
        ->where('userid',$userid)
        ->delete();
        //返回删除的行数
        return $result;

    }

    //根据商品id从购物车移除商品
    public function removeById()
    {

    }

    //修改商品数量
    public function modifyNumber()
    {

        //从session中获取userid
        $this->userid = 1;
        
        $cartid = $_GET['cartid'];
        $result = Db::name('cart')
        ->where('cartid',$cartid)
        ->delete();

        if($result){
            //查询商品总价
            $total = Db::table('shop_cart')
            ->alias('c')
            ->join('shop_goodsinfo g','c.goodsid = g.goodsid')
            ->where('c.userid ='.$userid)
            ->field('sum(c.num*g.price) as total')
            ->select();

            $result = json(['total'=>$total[0]['total']]);
        }
        
        return $result;

    }

    //添加商品到购物车
    public function addToCart()
    {
        //接受用户id
        $userid = 1;
        //接受购物车的cartid
        $cartid = $_GET['cartid'];
        //获取加入购物车的商品数量
        $num = $_GET['num'];

        // return $cartid.$num;

        //检查cartid是否在购物车表中存在
        $result = Db::name('cart')
        ->where('cartid',$cartid)
        ->update(['num'=>$num]);

        //查询商品价格
        $price = Db::name('cart')
        ->alias('c')
        ->join('goodsinfo g','c.goodsid=g.goodsid')
        ->where('c.cartid',$cartid)
        ->field('g.price')
        ->find();

        //计算用户购物总价
        $total = Db::table('shop_cart')
        ->alias('c')
        ->join('shop_goodsinfo g','c.goodsid = g.goodsid')
        ->where('c.userid ='.$userid)
        ->field('sum(c.num*g.price) as total')
        ->select();

        if($result)
        {
            // return true;
            return json(['num'=>$num,'cartid'=>$cartid,'price'=>$price['price'],'total'=>$total[0]['total']]);
        }else{
            return false;
        }

    }

    //显示购物车内容
    public function showCart()
    {

        //接受购物车的cartid
        $cartid = $_GET['cartid'];
        //获取商品id
        $goodsid = $_GET['goodsid'];
        //获取加入购物车的商品数量
        $num = $_GET['num'];
        //获取用户id
        $userid = 1;

        //查询shop_cart表中是否已存在商品
        $result = Db::name('cart')
        ->where('cartid',$cartid)
        ->find();

        //如果存在则数量num加1，不存在则执行插入一条记录
        if($result)
        {
            $r = Db::name('cart')
            ->where('cartid',$cartid)
            ->setInc('num',$num);
        }else{
            $data = ['num'=>$num,'goodsid'=>$goodsid,'userid'=>$userid];

            $r = Db::name('cart')->insert($data);
        }
        //$r 获取执行插入或更新的结果
        if($r==1){
            return true;
        }else{
            return false;
        }

    }

    //统计商品数量进入订单确认页面
    public function account()
    {


        // return 111;
        //return $this->fetch('buycar');

        //获取当前用户id
        $userid = 1;

        //根据用户id查询shop_cart和shop_goodsinfo表
        $data = Db::table('shop_cart')
        ->alias('c')
        ->join('shop_goodsinfo g','c.goodsid = g.goodsid')
        ->where('c.userid ='.$userid)
        ->field('cartid,thumb,goods_name,price,num,price*num subtotal')
        ->select();

        //计算用户购物总价
        $total = Db::table('shop_cart')
        ->alias('c')
        ->join('shop_goodsinfo g','c.goodsid = g.goodsid')
        ->where('c.userid ='.$userid)
        ->field('sum(c.num*g.price) as total')
        ->select();

        return $this->fetch('buycar',['data'=>$data,'total'=>$total[0]['total']]);
    }
}
