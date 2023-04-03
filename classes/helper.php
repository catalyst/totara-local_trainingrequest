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

namespace local_trainingrequest;

defined('MOODLE_INTERNAL') || die();

use totara_evidence\models;

class helper {

    public static function get_request_by_id($id) {
        global $DB;

        $record = $DB->get_record('trainingrequests', array('id' => $id), '*', MUST_EXIST);
        if (empty($record)) {
            return null;
        }
        $record->capabilities['capability1'] = $record->capability1;
        $record->capabilities['capability2'] = $record->capability2;
        $record->dateofrequest = $record->timecreated;
        return $record;
    }

    public static function create_request($data) {
        global $USER, $DB, $CFG;

        $transaction = $DB->start_delegated_transaction();

        $data->userid = $USER->id;
        $data->timecreated = time();
        $data->timemodified = time();
        $data->directmanagerid = !empty($data->directmanagerid) ? $data->directmanagerid : null;
        $data->coursename = !empty($data->coursename) ? $data->coursename : '';
        $data->trainingprovider = !empty($data->trainingprovider) ? $data->trainingprovider : '';
        $data->capability1 = $data->capabilities['capability1'];
        $data->capability2 = $data->capabilities['capability2'];
        $data->chargeablecostcode1 = !empty($data->chargeablecostcode1) ? $data->chargeablecostcode1 : '';
        $data->chargeablecostcode2 = !empty($data->chargeablecostcode2) ? $data->chargeablecostcode2 : '';
        $data->status = 'new';

        // Commit to DB.
        $data->id = $DB->insert_record('trainingrequests', $data);
        $transaction->allow_commit();

        // Send notification to direct manager.
        if (!empty($data->directmanagerid)) {
            self::notify_user($data->directmanagerid,
                get_string('manager_notification_subject', 'local_trainingrequest'),
                get_string('manager_notification_msg', 'local_trainingrequest',
                    [
                        'id' => $data->id,
                        'learnersname' => $data->learnersname,
                        'managersname' => $data->directmanagername,
                        'coursename' => $data->coursename,
                        'trainingprovider' => $data->trainingprovider,
                        'wwwroot' => $CFG->wwwroot,
                    ]));
        }

        return $data;
    }

    public static function notify_user($userid, $subject, $msg) {
        $user = \core_user::get_user($userid);
        $msgdata = new \core\message\message();
        $msgdata->courseid = SITEID;
        $msgdata->component = 'local_trainingrequest'; // Your component name.
        $msgdata->name = 'notification'; // This is the message name from messages.php.
        $msgdata->userfrom = \core_user::get_noreply_user();
        $msgdata->userto = $user;
        $msgdata->subject = $subject;
        $msgdata->fullmessage = html_to_text($msg);
        $msgdata->fullmessageformat = FORMAT_PLAIN;
        $msgdata->fullmessagehtml = $msg;
        $msgdata->smallmessage = '';
        $msgdata->notification = 1;
        $result = message_send($msgdata);

        if (!$result) {
            mtrace("ERROR: local/trainingrequest/classes/helper.php: Could not send notification to user $user->id ($user->email)");
        }
    }

    public static function notify_ldmanagers($subject, $msg) {
        global $DB;

        $results = array();
        $roles = get_roles_with_capability('local/trainingrequest:ldmanager');
        foreach ($roles as $role) {
            if (!empty($assignments = $DB->get_records('role_assignments', ['roleid' => $role->id]))) {
                $results = array_merge($assignments);
            }
        }

        $users = array();
        foreach ($results as $user) {
            if (empty($users[$user->userid])) {
                $query = "SELECT id, firstname, lastname FROM {user} WHERE id = :userid";
                $params = array(
                    'userid' => $user->userid
                );
                $details = $DB->get_record_sql($query, $params);
                $user->firstname = $details->firstname;
                $user->lastname = $details->lastname;
                self::notify_user($user->userid, $subject, $msg);
            }
        }
    }

    /**
     * Adds a training request to a request's user's record of learning.
     */
    public static function add_training_to_rol($request) {
        global $DB, $USER;

        if ($request != null && function_exists('customfield_save_data')) {
            $name = get_string('rolprefix', 'local_trainingrequest', $request->coursename);

            // Custom fields, not stored in totara_evidence_item.
            $customfield_data = new \stdClass();
            $customfield_data->customfield_evidencedescription_editor = array('text' => $request->coursename . ' from ' . $request->trainingprovider);

            if (get_config('local_trainingrequest', 'evidencetypeid') != 0) {
                $customfield_data->typeid = get_config('local_trainingrequest', 'evidencetypeid');
                $type = models\evidence_type::load_by_id(get_config('local_trainingrequest', 'evidencetypeid'));
            }
            if (get_config('local_trainingrequest', 'customfield_datecompleted_enabled')) {
                $fieldname = 'customfield_' . get_config('local_trainingrequest', 'customfield_datecompleted');
                $customfield_data->$fieldname = $request->enddate;
            }
            if (get_config('local_trainingrequest', 'customfield_cpdhours_enabled')) {
                $fieldname = 'customfield_' . get_config('local_trainingrequest', 'customfield_cpdhours');
                $customfield_data->$fieldname = !empty($request->cpdhours) ? $request->cpdhours : '';
            }

            models\evidence_item::create($type, $request->userid, $customfield_data, $name);

            $request->status = 'addedtorol';
            $DB->update_record('trainingrequests', $request);
        }
    }

    public static function update_request($request, $data) {
        global $DB, $CFG;

        $data->id = $request->id;
        $data->learnersname = $request->learnersname;
        $data->timecreated = time();
        $data->timemodified = time();
        $data->directmanagerid = !empty($data->directmanagerid) ? $data->directmanagerid : null;
        $data->coursename = !empty($data->coursename) ? $data->coursename : '';
        $data->trainingprovider = !empty($data->trainingprovider) ? $data->trainingprovider : '';
        $data->capability1 = $data->capabilities['capability1'];
        $data->capability2 = $data->capabilities['capability2'];
        $data->chargeablecostcode1 = !empty($data->chargeablecostcode1) ? $data->chargeablecostcode1 : '';
        $data->chargeablecostcode2 = !empty($data->chargeablecostcode2) ? $data->chargeablecostcode2 : '';

        $data->status = 'new';

        if (!empty($data->directmanagerid)) {
            self::notify_user($data->directmanagerid,
                get_string('manager_notification_subject', 'local_trainingrequest'),
                get_string('manager_notification_msg', 'local_trainingrequest',
                    [
                        'id' => $data->id,
                        'learnersname' => $data->learnersname,
                        'managersname' => $data->directmanagername,
                        'coursename' => $data->coursename,
                        'trainingprovider' => $data->trainingprovider,
                        'wwwroot' => $CFG->wwwroot,
                    ]));
        }

        $DB->update_record('trainingrequests', $data);
    }

    public static function delete_request($id) {
        global $DB;

        $DB->delete_records('trainingrequests', ['id' => $id]);
    }

}
