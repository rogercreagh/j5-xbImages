/**
 * @package xbmusic
 * @filesource /media/mod_xbimages/js/new-cover.js
 * @version 0.0.3.2 18th February 2026
 * @desc functions to auto details sections and prevent propogation of clicks
 * @author Roger C-O
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html 
 * 
**/
if (!window.Joomla) {
  throw new Error('Joomla API was not properly initialised');
}

const { covers } = Joomla.getOptions('mod_xbimages.vars');
const { delay } = Joomla.getOptions('mod_xbimages.vars');
const { albuminfo } = Joomla.getOptions('mod_xbimages.vars');
const { showyear } = Joomla.getOptions('mod_xbimages.vars');
const { imgsource } = Joomla.getOptions('mod_xbimages.vars');
var n = covers.length;
const first = covers[Math.floor(Math.random() * n)];
var ffile = first[0];
if (typeof ffile === 'undefined') {
	ffile = '/media/mod_xbimages/images/WreckersCircleLogo-500x500.png'
	document.getElementById('coverimg').src = ffile;
  } else {
	document.getElementById('coverimg').src = ffile;
	setInterval(function() {
		var r = Math.floor(Math.random() * n);
		const cover = covers[r];
	  	var fpath = cover[0];
		    document.getElementById('coverimg').src = fpath;
			if (imgsource == 1) {
			if ((albuminfo==1) || (albuminfo==3)) {
				document.getElementById('albumtitle').innerText = cover[1];
				if (showyear==1)
					document.getElementById('relyear').innerText = "("+cover[3]+")";
			}
			if (albuminfo > 1) {
				document.getElementById('albumartist').innerText = cover[2];
			}
		}
	}, delay*1000);
}