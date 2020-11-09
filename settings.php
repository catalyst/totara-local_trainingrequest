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
 *
 * @package   local_trainingrequest
 * @author    Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright 2020 onwards Catalyst IT Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_trainingrequest', new lang_string('pluginname', 'local_trainingrequest'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configcheckbox('local_trainingrequest/enabled',
        new lang_string('enabled', 'local_trainingrequest'),
        new lang_string('enabled_desc', 'local_trainingrequest'), 1));

    $settings->add(new admin_setting_configtext('local_trainingrequest/chargeablecodes',
        new lang_string('chargeablecodes', 'local_trainingrequest'),
        new lang_string('chargeablecodes_desc', 'local_trainingrequest'), ''));

    $selectoptions = $DB->get_records_select_menu('dp_evidence_type', null, null, 'sortorder', 'id, name');
    $selectoptions[0] = 'No Evidence Type';
    $settings->add(new admin_setting_configselect('local_trainingrequest/evidencetypeid',
        new lang_string('evidencetype', 'local_trainingrequest'),
        new lang_string('evidencetype_desc', 'local_trainingrequest'),
        0,
        $selectoptions));

    $settings->add(new admin_setting_configcheckbox('local_trainingrequest/customfield_datecompleted_enabled',
        new lang_string('customfield_datecompleted_enabled', 'local_trainingrequest'),
        new lang_string('customfield_datecompleted_enabled_desc', 'local_trainingrequest'),
        0));
    $settings->add(new admin_setting_configtext('local_trainingrequest/customfield_datecompleted',
        new lang_string('customfield_datecompleted', 'local_trainingrequest'),
        new lang_string('customfield_datecompleted_desc', 'local_trainingrequest'),
        'evidencedatecompleted'));

    $settings->add(new admin_setting_configcheckbox('local_trainingrequest/customfield_cpdhours_enabled',
        new lang_string('customfield_cpdhours_enabled', 'local_trainingrequest'),
        new lang_string('customfield_cpdhours_enabled_desc', 'local_trainingrequest'),
        0));
    $settings->add(new admin_setting_configtext('local_trainingrequest/customfield_cpdhours',
        new lang_string('customfield_cpdhours', 'local_trainingrequest'),
        new lang_string('customfield_cpdhours_desc', 'local_trainingrequest'),
        'cpdhours'));

    $settings->add(new admin_setting_configcheckbox('local_trainingrequest/costcode_prepopulation_enabled',
        new lang_string('costcode_prepopulation_enabled', 'local_trainingrequest'),
        new lang_string('costcode_prepopulation_enabled_desc', 'local_trainingrequest'),
        false));
    $settings->add(new admin_setting_configtext('local_trainingrequest/costcode_prepopulation_fieldname',
        new lang_string('costcode_prepopulation_fieldname', 'local_trainingrequest'),
        new lang_string('costcode_prepopulation_fieldname_desc', 'local_trainingrequest'),
        'costcode'));
}
