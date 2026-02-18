<?php
/*******
 * @package xbMusic
 * @filesource mod_xbimages/services/provider.php
 * @version 0.0.3.2 18th February 2026
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/

namespace Crosborne\Module\Xbimages\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class XbimagesHelper
{
    public static function getLoggedonUsername(string $default)
    {
        $user = Factory::getApplication()->getIdentity();
        if ($user->id !== 0)  // found a logged-on user
        {
            return $user->username;
        }
        else
        {
            return $default;
        }
    }
    
    public static function getFilesByExtension($directory, $extarr) {
        $files = [];
        if (!is_array($extarr)) $extarr = [strtolower($extarr)];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
        
        foreach ($iterator as $file) {
            $typeok = in_array(strtolower($file->getExtension()),$extarr);
            if ($file->isFile() && $typeok) {
                //we don't have title or artist so return empty fields for them
                $files[] = array(str_replace(JPATH_ROOT, '', $file->getPathname()),'t','a');
            }
        }
        
        return $files;
    }
    
    public static function getFilesFromXbmusic($albumtags) {
        $db = Factory::getContainer()->get(DatabaseInterface::class);
//        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('DISTINCT a.imgurl, a.title, a.sortartist, a.rel_date');
        $query->from('#__xbmusic_albums AS a');
        //add album tag filter by any selected tag
        $tagfilt = $albumtags;
        $subquery = '(SELECT tmap.tag_id AS tlist FROM #__contentitem_tag_map AS tmap
                    WHERE tmap.type_alias = '.$db->quote('com_xbmusic.album').'
                    AND tmap.content_item_id = a.id)';
        if (count($tagfilt) == 1) {
            $query->where($tagfilt[0].' IN '.$subquery);
        } else {
            $tagIds = implode(',', $tagfilt);
            if ($tagIds) {
                $subQueryAny = '(SELECT DISTINCT content_item_id AS cid FROM #__contentitem_tag_map
                                    WHERE tag_id IN ('.$tagIds.') AND type_alias = '.$db->quote('com_xbmusic.album').')';
                $query->innerJoin('(' . (string) $subQueryAny . ') AS tm ON tm.cid = a.id');
            }
        } //end else
        
        $db->setQuery($query);
        return $db->loadRowList() ;
        
    }
    
}

