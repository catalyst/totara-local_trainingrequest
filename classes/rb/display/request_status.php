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

defined('MOODLE_INTERNAL') || die();

class request_status extends \totara_reportbuilder\rb\display\base {

    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        switch ($value) {
            case 'new':
                $str = get_string('status:new', 'rb_source_request_management');
                break;
            case 'managerdenied':
                $str = get_string('status:managerdenied', 'rb_source_request_management');
                break;
            case 'managerapproved':
                $str = get_string('status:managerapproved', 'rb_source_request_management');
                break;
            case 'ldapproved':
                $str = get_string('status:ldapproved', 'rb_source_request_management');
                break;
            case 'lddenied':
                $str = get_string('status:lddenied', 'rb_source_request_management');
                break;
            case 'addedtorol':
                $str = get_string('status:addedtorol', 'rb_source_request_management');
                break;
            default:
                $str = get_string('status:unknown', 'rb_source_request_management');
                break;
        }
        if ($format !== 'html') {
            return $str;
        }
        return \html_writer::div('<span>' . $str . '</span>');
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }

}
