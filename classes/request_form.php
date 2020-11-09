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

class local_trainingrequest_request_form extends moodleform {

    /**
     * Training request form
     */
    protected function definition() {
        $mform =& $this->_form;

        $mform->addElement('text', 'learnersname', get_string('name', 'local_trainingrequest'),
            array('class' => 'trainingrequest-learnersname side-by-side'));
        $mform->setType('learnersname', PARAM_TEXT);
        $mform->addRule('learnersname', get_string('required', 'local_trainingrequest'),
            'required', null);
        $mform->freeze(['learnersname']);

        $mform->addElement('date_selector', 'dateofrequest',
            get_string('dateofrequest', 'local_trainingrequest'),
            null,
            array('class' => 'trainingrequest-dateofrequest side-by-side'));
        $mform->setType('dateofrequest', PARAM_TEXT);
        $mform->freeze(['dateofrequest']);

        $mform->addElement('text', 'jobtitle', get_string('jobtitle', 'local_trainingrequest'));
        $mform->setType('jobtitle', PARAM_TEXT);
        $mform->addRule('jobtitle', get_string('required', 'local_trainingrequest'), 'required', null);

        $mform->addElement('text', 'teamname', get_string('team', 'local_trainingrequest'));
        $mform->setType('teamname', PARAM_TEXT);
        $mform->addRule('teamname', get_string('required', 'local_trainingrequest'), 'required', null);

        $mform->addElement('text', 'directmanagername', get_string('managername', 'local_trainingrequest'));
        $mform->setType('directmanagername', PARAM_TEXT);
        $mform->addElement('hidden', 'directmanagerid');
        $mform->setType('directmanagerid', PARAM_INT);

        $mform->addElement('text', 'coursename', get_string('coursename', 'local_trainingrequest'));
        $mform->setType('coursename', PARAM_TEXT);
        $mform->addRule('coursename', get_string('required', 'local_trainingrequest'), 'required', null);

        $mform->addElement('text', 'trainingprovider', get_string('trainingprovider', 'local_trainingrequest'));
        $mform->setType('trainingprovider', PARAM_TEXT);
        $mform->addRule('trainingprovider', get_string('required', 'local_trainingrequest'), 'required', null);

        $mform->addElement('select', 'iscoaching', get_string('iscoaching', 'local_trainingrequest'), ['No', 'Yes']);

        $competencies = $this->get_competencies();
        $capabilities = array();
        $capabilities[] =& $mform->createElement('select', 'capability1',
            get_string('capability', 'local_trainingrequest'), $competencies);
        $capabilities[] =& $mform->createElement('select', 'capability2',
            get_string('capability', 'local_trainingrequest'), $competencies);
        $mform->addGroup($capabilities, 'capabilities', get_string('capabilities', 'local_trainingrequest'));

        $mform->addElement('date_time_selector', 'startdate', get_string('startdate', 'local_trainingrequest'));
        $mform->addElement('date_time_selector', 'enddate', get_string('enddate', 'local_trainingrequest'));

        $mform->addElement('text', 'cost', get_string('cost', 'local_trainingrequest'));
        $mform->addHelpButton('cost', 'cost', 'local_trainingrequest');
        $mform->setType('cost', PARAM_TEXT);

        $explodedcodes = explode(',', get_config('local_trainingrequest', 'chargeablecodes'));
        $codes = array();
        foreach ($explodedcodes as $item) {
            $codes[$item] = $item;
        }
        $mform->addElement('select', 'chargeablecostcode1', get_string('firstchargeablecostcode', 'local_trainingrequest'), $codes);
        $mform->setType('chargeablecostcode1', PARAM_TEXT);
        $mform->addElement('select', 'chargeablecostcode2', get_string('secondchargeablecostcode', 'local_trainingrequest'), $codes);
        $mform->setType('chargeablecostcode1', PARAM_TEXT);

        $mform->addElement('textarea', 'question1', get_string('question1', 'local_trainingrequest'), 'rows="4" cols="105"');
        $mform->setType('question1', PARAM_TEXT);
        $mform->addElement('textarea', 'question2', get_string('question2', 'local_trainingrequest'), 'rows="4" cols="105"');
        $mform->setType('question2', PARAM_TEXT);
        $mform->addElement('textarea', 'question3', get_string('question3', 'local_trainingrequest'), 'rows="4" cols="105"');
        $mform->setType('question3', PARAM_TEXT);
        $mform->addElement('textarea', 'question4', get_string('question4', 'local_trainingrequest'), 'rows="4" cols="105"');
        $mform->setType('question4', PARAM_TEXT);

        $mform->addElement('header', 'cpdheader', get_string('cpdheader', 'local_trainingrequest'));
        $mform->addElement('text', 'cpdhours', get_string('cpdhours', 'local_trainingrequest'));
        $mform->setType('cpdhours', PARAM_TEXT);
    }

    protected function after_definition() {
        $this->add_action_buttons();
    }

    protected function get_competencies() {
        global $DB;
        $competenciessql = "SELECT id, fullname FROM {comp} where visible = 1";
        $competencies = array(0 => get_string('no_competency', 'local_trainingrequest'));
        $records = $DB->get_records_sql($competenciessql);
        foreach ($records as $record) {
            $competencies[$record->id] = $record->fullname;
        }
        return $competencies;
    }
}
