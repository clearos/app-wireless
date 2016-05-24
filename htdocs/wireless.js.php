<?php

/**
 * Javascript helper for Wireless.
 *
 * @category   apps
 * @package    wireless
 * @subpackage javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2015 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearcenter.com/support/documentation/clearos/wireless/
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

clearos_load_language('wireless');

header('Content-Type: application/x-javascript');

?>

var lang_passphrase = '<?php echo lang('wireless_passphrase'); ?>';
var lang_radius_secret = '<?php echo lang('wireless_radius_secret'); ?>';

$(document).ready(function() {
    $('#mode').on('change', function(e) {
        if ($('#mode').val() == 'WPA-PSK')
            $('#passphrase_label').html(lang_passphrase);  
        else
            $('#passphrase_label').html(lang_radius_secret);  
    });
});

// vim: ts=4 syntax=javascript
