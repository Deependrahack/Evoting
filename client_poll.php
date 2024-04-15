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
// GNU General Public License fo r more details.
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

$idPoll = required_param('id', PARAM_INT);
$lang = required_param('lang', PARAM_TEXT);
$accesscode = optional_param('accesscode', null, PARAM_RAW);
$system = context_system::instance();
$PAGE->set_context($system);
$strname = get_string('modulenameplural', 'mod_evoting');
if (empty($accesscode)) {
    $PAGE->set_url('/mod/evoting/client_poll.php', array('id' => $idPoll, 'lang' => $lang));
} else {
    $PAGE->set_url('/mod/evoting/client_poll.php', array('id' => $idPoll, 'lang' => $lang, 'accesscode' => $accesscode));
}
$PAGE->navbar->add($strname);
$PAGE->set_title("E-voting Reports");
$PAGE->set_heading("E-voting Reports");
$PAGE->set_pagelayout('incourse');

//require_login();
if (!isset($SESSION->evotingclientid)) {
    $clientid = uniqid('', true);
    $SESSION->evotingclientid = $clientid;
} else {
    $clientid = $SESSION->evotingclientid;
}
$evotingobj = $DB->get_record('evoting', array('id' => $idPoll));
if (!empty($evotingobj->accesscode) && ($evotingobj->accesscode != $accesscode) && !empty($accesscode)) {
    print_error('erroraccesscode', 'mod_evoting');
}
$userid = '';
if ($evotingobj->anonymous != 1) {
    global $USER;
    require_login();
    $userid = $USER->id;
    $answers = $DB->get_records('evoting_answers', array('evotingid' => $idPoll, 'userid' => $userid));
    if ($answers && !is_siteadmin()) {
        $has_attempted = $DB->record_exists('evoting_answers', array('uservoteid' => $clientid, 'evotingid' => $idPoll, 'userid' => $userid));
        if ($has_attempted) {
            echo $OUTPUT->header();
            echo core\notification::info('You have already attempted this activity.');
            echo $OUTPUT->footer();
            die();
        }
    }
}
$jqueryurl = new moodle_url('/mod/evoting/js/jquery-3.2.1.min.js');
//$jqueryurl = new moodle_url('/lib/jquery/jquery-3.2.1.min.js');

$cm = get_coursemodule_from_instance('evoting', $idPoll, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$cancelvote = get_string_manager()->get_string('cancelvote', 'evoting', null, $lang);
$votedeleted = get_string_manager()->get_string('votedeleted', 'evoting', null, $lang);
$votesingledeleted = get_string_manager()->get_string('votesingledeleted', 'evoting', null, $lang);
$voteok = get_string_manager()->get_string('voteok', 'evoting', null, $lang);
?>

<!DOCTYPE html>
<html style="height:100%" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Voting</title>
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $jqueryurl->out(); ?>"></script>
        <script src="./js/html5shiv.min.js"></script>
        <script src="./js/respond.min.js"></script>
        <script src="./js/client_poll.js"></script>
    </head>
    <body id="clientVote" style="height:100%;overflow: hidden;" class="path-mod-evoting">
        <div id="clientOptions"></div>
        <span id="spanIdPoll" style="display:none"><?php echo $idPoll; ?></span>
        <div class="toastBg" style="display:none"></div>
        <div id="myToast" class='toast' style="display:none">
            <span></span>
        </div>
        <div id="preloadImg" style="display:none"></div>
        <input id="lang" type="hidden" value="<?php echo $lang; ?>">
        <input id="userid" type="hidden" value="<?php echo $userid; ?>">
        <input id="sesskey" type="hidden" value="<?php echo sesskey(); ?>">
        <input id="clientid" type="hidden" value="<?php echo $clientid; ?>">
        <input id="votedeleted" type="hidden" value="<?php echo $votedeleted; ?>">
        <input id="voteok" type="hidden" value="<?php echo $voteok; ?>">
        <input id="votesingledeleted" type="hidden" value="<?php echo $votesingledeleted; ?>">
        <!--<div id="cancelvote"><button id="cancelbutton"><?php echo $cancelvote; ?></button></div>-->
    </body>
</html>


