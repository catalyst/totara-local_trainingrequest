<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_trainingrequest
 * @author    Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright 2020 onwards Catalyst IT Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_trainingrequest_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020090800) {
        // Rename chargeable cost code into two fields.
        $table = new xmldb_table('trainingrequests');
        $costcode1field = new xmldb_field('chargeablecostcode', XMLDB_TYPE_CHAR, 255, null);
        if ($dbman->field_exists($table, $costcode1field)) {
            $dbman->rename_field($table, $costcode1field, 'chargeablecostcode1');
        }

        $costcode2field = new xmldb_field('chargeablecostcode2', XMLDB_TYPE_CHAR, 255, null);
        if (!$dbman->field_exists($table, $costcode2field)) {
            $dbman->add_field($table, $costcode2field);
        }

        upgrade_plugin_savepoint(true, 2020090800, 'local', 'trainingrequest');
    }

    if ($oldversion < 2020092200) {
        upgrade_plugin_savepoint(true, 2020092200, 'local', 'trainingrequest');
    }

    if ($oldversion < 2020102200) {
        // Remove team cost code, payment method, cardholders name
        $table = new xmldb_table('trainingrequests');
        $dbman->drop_field($table, new xmldb_field('teamcostcode', XMLDB_TYPE_CHAR, 255, null));
        $dbman->drop_field($table, new xmldb_field('paymentmethod', XMLDB_TYPE_NUMBER, 1, null));
        $dbman->drop_field($table, new xmldb_field('cardholdersname', XMLDB_TYPE_CHAR, 255, null));
        upgrade_plugin_savepoint(true, 2020102200, 'local', 'trainingrequest');
    }
}