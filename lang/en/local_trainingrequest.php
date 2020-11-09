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

$string['pluginname'] = 'Training Request';
$string['description'] = 'Request training through a form';

// Request form.
$string['name'] = 'Name';
$string['jobtitle'] = 'Job Title';
$string['dateofrequest'] = 'Date of request';
$string['team'] = 'Team';
$string['managername'] = 'Manager Name';
$string['coursename'] = 'Name of activity/course';
$string['trainingprovider'] = 'Training Provider';
$string['capabilities'] = 'Capability/Capabilities training relates to';
$string['capability'] = 'Capability';
$string['startdate'] = 'Start Date of Activity/Course';
$string['enddate'] = 'End Date of Activity/Course';
$string['cost'] = 'Cost (excluding travel)*';
$string['cost_help'] = 'Please refer to the travel policy form if travel is required';
$string['chargeablecostcode'] = 'Chargeable cost code';
$string['firstchargeablecostcode'] = 'First chargeable cost code';
$string['secondchargeablecostcode'] = 'Second chargeable cost code';
$string['question1'] = 'What is the targeted learning and development need? What development goal does this align to?';
$string['question2'] = 'What are the key objectives that this training covers?';
$string['question3'] = 'What will be the improvement once you have attended this training?';
$string['question4'] = 'How will the business see or measure the benefits of this investment?';
$string['cpdheader'] = 'If this is a CPD Activity, please provide details (hours of structured learning etc.)';
$string['cpdhours'] = 'CPD Hours';
$string['submit'] = 'Submit';
$string['no_competency'] = 'No capability';
$string['approveordeny'] = 'Approve / Decline';
$string['reason'] = 'Reason';
$string['directmanagerheader'] = 'Manager: {$a}';
$string['ldmanagerheader'] = 'L&D Manager';
$string['iscoaching'] = 'Is this coaching?';
$string['editbutton'] = 'Edit Request';
$string['delete'] = 'Delete';
$string['deleteconfirmation'] = 'Are you sure you want to delete this request?';
// Permissions.
$string['trainingrequest:submitrequest'] = 'submit training request';
$string['trainingrequest:manager'] = 'accept/decline tasks as a manager';
$string['trainingrequest:ldmanager'] = 'accept/decline tasks as an L&D manager';
$string['trainingrequest:config'] = 'view & change configuration settings';
$string['trainingrequest:subscribe'] = 'Receive notifications and emails';
// Settings.
$string['enabled'] = 'Enabled';
$string['enabled_desc'] = 'Should form be a ccessible';
$string['chargeablecodes'] = 'Chargeable codes';
$string['chargeablecodes_desc'] = 'Comma separated list of chargeable codes';
$string['evidencetype'] = 'Evidence Type';
$string['evidencetype_desc'] = 'Used when adding items to Record of Learning';
$string['customfield_datecompleted_enabled'] = 'RoL Datecompleted field enabled';
$string['customfield_datecompleted_enabled_desc'] = 'When adding to RoL is the date completed field also added?';
$string['customfield_datecompleted'] = 'RoL Date completed name of custom field';
$string['customfield_datecompleted_desc'] = '';
$string['customfield_cpdhours_enabled'] = 'RoL CPD hours field enabled';
$string['customfield_cpdhours_enabled_desc'] = 'When adding to RoL is the CPD hours field also added?';
$string['customfield_cpdhours'] = 'RoL CPD hours name of custom field';
$string['customfield_cpdhours_desc'] = '';
$string['costcode_prepopulation_enabled'] = 'Cost code pre-population enabled?';
$string['costcode_prepopulation_enabled_desc'] = 'If enabled it will pre-populate the cost code field from the user profile field defined below';
$string['costcode_prepopulation_fieldname'] = 'Custom profile field shortname';
$string['costcode_prepopulation_fieldname_desc'] = '';
// Other.
$string['requestcreated'] = 'Training Request made successfully. Sent notification to manager to be approved.';
$string['manager_notification_subject'] = 'Request Training Notification';
$string['manager_notification_msg'] = '<h3>NEW TRAINING REQUEST</h3><br>
Hello {$a->managersname},<br>
New training request submitted by {$a->learnersname} for course {$a->coursename} with provider {$a->trainingprovider}<br>
Click the link below to view the request:<br>
<a href="{$a->wwwroot}/local/trainingrequest/approval.php?id={$a->id}">{$a->wwwroot}/local/trainingrequest/approval.php?id={$a->id}</a>';
$string['required'] = 'This field is required.';
$string['noaccess'] = 'You do not have access to this training request.';
$string['missingprerequisites'] = 'You are missing the required prerequisites(A Manager) to file a training request.';
$string['request_declined_subject'] = 'Your training request has been declined.';
$string['request_declined_message'] = 'Hello {$a->learnersname},<br>
Your training request to {$a->trainingprovider} for the {$a->coursename} course has been denied by {$a->denier} for the following reason:<br>
{$a->reason}';
$string['request_ld_notify_subject'] = 'A new training request is ready for your approval';
$string['request_ld_notify_message'] = 'New training request submitted by {$a->learnersname} for course {$a->coursename} with provider {$a->trainingprovider} has been approved by {$a->managersname}<br>
It is now ready for L&D Approval. Click the link below to view the request:<br>
<a href="{$a->wwwroot}/local/trainingrequest/approval.php?id={$a->id}">{$a->wwwroot}/local/trainingrequest/approval.php?id={$a->id}</a>';
$string['request_approved_subject'] = 'Your training request has been approved!';
$string['request_approved_message'] = 'Hello {$a->learnersname},<br>
Your training request to {$a->trainingprovider} for the {$a->coursename} has been approved.';
$string['addedtorol'] = 'Successfully added to the user\'s Record of Learning';
$string['rolprefix'] = 'Completed course : {$a}';
$string['messageprovider:notification'] = 'Training request notifications';
