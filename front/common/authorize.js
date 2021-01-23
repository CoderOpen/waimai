var baseUrl = "";

// 同步获取storge
const getStorageSync = (key)=>{
	try {
	    const value = uni.getStorageSync(key);
	    if (value) {
	        return value;  
	    }
		return false;
	} catch (e) {
	    console.log('获取storge失败') 
	}
	return false; 
}

// 同步存储storge
const setStorageSync = (key,value)=>{
	try {
	    uni.setStorageSync(key, value);
		return true;
	} catch (e) {
	    console.log('存储storge失败')
	}
	return false;
}

// 检测sessionkey是否过期 1未过期 0已过期
const checkSessionKey=()=>{
	
    return new Promise((resolve,reject) => {
		const user = getStorageSync('user');// 用户缓存信息
		if(user){
			uni.checkSession({
			    success() {
					resolve(user);  //状态未过期
				}
			    ,fail() {
					resolve(false); //状态已过期
				}
			})
		}else{
			resolve(false);  //未存贮
		}
    })
}

// 登录授权
const login = (params)=> {
	return new Promise((resolve,reject) => {
		authDo(params).then(res=>{  
			res = res.data
			if (!res.code) {
				reject('网络错误，请检查一下网络');
				return;
			}
			if (res.code != 1) {
			    reject('登录失败');
				return;
			}
			let user = res.data.user;
			let token = res.data.token;
			uni.setStorageSync('user', user);//储存用户信息到本地
			uni.setStorageSync('token', token);//储存用户信息到本地
			resolve(user);
		})
	})
	
} 

// 保存用户信息 write by self
const authDo = function(params) {
	return new Promise(function (resolve, reject) {
			uni.request({
				url: baseUrl +'/api/chiheyouhui/login',
				data: params,
				method:'POST',
				header: {
	        		'content-type': 'application/x-www-form-urlencoded'
				},
				success: function(res){ resolve(res) },
				fail:function(){ reject("保存用户信息失败") }  
			})
	})
}

// 获取服务商信息
const getProvider = (service) => {
    return new Promise((resolve, reject) => {
		if(!service){ service = 'oauth' }
        uni.getProvider({
            service: service, //服务类型  登录授权
            success: function(res) {resolve(res.provider[0])},
            fail:function() { reject("获取服务商失败") }
        });
    })
} 

// 是否开启了获取用户名授权
const getSetting = function() {
    return new Promise((resolve,reject) => {
        uni.getSetting({
            success:function(res) {
                let authSetting=res.authSetting
                if(authSetting['scope.userInfo']){resolve(1);return;}//授权成功
                if(authSetting['scope.userInfo']===false){resolve(0);return;}//拒绝授权
                resolve(2) //2未操作
            },
            fail:function() { reject("获取用户授权失败") }
        })
    })
}

// 获取code
const getCode = provider => {
    return new Promise((resolve, reject) => {
		if (!provider) { reject("获取缺少provider参数") }
        uni.login({
            provider: provider,
            success: function(loginRes) {
                if (loginRes && loginRes.code) { resolve(loginRes.code) } else { reject("获取code失败") }
            },
			fail:function(){ reject("获取code失败")}
        });
    })
}

// 获取用户信息
const getUserInfo = (provider)=>{
	return new Promise( (resolve,reject)=>{
		if (!provider) { reject("获取缺少provider参数");return; }
		uni.getUserInfo({
			provider: provider,
			success: (detail) => {
				if(detail.iv != ''){
					resolve(detail);
				}else{
					reject(0);
				}
			}
			,fail: (error) => {
				console.log(error)
				reject(0);
			}
		});
	})
}


export default {getStorageSync,setStorageSync,getProvider,getSetting,checkSessionKey,getCode,login,getUserInfo}
