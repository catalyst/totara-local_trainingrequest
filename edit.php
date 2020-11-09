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
 * Page to edit a training request.
 *
 * @package     local_trainingrequest
 * @author      Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright   2020 onwards Catalyst IT Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_trainingrequest\helper;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

require_login();

if (!get_config('local_trainingrequest', 'enabled')) {
    redirect('/');
}

$context = context_system::instance();

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_context($context);
$PAGE->set_heading(format_string($SITE->fullname));
$PAGE->set_title('Training Request');
$PAGE->set_url('/local/trainingrequest/edit.php');

$editform = new local_trainingrequest_edit_form(null);
if (is_int($id) && $id > 0) {
    $request = helper::get_request_by_id($id);
    if ($request->userid === $USER->id && $request->status != 'addedtorol' && $request->status != 'unknown') {
        if ($editform->is_cancelled()) {
            redirect($_SERVER['HTTP_REFERER']);
        } else if ($data = $editform->get_data()) {
            helper::update_request($request, $data);
            redirect('/');
        } else {
            $editform->set_data($request);
        }
    } else {
        notice(get_string('noaccess', 'local_trainingrequest'));
    }
} else {
    notice(get_string('noaccess', 'local_trainingrequest'));
}

$output = $PAGE->get_renderer('core');
echo $output->header();
$editform->display();
echo $output->footer();
