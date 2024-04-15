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
require_once ("locallib.php");

$PAGE->requires->jquery();
$PAGE->requires->css('/mod/evoting/styles.css');
// Get URL QRCODE (client poll)
$path = dirname($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

$id = required_param('id', PARAM_INT);

if (!$cm = get_coursemodule_from_id('evoting', $id)) {
    print_error('invalidcoursemodule');
}

if (!$course = $DB->get_record("course", array("id" => $cm->course))) {
    print_error('coursemisconf');
}

if (!$evoting = evoting_get_evoting($cm->instance)) {
    print_error('invalidcoursemodule');
}

global $SITE;
// Lang course
$lang = current_language();
define('QRCODE_URL_LINK', "client_poll.php?id=" . $evoting->idpoll . '&lang=' . $lang . '&accesscode=' . "$evoting->accesscode");
if ($evoting->anonymous != 1) {
//    require_course_login($course, false, $cm);
    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url('/mod/evoting/check.php', array('id' => $id));
    $PAGE->set_heading(format_string($course->fullname));
} else {
    $context = context_system::instance();
    $PAGE->set_context($context);
}
// Get Contexts
$context_course = context_course::instance($course->id);

$PAGE->set_url('/mod/evoting/check.php', array('id' => $id));
$PAGE->set_title(format_string($evoting->name));
//$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();
$PAGE->requires->js_call_amd('mod_evoting/accesscode');

echo '<div class="check-page">';
echo '<div class="row justify-content-center align-items-center h-100">';
echo '<div class="col col-sm-6 col-md-6 col-lg-4 col-xl-3">';
echo '<div class="sc-jXupOl cChdJU container" >';
$logourl = $OUTPUT->get_logo_url();
echo "<div class='sc-dwyeRJ bzUkDv '>"
 . "<div data-functional-selector='game-logo' class='sc-nFpLZ cKijFY' title=$SITE->fullname>"
 . "<img src=$logourl title=$SITE->fullname  style='width:100%'></div></div>";
echo '<div class="text-primary add-loader" role="status">
       <span class="sr-only">Loading...</span></div>';
echo '<div class="errormsg  alert alert-danger" style="display: none;"></div>';
echo '<form action="#" class="check-form sc-eJFUOD dSObJT">'
 . '<input name="gameId" type="text" placeholder="Access code" id="accesscodeid" data-functional-selector="game-pin-input" class="sc-iUuytg ipZuUf" autocomplete="off" value="" aria-expanded="false">'
 . '<button type="submit" class="eMQRbB" data-functional-selector="join-game-pin">Enter</button>'
 . '</form>';
echo '</div>';
echo '</div>';
echo '</div>';
echo "<input type='hidden' class='courseid' value='" . $course->id . "'>";
echo "<input type='hidden' class='cmid' value='" . $id . "'>";
echo "<input type='hidden' class='quizurl' value='" . QRCODE_URL_LINK . "'>";
echo '</div>';
echo $OUTPUT->footer();
