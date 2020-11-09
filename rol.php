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
 * Page to add a training request to a user's record of learning.
 *
 * @package   local_trainingrequest
 * @author    Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright 2020 onwards Catalyst IT Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->dirroot.'/totara/customfield/fieldlib.php');

use local_trainingrequest\helper;

require_login();

$context = context_system::instance();

$id = required_param('id', PARAM_INT);
$action = required_param('action', PARAM_TEXT);

$PAGE->set_context($context);
$PAGE->set_heading(format_string($SITE->fullname));
$PAGE->set_title('Training Request');
$PAGE->set_url(new moodle_url('/local/trainingrequest/rol.php', array('action' => $action, 'id' => $id)));

if ($action === 'add') {
    $request = helper::get_request_by_id($id);
    if ($request != null) {
        if ($request->enddate <= time()) {
            helper::add_training_to_rol($request);
            totara_set_notification(get_string('evidenceadded', 'totara_plan'), null, array('class' => 'notifysuccess'));
            notice(get_string('addedtorol', 'local_trainingrequest'), '/');
            return;
        }
    }
}
notice(get_string('noaccess', 'local_trainingrequest'), '/');
