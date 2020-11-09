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

namespace local_trainingrequest\rb\display;

use moodle_url;

defined('MOODLE_INTERNAL') || die();

class single_link extends \totara_reportbuilder\rb\display\base {

    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        global $DB, $USER;

        if ($format !== 'html') {
            return '';
        }

        $request = $DB->get_record_sql('SELECT enddate, status, directmanagerid, userid FROM {trainingrequests} WHERE id = :id', array('id' => $value));
        if ($request->enddate !== null && $request->enddate <= time() &&
            $request->status == 'ldapproved' && ($USER->id === $request->directmanagerid || $USER->id === $request->userid)) {
            return \html_writer::link(new moodle_url('/local/trainingrequest/rol.php',
                array('id' => $value, 'action' => 'add')),
                \html_writer::tag('button', get_string('rolbutton', 'rb_source_request_management')));
        }
        if (
            ($request->status == 'new' && $USER->id === $request->directmanagerid) ||
            ($request->status == 'managerapproved' && has_capability('local/trainingrequest:ldmanager', \context_user::instance($USER->id)))
        ) {
            return \html_writer::link(new moodle_url('/local/trainingrequest/approval.php',
                array('id' => $value)),
                \html_writer::tag('button', get_string('approvalbutton', 'rb_source_request_management')));
        }

        if ($USER->id === $request->userid) {
            return \html_writer::link(new moodle_url('/local/trainingrequest/view.php',
                array('id' => $value)),
                \html_writer::tag('button', get_string('viewrequestbutton', 'rb_source_request_management')));
        }
        return '';
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }

}
