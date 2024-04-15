<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../../../config.php');
require_once('../lib.php');
require_once ("../locallib.php");

$accesscode = optional_param('accesscode', null, PARAM_RAW);
$id = optional_param('cmid', 0, PARAM_INT);
global $PAGE;
$system = context_system::instance();
$PAGE -> set_context($system);


if (!$cm = get_coursemodule_from_id('evoting', $id)) {
	print_error('invalidcoursemodule');
}

if (!$evoting = evoting_get_evoting($cm -> instance)) {
	print_error('invalidcoursemodule');
}
if($accesscode == $evoting->accesscode){
    echo "success";
}else{
   echo "failed";
}