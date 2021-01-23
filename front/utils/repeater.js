import config from '../config/config.js';
import helper from '../helper/helper.js';

import player from '@/utils/player.js';
import storage from '@/utils/storage.js';

import user from '../model/user.js';
import playlist from '../model/playlist.js';
import song from '../model/song.js';
import search from '../model/search.js';
import other from '../model/other.js';
import album from '../model/album.js';
import personalized from '../model/personalized.js';
import top from '../model/top.js';
import video from '../model/video.js';

export default{ 
	config,
	helper,
	user,
	playlist,
	player,
	storage,
	song,
	search,
	other,
	album,
	personalized,
	top,
	video
}