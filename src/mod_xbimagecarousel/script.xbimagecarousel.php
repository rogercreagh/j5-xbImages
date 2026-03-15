<?php
/*******
 * @package xbMusic
 * @filesource mod_xbimagecarousel/script.xbimagecarousel.php
 * @version 0.0.6.1 15th March 2026
 * @copyright Copyright (c) Roger Creagh-Osborne, 2026
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 ******/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\Path;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Version;

return new class () implements InstallerScriptInterface {

    protected $minphp = '8.2';
    protected $jminver = '5.0';
    protected $jmaxver = '7.0';
    protected $extension = 'mod_xbimagecarousel';
    protected $extname = 'xbImageCarousel';
    protected $extslug = 'xbimagecarousel';
    protected $ver = 'v1.2.3.4';
    protected $extdate = '32nd January 2024';
    protected $oldver = 'v1.2.3.4';
    protected $olddate = '32nd January 2024';
    
    
    public function preflight(string $type, InstallerAdapter $adapter): bool
    {        
        if (($type != 'uninstall') && (version_compare(PHP_VERSION, $this->minphp, '<'))) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minphp), 'error');
            return false;
        }

        $jversion = new Version();
        $jverthis = $jversion->getShortVersion();
        if ((version_compare($jverthis, $this->jminver,'lt')) || (version_compare($jverthis, $this->jmaxver, 'ge'))) {
            throw new RuntimeException($this->extname.' requires Joomla version greater than '.$this->jminver. ' and less than '.$this->jmaxver.'. You have '.$jverthis);
            return false;
        }
        // if we are updating then get the old version and date from component xml before it gets overwritten.
        if ($type=='update') {
            $oldmanifest = simplexml_load_file(Path::clean(JPATH_SITE . '/modules/'.$this->extension.'/'.$this->extension.'.xml'));
            $this->oldver = $oldmanifest->version;
            $this->olddate = $oldmanifest->creationDate;
        }
        return true;
    }

    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {

        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        $app = Factory::getApplication();
        $manifest = $adapter->getManifest();
        $ver = $manifest->version;
        $this->extdate = $manifest->creationDate;
        $url = $manifest->changelogurl;
        $ext_mess = '<div style="position: relative; margin: 15px 15px 15px -15px; padding: 1rem; border:solid 1px #444; border-radius: 6px;">';
        if ($type == 'update') {
            $ext_mess .= '<p><b>'.$this->extname.'</b> module has been updated from '.$this->oldver.' of '.$this->olddate;
            $ext_mess .= ' to v<b>'.$ver.'</b> dated '.$manifest->creationDate.'</p>';
            $ext_mess .= $this->showChanglog($ver, $url);
            $ext_mess .= '<p>Check options for existing instances of xbImageCarousel on <a href="index.php?option=com_modules&view=modules&client_id=0">Site Modules</a> page.</p>';
        }
        if (($type=='install') || ($type=='discover_install')) {
            $ext_mess .= '<h3>'.$this->extname.' module installed</h3>';
            $ext_mess .= '<p>version '.$ver.' dated '.$manifest->creationDate.'</p>';
            $ext_mess .= $this->showChanglog($ver, $url);
            $ext_mess .= '<p>Enable module and set options on <a href="index.php?option=com_modules&view=select&client_id=0">Site Modules</a> page.</p>';
        }
        if (($type=='install') || ($type=='discover_install') || ($type == 'update')) {
            $ext_mess .= '<p>For help and information see <a href="https://crosborne.co.uk/'.$this->extslug.'/doc" target="_blank" style="font-weight:bold; color:black;">www.crosborne.co.uk/'.$this->extslug.'/doc</a> ';
            $ext_mess .= '</div>';
            echo $ext_mess;
        }
        return true;
    }

    function showChanglog($ver, $url) {
        $output = '<div style="max-width: 750px; background-color: white; border: 1px solid black; padding:15px 25px; margin:10px auto; font-size:0.9rem;">';
        if (!$this->remoteFileExists($url)) {
            $output .= '<p style="color:red;">Could not find changelog file <code>'.$url.'</code></p></div>';
            return $output;
        }
        $xml = simplexml_load_file($url, null , LIBXML_NOCDATA);
        if ($xml===false) {
            $output .= '<p style="color:red;">Could not parse changelog file <code>'.$url.'</code></p></div>';
            return $output;
        } else {
            $json = json_encode($xml);
            $changelog = json_decode($json,true);
            $log = 0;
            if (array_key_exists('element',$changelog['changelog'])) {
                //only 1 changelog in file
                $log = $changelog['changelog'];
                $newver = $log['version'];
                if (version_compare($newver, $ver) !== 0) {
                    $output.= '<p style="color:red;">Changelog for v'.$ver.' not found. v'.$newver.' is only one available.</p>';
                }
            } else {
                $changelog = $changelog['changelog'];
                //look for current version
                for ($i = 0; $i < count($changelog); $i++) {
                    if (version_compare($changelog[$i]['version'], $ver) === 0) $log = $changelog[$i];
                }
                if ($log === 0 ) {
                    $log = $changelog[0];
                    $output.= '<p style="color:red;">Changelog for v'.$ver.' not found; displaying most recent</p>';
                }
            }
            $output .= '<h4>Changelog for ';
            $output .= $this->extname;
            $output .= ' v'.$log['version'].' ';
            $output .= $this->extdate;
            $output .= '</h4><hr />';
            
            $colours = array('security'=>'bg-danger', 'addition'=>'bg-success', 'fix'=>'bg-dark','language'=>'bg-primary',
                'change'=>'bg-warning text-dark','remove'=>'bg-secondary','note'=>'bg-info'
            );
            $output .= '<table style="margin-left:20px; width:90%;">';
            foreach ($colours as $colkey=>$col) {
                if ((isset($log[$colkey])) && isset($log[$colkey]['item'])) {
                    $output .= '<tr style="border-bottom:1px solid #888;"><td style="background-color:#ddd; vertical-align: top; padding: 5px 10px;">';
                    $output .=  '<span class="badge '.$col.'" style="font-size: 0.8rem;padding: 0.3rem 0.5rem;">'.$colkey.'</span>';
                    $output .= '</td><td style="vertical-align: top; padding: 5px 10px;"><ul>';
                    if (is_array($log[$colkey]['item'])) {
                        foreach ($log[$colkey]['item'] as $item) {
                            $output .= '<li>'.$item.'</li>';
                        }
                    } else {
                        $output .= '<li>'.$log[$colkey]['item'].'</li>';
                    }
                    $output .= '</ul></td></tr>';
                }
            }
            $output .= '</table>';
        }
        $output .= '</div>';
        return $output;
    }
    
    function remoteFileExists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);      // Don't fetch the body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);       // Set timeout
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
    
};