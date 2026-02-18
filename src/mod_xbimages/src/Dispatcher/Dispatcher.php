<?php
/*******
 * @package xbMusic
 * @filesource mod_xbimages/services/provider.php
 * @version 0.0.3.2 18th February 2026
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/

namespace Crosborne\Module\Xbimages\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\Dispatcher as JoomlaDispatcher;
//use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
//use Crosborne\Module\Xbimages\Site\Helper\XbimagesHelper;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Factory;

//class Dispatcher implements DispatcherInterface
class Dispatcher extends JoomlaDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;
    protected $module;
    
    protected $app;
    
    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input)
    {
        parent::__construct($app, $input);
        $this->module = $module;
        $this->app = $app;
    }
    
    public function dispatch()
    {
        
        // The default Joomla Factory classes set the Database object within the Helper class,
        // but not within the Dispatcher class, and we need the dbo for passing to the Table
        $helper = $this->getHelperFactory()->getHelper('XbimagesHelper');
//        $helper->doBasicTableOperations($this->module->id, $this->input);
//        $helper->doAdvancedTableOperations($this->module->id, $this->input);
        
        $params = new Registry($this->module->params);
        $imgdelay = $params->get('img_delay', 7 );
        $albuminfo = $params->get('albuminfo',0);
        $showyear = $params->get('showyear',0);
        $subtitle = $params->get('subtitle','');
        $imgsource = $params->get('img_source', 0 );
        if ($imgsource == 0) {
            $img_folder = $params->get('img_folder', '' );
            $img_exts = $params->get('img_exts', 'jpg' );
            $img_exts = explode( ',', $img_exts);
            $covers = $helper->getFilesByExtension(JPATH_ROOT.'/images/'.$img_folder, $img_exts);
        } elseif ($imgsource == 1) {
            //check if xbmusic installed
            $albumtags = $params->get('albumtags', [] );
            $covers = $helper->getFilesFromXbmusic($albumtags);
        }
        
        
        require ModuleHelper::getLayoutPath('mod_xbimages');
   }
}