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

require_once($CFG->dirroot . '/lib/formslib.php');

class local_trainingrequest_ldmanager_form extends local_trainingrequest_manager_form {

    protected function definition() {
        parent::definition();
        $mform =& $this->_form;
        $mform->freeze(['dmapproveordeny', 'dmreason']);

        $mform->addElement('header', 'ldapprovalheader',
            $this->_customdata['ldmanager']);

        $mform->addElement('select', 'ldapproveordeny',
            get_string('approveordeny', 'local_trainingrequest'), array(1 => 'Approve', 2 => 'Decline'));
        $mform->addRule('ldapproveordeny',
            get_string('required', 'local_trainingrequest'), 'required', null);

        $mform->addElement('textarea', 'ldreason',
            get_string('reason', 'local_trainingrequest'), 'rows="4" cols="105"');
        $mform->setType('ldreason', PARAM_TEXT);
        $mform->hideIf('ldreason', 'ldapproveordeny', 'eq', 1);
    }

}
