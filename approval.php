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
 * Page to approve training requests.
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

$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_context($context);
$PAGE->set_heading(format_string($SITE->fullname));
$PAGE->set_title('Training Request');
$PAGE->set_url('/local/trainingrequest/approval.php');

$approvalform = new local_trainingrequest_manager_form(null);
if (is_int($id) && $id > 0) {
    $request = helper::get_request_by_id($id);
    if ($request == null
        || ($request->status !== 'new' && $request->status !== 'managerapproved')
        || ($request->status === 'new' && $request->directmanagerid !== $USER->id) // Only manager's can access new requests.
        || ($request->status === 'managerapproved'
            && !has_capability('local/trainingrequest:ldmanager', \context_user::instance($USER->id))) // Managers can't access L&D requests.
    ) {
        notice(get_string('noaccess', 'local_trainingrequest'), '/');
    }

    if ($USER->id === $request->directmanagerid) {
        $approvalform = new local_trainingrequest_manager_form(null, [
            'manager' => get_string('directmanagerheader', 'local_trainingrequest', $request->directmanagername)
        ]);
    } else if (has_capability('local/trainingrequest:ldmanager', \context_user::instance($USER->id))) {
        $approvalform = new local_trainingrequest_ldmanager_form(null, [
            'ldmanager' => get_string('ldmanagerheader', 'local_trainingrequest'),
            'manager' => get_string('directmanagerheader', 'local_trainingrequest', $request->directmanagername)
        ]);
        $request->dmapproveordeny = $request->status == 'managerapproved' ? 1 : 2;
    }
    $approvalform->set_data($request);
}

if ($approvalform->is_cancelled()) {
    redirect('/');
} else if ($data = $approvalform->get_data()) {
    $request = helper::get_request_by_id($data->id);
    if ($request != null) {
        if ($request->status == 'new' && $request->directmanagerid == $USER->id &&
            $approvalform instanceof local_trainingrequest_manager_form && ($data->dmapproveordeny == 1 || $data->dmapproveordeny == 2)) {
            // Manager.
            if ($data->dmapproveordeny == 1) {

                $request->status = 'managerapproved';
                $request->approvaluser = $USER->id;
                helper::notify_ldmanagers(
                    get_string('request_ld_notify_subject', 'local_trainingrequest'),
                    get_string('request_ld_notify_message', 'local_trainingrequest',
                        [
                            'id' => $data->id,
                            'learnersname' => $data->learnersname,
                            'managersname' => $data->directmanagername,
                            'coursename' => $data->coursename,
                            'trainingprovider' => $data->trainingprovider,
                            'wwwroot' => $CFG->wwwroot,
                        ]));
            } else {
                $request->status = 'managerdenied';
                $request->declinereason = $data->dmreason;
                $request->declininguser = $USER->id;
                helper::notify_user(
                    $request->userid,
                    get_string('request_declined_subject', 'local_trainingrequest'),
                    get_string('request_declined_message', 'local_trainingrequest',
                        [
                            'learnersname' => $data->learnersname,
                            'denier' => $data->directmanagername,
                            'trainingprovider' => $data->trainingprovider,
                            'coursename' => $data->coursename,
                            'wwwroot' => $CFG->wwwroot,
                            'reason' => $data->dmreason,
                        ]
                    ));
            }

            $DB->update_record('trainingrequests', $request);
        } else if ($request->status == 'managerapproved' && has_capability('local/trainingrequest:ldmanager',
                \context_user::instance($USER->id)) && $approvalform instanceof local_trainingrequest_ldmanager_form &&
            ($data->ldapproveordeny == 1 || $data->ldapproveordeny == 2)) {
            // L&D Manager.
            if ($data->ldapproveordeny == 1) {
                $request->status = 'ldapproved';
                $request->approvaluser = $USER->id;
                helper::notify_user(
                    $request->userid,
                    get_string('request_approved_subject', 'local_trainingrequest'),
                    get_string('request_approved_message', 'local_trainingrequest',
                        [
                            'learnersname' => $data->learnersname,
                            'trainingprovider' => $data->trainingprovider,
                            'coursename' => $data->coursename,
                        ]
                    ));
            } else {
                $request->status = 'lddenied';
                $request->declinereason = $data->ldreason;
                $request->declininguser = $USER->id;
                helper::notify_user(
                    $request->userid,
                    get_string('request_declined_subject', 'local_trainingrequest'),
                    get_string('request_declined_message', 'local_trainingrequest',
                        [
                            'learnersname' => $data->learnersname,
                            'denier' => $USER->firstname . ' ' . $USER->lastname,
                            'trainingprovider' => $data->trainingprovider,
                            'coursename' => $data->coursename,
                            'wwwroot' => $CFG->wwwroot,
                            'reason' => $data->ldreason,
                        ]
                    ));
            }

            $DB->update_record('trainingrequests', $request);
        }
        redirect('/');
    } else {
        notice(get_string('noaccess', 'local_trainingrequest'), '/');
    }
}

$output = $PAGE->get_renderer('core');
echo $output->header();
$approvalform->display();
echo $output->footer();
