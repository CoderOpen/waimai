function get(url, params, success_cb, failed_cb) {
	
	helper.toast('loading', '加载中...', 10000)
	
	let uri = config.HOST + url;

	uni.request({
		url: uri, 
		data: params,
		method:"GET",
		success: (res)=>{
			
			success_cb && success_cb(res);
		},
		fail:(res) => {
			failed_cb && failed_cb(res);
		    console.log('request_fail:url:' + url + ' ; ' + 'errMsg:' + res.errMsg);
		},
		complete: ()=> {
			uni.hideToast();
		}
	});
} 

function post(url, params, success_cb, failed_cb) {
	
	helper.toast('loading', '加载中...', 10000)
	
	uni.request({
		url: config.HOST + url, 
		data: params,
		method:"POST",   
		success: (res)=>{
			success_cb && success_cb(res);
		},
		fail:(res) => {
		    console.log('request_fail:url:' + url + ' ; ' + 'errMsg:' + res.errMsg);
			failed_cb && failed_cb(res);
		},
		complete: ()=> {
			uni.hideToast();
		}
	})   
}

function test() {
	//111.13.100.92
	uni.request({
	    url: 'https://www.baidu.com/', //仅为示例，并非真实接口地址。
	    success: (res) => {
	        console.log(res.data);
	    }
	});
}


import config from '../config/config.js';
import helper from '../helper/helper.js';

export default{
	get,
	post,
	test
}