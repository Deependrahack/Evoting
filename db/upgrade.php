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

/**
 * Upgrade evoting
 * @param $oldversion
 * @return bool
 */
function xmldb_evoting_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    $tableOptions = new xmldb_table('evoting_options');
    $fieldCorrect = new xmldb_field('correct', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

    if (!$dbman->field_exists($tableOptions, $fieldCorrect)) {
        $dbman->add_field($tableOptions, $fieldCorrect);
    }

    $tableQuestions = new xmldb_table('evoting_questions');
    $multipleAnswers = new xmldb_field('multipleanswers', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

    if (!$dbman->field_exists($tableQuestions, $multipleAnswers)) {
        $dbman->add_field($tableQuestions, $multipleAnswers);
    }

    if ($oldversion < 2020070700.02) {

        // Define field anonymous to be added to evoting.
        $table = new xmldb_table('evoting');
        $field = new xmldb_field('anonymous', XMLDB_TYPE_INTEGER, '2', null, null, null, '1', 'allowupdate');

        // Conditionally launch add field anonymous.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field userid to be added to evoting_answers.
        $table = new xmldb_table('evoting_answers');
        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'uservoteid');

        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field evotingid to be added to evoting_answers.
        $table = new xmldb_table('evoting_answers');
        $field = new xmldb_field('evotingid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'userid');

        // Conditionally launch add field evotingid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Evoting savepoint reached.
        upgrade_mod_savepoint(true, 2020070700.02, 'evoting');
    }
    if ($oldversion < 2020070700.03) {

        // Define field accesscode to be added to evoting.
        $table = new xmldb_table('evoting');
        $field = new xmldb_field('accesscode', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'introformat');

        // Conditionally launch add field accesscode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evoting savepoint reached.
        upgrade_mod_savepoint(true, 2020070700.03, 'evoting');
    }

    return true;
}
