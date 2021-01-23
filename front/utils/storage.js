// 缓存统一管理

let Storage = {}

const LATEST_PLAYED = 'latest_played'; //最后一个播放记录
const CURRENT_PLAYLIST = 'current_playlist'; // 当前播放列表
const IS_LOGIN = 'is_login'; // 登录状态
const PLAY_MODE = 'play_mode';

Storage.is_login = function() {
	return get(IS_LOGIN);
}

Storage.set_is_login = function(v) {
	set(IS_LOGIN, v);
	return;
}

Storage.get_played = function() {
	return get(LATEST_PLAYED);
}

Storage.set_played = function(v) {
	set(LATEST_PLAYED, v);
	return;
}
Storage.get_current_playlist = function() {
	return get(CURRENT_PLAYLIST);
}

Storage.set_current_playlist = function(v) {
	set(CURRENT_PLAYLIST, v);
	return;
}

Storage.get_play_mode = function() {
	return get(PLAY_MODE);
}

Storage.set_play_mode = function(v) {
	set(PLAY_MODE, v);
	return;
}

function set(k, v) {
	try {
	    uni.setStorageSync(k, v);
	} catch (e) {
	    // error
	}
	return;
}

function get(k) {
	try {
	    return uni.getStorageSync(k);
	} catch (e) {
	    console.log(e);
	}
}

module.exports = Storage;