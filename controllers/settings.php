<?php

/**
 * Hostapd settings controller.
 *
 * @category   apps
 * @package    wireless
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2016 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/wireless/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Hostapd settings controller.
 *
 * @category   apps
 * @package    wireless
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2016 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/wireless/
 */

class Settings extends ClearOS_Controller
{
    /**
     * Hostapd settings controller
     *
     * @return view
     */

    function index()
    {
        $this->_common('view');
    }

    /**
     * Edit view.
     *
     * @return view
     */

    function edit()
    {
        $this->_common('edit');
    }

    /**
     * View view.
     *
     * @return view
     */

    function view()
    {
        $this->_common('view');
    }

    /**
     * Common view/edit handler.
     *
     * @param string $form_type form type
     *
     * @return view
     */

    function _common($form_type)
    {
        // Load dependencies
        //------------------

        $this->lang->load('wireless');
        $this->load->library('wireless/Hostapd');

        // Set validation rules
        //---------------------
         
        $this->form_validation->set_policy('ssid', 'wireless/Hostapd', 'validate_ssid');
        $this->form_validation->set_policy('passphrase', 'wireless/Hostapd', 'validate_wpa_passphrase');
        $this->form_validation->set_policy('mode', 'wireless/Hostapd', 'validate_wpa_key_management');
        $this->form_validation->set_policy('channel', 'wireless/Hostapd', 'validate_channel');
        $this->form_validation->set_policy('interface', 'wireless/Hostapd', 'validate_interface');
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $this->hostapd->set_ssid($this->input->post('ssid'));
		$this->hostapd->set_wpa_passphrase($this->input->post('passphrase'));
		$this->hostapd->set_channel($this->input->post('channel'));
		$this->hostapd->set_interface($this->input->post('interface'));
		$this->hostapd->set_mode($this->input->post('mode'));
                $this->hostapd->reset(TRUE);

                $this->page->set_status_updated();
                redirect('/wireless/settings');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data['form_type'] = $form_type;
            $data['ssid'] = $this->hostapd->get_ssid();
            $data['mode'] = $this->hostapd->get_mode();
            $data['modes'] = $this->hostapd->get_modes();
            $data['channel'] = $this->hostapd->get_channel();
            $data['channels'] = $this->hostapd->get_channels();
            $data['interface'] = $this->hostapd->get_interface();
            $data['interfaces'] = $this->hostapd->get_interfaces();
            $data['passphrase'] = $this->hostapd->get_wpa_passphrase();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('wireless/settings', $data, lang('base_settings'));
    }
}
