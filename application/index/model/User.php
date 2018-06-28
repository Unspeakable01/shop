<?php

namespace app\index\model;
use think\Model;

class User extends Model//自动继承了//根据用户名查询密码
{  
   
public function get_password_by_username($username) {
    //根据用户名查询密码 
        
    return $this->where('username', $username)->field('password,userid')->find();
        
    //$this指的是当前类指向的对象（实例化）
}
public function insert_to_shopuser($user,$paswd,$phone) {  
    $data = ['username' => $user, 'password' => $paswd, 'phone' => $phone];
    //插入并返回插入自增的id
    return $this->insertGetId($data);
    //return User::table;
} 

    public function update_by_username($user,$newpaswd) { 
    //根据名字修改密码(先查后改)
    $shopuser=User::where('username',$user)->find();//只能用静态
    $shopuser->password = $newpaswd;    
    $shopuser->save();
    
    }
}
