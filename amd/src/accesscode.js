//// This file is part of Moodle - http://moodle.org/
////
//// Moodle is free software: you can redistribute it and/or modify
//// it under the terms of the GNU General Public License as published by
//// the Free Software Foundation, either version 3 of the License, or
//// (at your option) any later version.
////
//// Moodle is distributed in the hope that it will be useful,
//// but WITHOUT ANY WARRANTY; without even the implied warranty of
//// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//// GNU General Public License for more details.
////
//// You should have received a copy of the GNU General Public License
//// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
///**
// * Module to initialise modal with listeners
// *
// * @module     format_popups/popups
// * @package    format_popups
// * @copyright  2021 Daniel Thies <dethies@gmail.com>
// * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
// */
//import Ajax from 'core/ajax';
//import config from 'core/config';
//import Fragment from 'core/fragment';
//import ModalEvents from 'core/modal_events';
//import ModalFactory from 'core/modal_factory';
//import notification from 'core/notification';
//import templates from 'core/templates';
//
///**
// * Initialize modal and listeners
// *
// * @param {int} contextid Course context id
// * @param {int} courseid Course id
// * @param {int} displaysection Single section to display
// */
define(['jquery', 'core/config', 'core/modal_factory', 'core/modal_events', 'core/notification', 'core/str'],
        function (e, config, ModalFactory, ModalEvents, notification, str) {
            $(document).on('submit', 'form', function (e) {
                     e.preventDefault();
                    $(".close").attr('style', 'display: none;');
                    let  accesscode = $("#accesscodeid").val();
                    var ajaxurl = 'ajax/accesscodecheck.php';
                    let cmid = getURLParameter('id');
                    $('.add-loader').addClass('spinner-border');                    
                    $.ajax({
                        type: 'POST',
                        data: {'cmid': cmid, 'accesscode': accesscode},
                        url: ajaxurl,
                        success: function (data) {
                            if (data == "success") {
                                $('.add-loader').removeClass('spinner-border');
                                let baseurl = $('.quizurl').val();
                                let reurl = M.cfg.wwwroot + "/mod/evoting/" + baseurl + "&accesscode=" + accesscode;
                                location.href = reurl;
                            } else if (data = "failed") {
                                $('.add-loader').removeClass('spinner-border');
                                $(".errormsg").removeAttr("style");
                                $(".errormsg").fadeOut(5000);
                                $(".errormsg").html("Please enter correct access code.");
                            }
                        },
                        error: function (data) {
                            alert('Something went wrong');
                        }
                    });
            });

            $(document).on('click', 'button.close', function () {
                let courseid = $(".courseid").val();
                let reurl = M.cfg.wwwroot + "/course/view.php?id=" + courseid;
                location.href = reurl;
            })
            function getURLParameter(name) {
                return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
            }
        });