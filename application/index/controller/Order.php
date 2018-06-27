<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Order extends Controller
{
    public function index()
    {
    
        
    }   

    public function userQuery()
    {
      
        
        return $this->fetch('order/member_user');
    }
    public function orderDetail()
    {
        $userid = 1;
        //根据用户id查订单表和地址表，查到该用户对应订单下的收货地址，获得地址和该用户下的订单id
        $data = Db::table('shop_order')->join('shop_addr','shop_order.addrid=shop_addr.addrid')
                ->where('shop_order.userid',$userid)->select();
//dump($data);
        $orderlist = [];
       
        foreach ($data as $v) {
           //跟据订单该用户的订单编号查订单详情的到具体购买的商品id和购买数量
            $res = Db::table('shop_order_detail')->where('orderid',$v['orderid'])->select();
           // dump($res);           
            $goods = [];
            $goodslist = [];
            foreach ($res as $val) {
                //跟据该订单下的商品id查询具体的商品,得到商品名价格
                $goods_res = Db::table('shop_goodsinfo')->where('goodsid',$val['goodsid'])->select();
                //dump($goods_res);
                // $orderlist[]=[$goods_res[0]['goods_name']];
                $goods['thumb']=$goods_res[0]['thumb'];
                 $goods['goodsname']=$goods_res[0]['goods_name'];
                  $goods['price']=$goods_res[0]['price'];
                   $goods['goodsnum']=$val['num'];
                    $goods['tal']=$val['num']*$goods_res[0]['price'];
                    $goodslist[] = $goods;
                /*
                 $goods[] = [
                     'thumb'=>$goods_res[0]['thumb'],
                     'goodsname'=>$goods_res[0]['goods_name'],
                     'price'=>$goods_res[0]['price'],
                     'goodsnum'=>$val['num'],
                     'tal'=> $val['num']*$goods_res[0]['price']
                         ];
                 
                 */
                //$goodslist[] = $goods_res[0]['goods_name'];
               //$goodsnum[] = $val['num'];
               //$price[] = $goods_res[0]['price'];
               //$tal[] = $val['num']*$goods_res[0]['price'];             
               //$thumb[] = $goods_res[0]['thumb'];
            }
             //$v[]=['goodslist'=>$goodslist,'goodsnum'=>$goodsnum,'price'=>$price,'tal'=>$tal,'thumb'=>$thumb];
               $v['goods']=$goodslist;
            $orderlist[]=[$v];
            
            
        }
        //echo'<pre>';
        //print_r($orderlist);
        //dump($orderlist);
        return $this->fetch('order/member_order',['orderlist'=>$orderlist ]);
    }

    public function checkOut()
    {
        
    }
    public function address(){
        return $this->fetch('order/member_addr');
    }
    
     public function confirmgoods(Request $request){
         $orderid = ($request->orderid);
         Db::table('shop_order')->where('orderid',$orderid)->update(['status'=>3]);
         return 1;
     }
     //取消订单
     public function delorder(Request $request){
         $orderid = ($request->orderid);
         Db::table('shop_order')->where('orderid',$orderid)->update(['status'=>2]);
         return 1;
     }
     //添加收货地址
     

}
