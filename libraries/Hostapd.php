<?php

/**
 * Hostapd class.
 *
 * @category   Apps
 * @package    Wireless
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/wireless/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\wireless;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('wireless');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Configuration_File as Configuration_File;
use \clearos\apps\base\Country as Country;
use \clearos\apps\base\Daemon as Daemon;
use \clearos\apps\base\File as File;

clearos_load_library('base/Configuration_File');
clearos_load_library('base/Country');
clearos_load_library('base/Daemon');
clearos_load_library('base/File');

// Exceptions
//-----------

use \clearos\apps\base\File_Not_Found_Exception as File_Not_Found_Exception;
use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/File_Not_Found_Exception');
clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Hostapd class.
 *
 * @category   Apps
 * @package    Wireless
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/wireless/
 */

class Hostapd extends Daemon
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const FILE_CONFIG = '/etc/hostapd/hostapd.conf';

    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    protected $is_loaded = FALSE;
    protected $config = array();

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Hostapd constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Returns bridge.
     *
     * @return string bridge
     * @throws Engine_Exception
     */

    public function get_bridge()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['bridge'];
    }

    /**
     * Returns channel.
     *
     * @return string channel
     * @throws Engine_Exception
     */

    public function get_channel()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['channel'];
    }

    /**
     * Returns channels.
     *
     * @return array channels
     * @throws Engine_Exception
     */

    public function get_channels()
    {
        clearos_profile(__METHOD__, __LINE__);
    
        // FIXME: just an example - please fix
        // Could we scan the network for other APs and flag some as recommend?
        return array(
            1 => '1',
            2 => '2 - ' . lang('wireless_recommended'),
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
            13 => '13',
            14 => '14',
            15 => '15',
        );
    }

    /**
     * Returns IEEE 802.1X authorization state.
     *
     * @return boolean IEEE 802.1X authorization state.
     * @throws Engine_Exception
     */

    public function get_ieee8021x()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['ieee8021x'];
    }

    /**
     * Returns interface.
     *
     * @return string interface
     * @throws Engine_Exception
     */

    public function get_interface()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['interface'];
    }

    /**
     * Returns wireless mode.
     *
     * Essentially, this returns the wpa_key_mgmt value with a couple 
     * of sanity checks (e.g. state of ieee8021x)
     *
     * @return string wireless mode
     * @throws Engine_Exception
     */

    public function get_mode()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        $ieee8021x = $this->get_ieee8021x();
        $key_management = $this->get_wpa_key_management();

        if (($key_management === 'WPA-EAP') && $ieee8021x)
            $mode = 'WPA-EAP';
        else if (($key_management === 'WPA-PSK') && !$ieee8021x)
            $mode = 'WPA-PSK';
        else
            $mode = 'unknown';
        
        return $mode;
    }

    /**
     * Returns available modes.
     *
     * @return array modes
     * @throws Engine_Exception
     */

    public function get_modes()
    {
        clearos_profile(__METHOD__, __LINE__);

        return array(
            'WPA-EAP' => lang('wireless_wpa_infrastructure'),
            'WPA-PSK' => lang('wireless_wpa_preshared_key')
        );
    }

    /**
    /**
     * Returns SSID.
     *
     * @return string SSID
     * @throws Engine_Exception
     */

    public function get_ssid()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['ssid'];
    }

    /**
     * Returns WPA key management.
     *
     * @return string WPA key management
     * @throws Engine_Exception
     */

    public function get_wpa_key_management()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['wpa_key_mgmt'];
    }

    /**
     * Returns WPA passphrase.
     *
     * @return string WPA passphrase
     * @throws Engine_Exception
     */

    public function get_wpa_passphrase()
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! $this->is_loaded)
            $this->_load_config();

        return $this->config['wpa_passphrase'];
    }

    /**
     * Sets SSID.
     *
     * @param string $ssid SSID
     *
     * @return void
     * @throws Engine_Exception
     */

    public function set_ssid($ssid)
    {
        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_ssid($ssid));

        $this->_set_parameter('ssid', $ssid);
    }

    /**
     * Sets WPA key management.
     *
     * @param string $mode WPA key management mode
     *
     * @return void
     * @throws Engine_Exception
     */

    public function set_wpa_key_management($mode)
    {
        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_wpa_key_management($mode));

        $this->_set_parameter('wpa_key_mgmt', $mode);
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validation routine for SSID.
     *
     * @param string $ssid SSID
     *
     * @return string error message if SSID is invalid
     */

    public function validate_ssid($ssid)
    {
        clearos_profile(__METHOD__, __LINE__);

        // FIXME - what's really valid here?
        if (preg_match("/([:;\/#!@])/", $ssid))
            return lang('wireless_ssid_invalid');
    }

    /**
     * Validation routine for WPA key management mode.
     *
     * @param string $mode WPA key management mode
     *
     * @return string error message if WPA key management is invalid
     */

    public function validate_wpa_key_management($mode)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (!array_key_exists($mode, $this->get_modes()))
            return lang('wireless_mode_invalid');
    }

    ///////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E  M E T H O D S 
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Loads configuration files.
     *
     * @access private
     * @return void
     * @throws Engine_Exception
     */

    protected function _load_config()
    {
        clearos_profile(__METHOD__, __LINE__);

        try {
            $config_file = new Configuration_File(self::FILE_CONFIG);
            $this->config = $config_file->load();
        } catch (File_Not_Found_Exception $e) {
            // Not fatal
        }

        $this->is_loaded = TRUE;
    }

    /**
     * Sets a parameter in the config file.
     *
     * @param string $key   name of the key in the config file
     * @param string $value value for the key
     *
     * @access private
     * @return void
     * @throws Engine_Exception
     */

    protected function _set_parameter($key, $value)
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->is_loaded = FALSE;

        $file = new File(self::FILE_CONFIG);

        if (! $file->exists())
            $file->create("root", "root", "0644"); 

        $match = $file->replace_lines("/^$key\s*=\s*/", "$key = $value\n");

        if (!$match)
            $file->add_lines("$key = $value\n");
    }
}
