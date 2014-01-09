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
 * Unit tests for the fwstructmatch question type class.
 *
 * @package    qtype
 * @subpackage fwstructmatch
 * @copyright  2013 Fredrik Wallner, 2007 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/fwstructmatch/questiontype.php');
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');


/**
 * Unit tests for the fwstructmatch question type class.
 *
 * @copyright  2013 Fredrik Wallner, 2007 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_fwstructmatch_test extends advanced_testcase {
    public static $includecoverage = array(
        'question/type/questiontypebase.php',
        'question/type/fwstructmatch/questiontype.php',
    );

    protected $qtype;

    protected function setUp() {
        $this->qtype = new qtype_fwstructmatch();
    }

    protected function tearDown() {
        $this->qtype = null;
    }

    protected function get_test_question_data() {
        return test_question_maker::get_question_data('fwstructmatch');
    }

    public function test_name() {
        $this->assertEquals($this->qtype->name(), 'fwstructmatch');
    }

    public function test_can_analyse_responses() {
        $this->assertTrue($this->qtype->can_analyse_responses());
    }

    public function test_get_random_guess_score() {
        $q = test_question_maker::get_question_data('fwstructmatch');
        $q->options->answers[15]->fraction = 0.1;
        $this->assertEquals(0.1, $this->qtype->get_random_guess_score($q));
    }

    public function test_get_possible_responses() {
        $q = test_question_maker::get_question_data('fwstructmatch');

        $this->assertEquals(array(
            $q->id => array(
                13 => new question_possible_response('InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1),
                14 => new question_possible_response('InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', 0.3),
                15 => new question_possible_response('*', 0),
                null => question_possible_response::no_response()
            ),
        ), $this->qtype->get_possible_responses($q));
    }

    public function test_get_possible_responses_no_star() {
        $q = test_question_maker::get_question_data('fwstructmatch', 'TMSCl');

        $this->assertEquals(array(
            $q->id => array(
                13 => new question_possible_response('InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1),
                0 => new question_possible_response(get_string('didnotmatchanyanswer', 'question'), 0),
                null => question_possible_response::no_response()
            ),
        ), $this->qtype->get_possible_responses($q));
    }
}
