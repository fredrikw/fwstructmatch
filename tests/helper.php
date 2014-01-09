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
 * Test helpers for the fwstructmatch question type.
 *
 * @package    qtype_fwstructmatch
 * @copyright  2013 Fredrik Wallner, 2012 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Test helper class for the fwstructmatch question type.
 *
 * @copyright  2013 Fredrik Wallner, 2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_fwstructmatch_test_helper extends question_test_helper {
    public function get_test_questions() {
        return array('silyls', 'TMSCl');
    }

    /**
     * Makes a fwstructmatch question with correct answer TMSCl, partially
     * correct answer TESCl and defaultmark 1. This question also has a
     * '*' match anything answer.
     * @return qtype_fwstructmatch_question
     */
    public function make_fwstructmatch_question_silyls() {
        question_bank::load_question_definition_classes('fwstructmatch');
        $sa = new qtype_fwstructmatch_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Structure match question';
        $sa->questiontext = 'Type the InChI for the most labile silyl PG as its chloride: __________';
        $sa->generalfeedback = 'Generalfeedback: TMSCl or TESCl would have been OK.';
        $sa->answers = array(
            13 => new question_answer(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0, 'TMSCl is a very good answer.', FORMAT_HTML),
            14 => new question_answer(14, 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', 0.3, 'TESCl is an OK answer.', FORMAT_HTML),
            15 => new question_answer(15, '*', 0.0, 'That is a bad answer.', FORMAT_HTML),
        );
        $sa->qtype = question_bank::get_qtype('fwstructmatch');

        return $sa;
    }

    /**
     * Gets the question data for a fwstructmatch question with with correct
     * ansewer TMSCl, partially correct answer TESCl and defaultmark 1.
     * This question also has a '*' match anything answer.
     * @return stdClass
     */
    public function get_fwstructmatch_question_data_silyls() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'fwstructmatch';
        $qdata->name = 'Structure match question';
        $qdata->questiontext = 'Type the InChI for the most labile silyl PG as its chloride: __________';
        $qdata->generalfeedback = 'Generalfeedback: TMSCl or TESCl would have been OK.';

        $qdata->options = new stdClass();
        $qdata->options->answers = array(
            13 => new question_answer(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0, 'TMSCl is a very good answer.', FORMAT_HTML),
            14 => new question_answer(14, 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', 0.3, 'TESCl is an OK answer.', FORMAT_HTML),
            15 => new question_answer(15, '*', 0.0, 'That is a bad answer.', FORMAT_HTML),
        );

        return $qdata;
    }

    /**
     * Makes a fwstructmatch question with just the correct ansewer TMSCl, and
     * no other answer matching.
     * @return qtype_fwstructmatch_question
     */
    public function make_fwstructmatch_question_TMSCl() {
        question_bank::load_question_definition_classes('fwstructmatch');
        $sa = new qtype_fwstructmatch_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Structure match question';
        $sa->questiontext = 'Type the InChI for TMSCl: __________';
        $sa->generalfeedback = 'Generalfeedback: TMSCl has the InChI InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3.';
        $sa->answers = array(
            13 => new question_answer(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0, 'That is right.', FORMAT_HTML),
        );
        $sa->qtype = question_bank::get_qtype('fwstructmatch');

        return $sa;
    }

    /**
     * Gets the question data for a fwstructmatch questionwith just the correct
     * ansewer 'frog', and no other answer matching.
     * @return stdClass
     */
    public function get_fwstructmatch_question_data_TMSCl() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'fwstructmatch';
        $qdata->name = 'Structure match question';
        $qdata->questiontext = 'Type the InChI for TMSCl: __________';
        $qdata->generalfeedback = 'Generalfeedback: TMSCl has the InChI InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3.';

        $qdata->options = new stdClass();
        $qdata->options->answers = array(
            13 => new question_answer(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0, 'That is right.', FORMAT_HTML),
        );

        return $qdata;
    }

}
