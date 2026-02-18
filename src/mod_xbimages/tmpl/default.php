<?php
/*******
 * @package xbMusic
 * @filesource mod_xbimages/tmpl/default.php
 * @version 0.0.4.1 18th February 2026
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/

defined('_JEXEC') or die;

$document = $this->app->getDocument();
$wa = $document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_xbimages');
$wa->useScript('mod_xbimages.new-cover');
$wa->useStyle('xbimages.styles');

// Pass the options down to js
$document->addScriptOptions('mod_xbimages.vars', ['covers' => $covers,'imgdelay' => $imgdelay, 'albuminfo' => $albuminfo, 'imgsource' => $imgsource, 'showyear' => $showyear]);

?>
<?php if($subtitle !='') :?>
	<span class="xbimgsubtitle"><?php echo $subtitle; ?></span><br/>
<?php endif; ?>
<img id="coverimg" src="/media/mod_xbimages/images/WreckersCircleLogo-500x500.png" />
<?php if(($albuminfo == 1) || ($albuminfo == 3)) : ?>
	<span id="albumtitle"></span>
	<?php if($showyear == 1) : ?>
		<span id="relyear"></span>
	<?php endif; ?>
	<?php if ($albuminfo == 3) : ?>
		<br />
	<?php endif; ?>
<?php endif; ?>
<?php if($albuminfo > 1 ) :?>
	<div id="albumartist"></div>
<?php endif; ?>
