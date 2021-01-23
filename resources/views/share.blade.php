<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="http://res2.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
<button id="share-btn">分享到朋友圈</button>
<script>
/*$.ajax({    
    url:"/api/wechat/signature",
    type: "GET",
    data: {},
    success:function (data) {
    	var res = eval('('+data+')')
    	console.log(res)
    	
    }
});*/
/*var config = {
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: res.appId, // 必填，公众号的唯一标识
            timestamp: res.timestamp, // 必填，生成签名的时间戳
            nonceStr: res.noncestr,// 必填，生成签名的随机串
            signature: res.signature,// 必填，签名
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo'
            ]// 必填，需要使用的JS接口列表
        }*/
wx.config(<?php echo json_encode($config); ?>);
wx.ready(function () {
    // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
    document.querySelector("#share-btn").onclick = function () {
        wx.onMenuShareTimeline({
            title: "分享标题",
            desc: "分享描述",
            link:  "/share.html",
            imgUrl: "",
            success: function(res){
            	console.log(res)
            }/*,
            cancel : function (res) {
            },
            fail : function (res) {
            }*/
        });
      /*          //分享给朋友
        wx.onMenuShareAppMessage({
        	"title": "分享标题",
            "desc": "分享描述",
            "link":  "/share.html",
            "imgUrl": "",

            trigger: function (res) {
            },
            success: function (res) {
            },
            cancel: function (res) {
            },
            fail: function (res) {
            }
        });*/

    };
})
 
</script>