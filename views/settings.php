<?php

/**
 * Wireless settings view.
 *
 * @category   apps
 * @package    wireless
 * @subpackage views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('wireless');

///////////////////////////////////////////////////////////////////////////////
// Form handler
///////////////////////////////////////////////////////////////////////////////

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array(
        form_submit_update('submit'),
        anchor_cancel('/app/wireless/settings'),
    );
} else {
    $read_only = TRUE;
    $buttons = array(
        anchor_edit('/app/wireless/settings/edit')
    );
}

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

//echo form_open('wireless/settings/edit', array('autocomplete' => 'off'));
echo form_open('wireless/settings/edit');
echo form_header(lang('base_settings'));

echo field_dropdown('mode', $modes, $mode, lang('wireless_mode'), $read_only);
echo field_input('ssid', $ssid, lang('wireless_ssid'), $read_only);
if ($mode === 'WPA-EAP') {
    echo field_password('passphrase', $passphrase, lang('wireless_radius_secret'), $read_only);
} else {
    echo field_password('passphrase', $passphrase, lang('wireless_passphrase'), $read_only);
}
echo field_simple_dropdown('interface', $interfaces, $interface, lang('wireless_interface'), $read_only);
echo field_simple_dropdown('channel', $channels, $channel, lang('wireless_channel'), $read_only);
// echo field_dropdown('bridge', $bridges, $bridge, lang('wireless_bridge'), $read_only);

// FIXME
echo field_button_set($buttons);

echo form_footer();
echo form_close();
