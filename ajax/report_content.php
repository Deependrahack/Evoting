<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../../../config.php');
require_once('../lib.php');
global $PAGE;
$system = context_system::instance();
$PAGE -> set_context($system);
$course_id = optional_param('courseid', 0, PARAM_INT);
$renderer = $PAGE->get_renderer('mod_evoting');
echo $renderer->reports_content($course_id);
