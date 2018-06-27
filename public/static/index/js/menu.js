// JavaScript Document

var jq = jQuery.noConflict();
jQuery(function(){
	jq(".leftNav ul li").hover(
		function(){
			jq(this).find(".fj").addClass("nuw");
			jq(this).find(".zj").show();
		}
		,
		function(){
			jq(this).find(".fj").removeClass("nuw");
			jq(this).find(".zj").hide();
		}
	)
})

function confirmgoods(){
   //alert(111);
    if(confirm('是否确认已收货')==true){
        var orderid = jq('#orderid').text();
    jq.get('/index/order/confirmgoods',{orderid:orderid},function(data){
        //alert(data);
        if(data==1){
        window.location.reload();
    }
    });
    }
}
function delorder(){
    if(confirm('是否确定取消订单')==true){
         var orderid = jq('#orderid').text();
         jq.get('/index/order/delorder',{orderid:orderid},function(data){
        //alert(data);
        if(data==1){
        window.location.reload();
    }
    });
    }
    
}