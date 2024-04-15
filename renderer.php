<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class mod_evoting_renderer extends plugin_renderer_base {

    function reports_content($course_id) {
        global $OUTPUT;
        $o = '';
        $coursecontext = context_course::instance($course_id);
        $courseusers = get_enrolled_users($coursecontext);
        $emods = mod_evoting_course_module($course_id);
        if(empty($emods)){
            $o .= html_writer::tag('div', 'Sorry, This course not has any evoting activity added.', array('class'=>'alert alert-warning'));
            return $o;
        }
        $table = new html_table();
        $table->attributes['class'] = 'generaltable mod_index';
        $head = [];
        $head[] = "Fullname";
        $moduleids = array();

        foreach ($emods as $e) {
            $head[] = $e->name." ($e->accesscode)";
            $moduleids[] = $e->instance;
        }
        $count_moduleids = count($moduleids);
        $head[] = " Quiz sum";
        $table->head = $head;
        $table->align = array('center', 'left');
        foreach ($courseusers as $key => $user) {
            $row = array();
            $row[] = fullname($user);
            foreach($moduleids as $moduleid){
                $row[] = correct_answer_by_user($moduleid, $user->id);
            }
            $sum = $row;
            array_shift($sum);
            $row[] = sprintf("%.2f",array_sum($sum)/$count_moduleids);
            $table->data[] = $row;
        }
        $o .= html_writer::table($table);
        $o .= $OUTPUT->download_dataformat_selector('', 'download_evotingreport.php', $name = 'dataformat', array('courseid' => $course_id));
        return $o;
    }
    
    public function courses_filter() {
        global $DB, $USER;
        $o = '';
        $coursesarray = [];
        if(is_siteadmin()){
            $coursesarray = $DB->get_records('course', array('visible' => 1), '', 'id, fullname');
            $defaultval = 2;
        }
        if(!is_siteadmin()){
           $coursesarray = enrol_get_users_courses($USER->id); 
           $defaultval = key($coursesarray);
        }
        $courses = [];
        foreach ($coursesarray as $course) {
            $courses[$course->id] = $course->fullname;
        }
        $o .= html_writer::start_tag('div', array('class'=>'form-group col-md-6'));
        $o .= html_writer::label('Select Course', 'coursefilter', '', array('class'=>'col-md-3'));
        $o .= html_writer::select($courses, 'courses_filter', array($defaultval), '', array('id'=>'coursefilter','class'=>'course_filter form-select w-50'));
        $o .= html_writer::end_tag('div');
        return $o;
    }

}
//SELECT evans.id, evans.userid, evans.evotingid, evans.optionid, ev_ques.id as 'questionid'  FROM `mdl_evoting_answers`  AS `evans` INNER JOIN `mdl_evoting_questions` AS `ev_ques` ON evans.`evotingid` = ev_ques.`evotingid` WHERE evans.evotingid = ev_ques.`evotingid`