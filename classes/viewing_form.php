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
 * @package     local_trainingrequest
 * @author      Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright   2020 onwards Catalyst IT Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

class local_trainingrequest_viewing_form extends local_trainingrequest_request_form {

    protected function definition() {
        parent::definition();
        $mform =& $this->_form;
        $mform->freeze(['learnersname', 'dateofrequest', 'jobtitle', 'teamname', 'directmanagername', 'coursename',
            'trainingprovider', 'iscoaching', 'capabilities', 'startdate', 'enddate', 'cost', 'chargeablecostcode1',
            'chargeablecostcode2', 'question1', 'question2', 'question3', 'question4', 'cpdhours']);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
    }

    /**
     * This prevents the save changes button from showing
     */
    protected function after_definition() {
    }

}
