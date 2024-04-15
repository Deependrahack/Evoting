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
 * Version information
 *
 * @package    mod_evoting
 * @copyright  2016 Cyberlearn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once ("../../config.php");
require_once ("lib.php");
require_login();
global $DB;
$system = context_system::instance();
$PAGE -> set_context($system);
$strname = get_string('modulenameplural', 'mod_evoting');
$PAGE -> set_url('/mod/evoting/reports.php');
$PAGE -> navbar -> add($strname);
$PAGE -> set_title("E-voting Reports");
$PAGE -> set_heading("E-voting Reports");
$PAGE -> set_pagelayout('incourse');

require_login();
//$PAGE->requires->jquery();
echo $OUTPUT -> header();
$renderer = $PAGE->get_renderer('mod_evoting');
if(is_siteadmin()){
    $course_id = 2;
}else{
    $coursesarray = enrol_get_users_courses($USER->id);
    $course_id = key($coursesarray);
}
if(true){
    echo $renderer->courses_filter();
    echo html_writer::start_div('reports_content_container', null);
    echo $renderer->reports_content($course_id);
    echo html_writer::end_div();
}else{
    echo core\notification::warning('You dont have capabilities to see this page content.');
}
$PAGE->requires->js_call_amd('mod_evoting/main', 'course_filter');
echo $OUTPUT->footer();