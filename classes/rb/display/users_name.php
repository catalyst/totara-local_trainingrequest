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

class users_name extends \totara_reportbuilder\rb\display\base {

    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report) {
        if (!empty($value)) {
            global $DB;

            $user = $DB->get_record('user', array('id' => $value), 'firstname, lastname', MUST_EXIST);
            if (!empty($user->firstname) && !empty($user->lastname)) {
                return $user->firstname . ' ' . $user->lastname;
            }
        }
        return '';
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return false;
    }

}
