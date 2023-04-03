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

class rb_source_request_management extends rb_base_source {
    use \totara_reportbuilder\rb\source\report_trait;
    use \totara_job\rb\source\report_trait;

    public $base, $joinlist, $columnoptions, $filteroptions;

    public function __construct() {
        $this->base = '{trainingrequests}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->sourcetitle = 'Training Requests';
        $this->sourcelabel = 'Training Requests';
        $this->contentoptions = $this->define_contentoptions();
        $this->usedcomponents[] = 'local_trainingrequest';

        parent::__construct();
    }

    protected function define_joinlist() {
        $joinlist = array(
            new rb_join(
                'job_assignment',
                'LEFT',
                '{job_assignment}',
                'job_assignment.userid = base.directmanagerid'
            ),
        );

        $this->add_core_user_tables($joinlist, 'base', 'userid');
        $this->add_totara_job_tables($joinlist, 'base', 'userid');

        return $joinlist;
    }

    protected function define_columnoptions() {
        $columnoptions = array(
            new rb_column_option(
                'request',
                'learnersname',
                get_string('learnersname', 'rb_source_request_management'),
                'base.learnersname',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'jobtitle',
                get_string('jobtitle', 'rb_source_request_management'),
                'base.jobtitle',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'teamname',
                get_string('teamname', 'rb_source_request_management'),
                'base.teamname',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'coursename',
                get_string('coursename', 'rb_source_request_management'),
                'base.coursename',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'trainingprovider',
                get_string('trainingprovider', 'rb_source_request_management'),
                'base.trainingprovider',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'question1',
                get_string('question1', 'rb_source_request_management'),
                'base.question1',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'question2',
                get_string('question2', 'rb_source_request_management'),
                'base.question2',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'question3',
                get_string('question3', 'rb_source_request_management'),
                'base.question3',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'question4',
                get_string('question4', 'rb_source_request_management'),
                'base.question4',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'directmanagername',
                get_string('directmanagername', 'rb_source_request_management'),
                'base.directmanagername',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'status',
                get_string('status', 'rb_source_request_management'),
                'base.status',
                array(
                    'displayfunc' => 'request_status'
                )
            ),
            new rb_column_option(
                'request',
                'timemodified',
                get_string('timemodified', 'rb_source_request_management'),
                'base.timemodified',
                array(
                    'displayfunc' => 'nice_datetime'
                )
            ),
            new rb_column_option(
                'request',
                'reason',
                get_string('reason', 'rb_source_request_management'),
                'base.declinereason',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'approvallink',
                get_string('approvallink', 'rb_source_request_management'),
                'base.id',
                array(
                    'displayfunc' => 'approval_link',
                    'noexport' => true,
                )
            ),
            new rb_column_option(
                'request',
                'rollink',
                get_string('rollink', 'rb_source_request_management'),
                'base.id',
                array(
                    'displayfunc' => 'rol_link',
                    'noexport' => true,
                )
            ),
            new rb_column_option(
                'request',
                'singlelink',
                get_string('singlelink', 'rb_source_request_management'),
                'base.id',
                array(
                    'displayfunc' => 'single_link',
                    'noexport' => true,
                )
            ),
            new rb_column_option(
                'request',
                'managerjobtitle',
                get_string('managerjobtitle', 'rb_source_request_management'),
                'job_assignment.fullname',
                array(
                    'joins' => 'job_assignment',
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'chargeablecostcode1',
                get_string('chargeablecostcode1', 'rb_source_request_management'),
                'base.chargeablecostcode1',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'chargeablecostcode2',
                get_string('chargeablecostcode2', 'rb_source_request_management'),
                'base.chargeablecostcode2',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'dateofrequest',
                get_string('dateofrequest', 'rb_source_request_management'),
                'base.timecreated',
                array(
                    'displayfunc' => 'nice_date'
                )
            ),
            new rb_column_option(
                'request',
                'startdate',
                get_string('startdate', 'rb_source_request_management'),
                'base.startdate',
                array(
                    'displayfunc' => 'nice_date'
                )
            ),
            new rb_column_option(
                'request',
                'enddate',
                get_string('enddate', 'rb_source_request_management'),
                'base.enddate',
                array(
                    'displayfunc' => 'nice_date'
                )
            ),
            new rb_column_option(
                'request',
                'declininguser',
                get_string('declininguser', 'rb_source_request_management'),
                'base.declininguser',
                array(
                    'displayfunc' => 'users_name'
                )
            ),
            new rb_column_option(
                'request',
                'cpdhours',
                get_string('cpdhours', 'rb_source_request_management'),
                'base.cpdhours',
                array(
                    'displayfunc' => 'format_string'
                )
            ),
            new rb_column_option(
                'request',
                'iscoaching',
                get_string('coaching', 'rb_source_request_management'),
                'base.iscoaching',
                array(
                    'displayfunc' => 'yes_or_no'
                )
            ),
            new rb_column_option(
                'request',
                'viewrequest',
                get_string('viewrequestbutton', 'rb_source_request_management'),
                'base.id',
                array(
                    'displayfunc' => 'view_request',
                    'noexport' => true,
                )
            )
        );

        $this->add_core_user_columns($columnoptions);
        $this->add_totara_job_columns($columnoptions);

        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'request',
                'learnersname',
                get_string('learnersname', 'rb_source_request_management'),
                'text'
            ),
            new rb_filter_option(
                'request',
                'chargeablecostcode1',
                get_string('chargeablecostcode1', 'rb_source_request_management'),
                'text'
            ),
            new rb_filter_option(
                'request',
                'chargeablecostcode2',
                get_string('chargeablecostcode2', 'rb_source_request_management'),
                'text'
            ),
            new rb_filter_option(
                'request',
                'status',
                get_string('status', 'rb_source_request_management'),
                'text'
            ),
            new rb_filter_option(
                'request',
                'dateofrequest',
                get_string('dateofrequest', 'rb_source_request_management'),
                'date'
            ),
            new rb_filter_option(
                'request',
                'startdate',
                get_string('startdate', 'rb_source_request_management'),
                'date'
            ),
            new rb_filter_option(
                'request',
                'enddate',
                get_string('enddate', 'rb_source_request_management'),
                'date'
            )
        );

        $this->add_core_user_filters($filteroptions);
        $this->add_totara_job_filters($filteroptions, 'base', 'userid');

        return $filteroptions;
    }

    protected function define_contentoptions() {
        $contentoptions = array();

        $this->add_basic_user_content_options($contentoptions);

        return $contentoptions;
    }

    public function global_restrictions_supported() {
        return true;
    }

    /**
     * Returns expected result for column_test.
     * @param rb_column_option $columnoption
     * @return int
     */
    public function phpunit_column_test_expected_count($columnoption) {
        if (!PHPUNIT_TEST) {
            throw new coding_exception('phpunit_column_test_expected_count() cannot be used outside of unit tests');
        }
        return 0;
    }

}
