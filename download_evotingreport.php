<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../../config.php');
require_login();
global $CFG, $PAGE, $DB, $USER, $OUTPUT;
require_once($CFG->libdir . '/dataformatlib.php');
require_once($CFG->dirroot . '/mod/evoting/lib.php');

$course_id = optional_param('courseid', null, PARAM_INT);
$format = optional_param('dataformat', '', PARAM_ALPHA);

if ($format) {
    $params['dataformat'] = $format;
}

if ($format) {
    $coursecontext = context_course::instance($course_id);
    $courseusers = get_enrolled_users($coursecontext);
    $emods = mod_evoting_course_module($course_id);

    $filename = clean_filename('evotingreport');
    $fields = [];
    $fields[] = "Fullname";
    $moduleids = array();
    foreach ($emods as $e) {
        $fields[] = $e->name;
        $moduleids[] = $e->instance;
    }
    $fields[] = "Sum";
    $downloadusers = new ArrayObject($courseusers);
    $iterator = $downloadusers->getIterator();

    \core\dataformat::download_data($filename, $format, $fields, $iterator, function($data) use($moduleids) {
        $finaldata = array();
        $row[] = fullname($data);
        foreach ($moduleids as $moduleid) {
            $row[] = correct_answer_by_user($moduleid, $data->id);
        }
        $sum = $row;
        array_shift($sum);
        $row[] = sprintf("%.2f",array_sum($sum)/count($sum));
        $finaldata = $row;
        return $finaldata;
    });

    exit;
}
