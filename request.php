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
 * Page to create a training request.
 * Autofills details from the job assignment, user, and user info data tables.
 *
 * @package   local_trainingrequest
 * @author    Alex Morris <alex.morris@catalyst.net.nz>
 * @copyright 2020 onwards Catalyst IT Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_trainingrequest\helper;

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

require_login();

if (!get_config('local_trainingrequest', 'enabled')) {
    redirect('/');
}

$context = context_system::instance();
require_capability('local/trainingrequest:submitrequest', $context);

$PAGE->set_context($context);
$PAGE->set_heading(format_string($SITE->fullname));
$PAGE->set_title('Training Request');
$PAGE->set_url('/local/trainingrequest/request.php');

$requestform = new local_trainingrequest_request_form();
if ($requestform->is_cancelled()) {
    redirect('/');
} else if ($data = $requestform->get_data()) {
    helper::create_request($data);

    notice(get_string('requestcreated', 'local_trainingrequest'), '/');
} else {
    // Autofill some details in the form.
    $sql = "SELECT u.firstname,
                    u.lastname,
                    u.department as teamname,
                    j.fullname AS jobtitle,
                    dj.userid AS directmanagerid,
                    uj.firstname AS managerfirstname,
                    uj.lastname AS managerlastname";
    if (get_config('local_trainingrequest', 'costcode_prepopulation_enabled')) {
        $sql .= ", uid.data as costcode";
    }
    $sql .= " FROM {user} u
                    LEFT JOIN {job_assignment} j
                        ON j.userid = u.id
                    LEFT JOIN {job_assignment} dj
                        ON dj.id = j.managerjaid
                    LEFT JOIN {user} uj
                        ON uj.id = dj.userid";
    if (get_config('local_trainingrequest', 'costcode_prepopulation_enabled')) {
        $sql .= " LEFT JOIN {user_info_field} uif
                     on uif.shortname = '" . get_config('local_trainingrequest', 'costcode_prepopulation_fieldname') . "'
                 LEFT JOIN {user_info_data} uid
                     on uid.userid = u.id and uid.fieldid = uif.id";
    }
    $sql .= " WHERE u.id = :userid";
    $params = array(
        'userid' => $USER->id
    );
    $details = $DB->get_record_sql($sql, $params);

    if (empty($details->firstname)
        || empty($details->lastname)
        || empty($details->managerfirstname)
        || empty($details->managerlastname)) {
        notice(get_string('missingprerequisites', 'local_trainingrequest'));
    }

    $details->learnersname = $details->firstname . ' ' . $details->lastname;
    $details->directmanagername = $details->managerfirstname . ' ' . $details->managerlastname;
    $requestform->set_data($details);
}

$output = $PAGE->get_renderer('core');
echo $output->header();
echo $output->heading('Training Request Form', 1);
$requestform->display();
echo $output->footer();
