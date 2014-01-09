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
 * Unit tests for the fwstructmatch question definition class.
 *
 * @package    qtype
 * @subpackage fwstructmatch
 * @copyright  2013 Fredrik Wallner, 2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');
require_once($CFG->dirroot . '/question/type/fwstructmatch/question.php');


/**
 * Unit tests for the fwstructmatch question definition class.
 *
 * @copyright  2013 Fredrik Wallner, 2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_fwstructmatch_question_test extends advanced_testcase {
    public function test_compareinchis() {
        // Test matches with stereochemistry.
        $this->assertTrue((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', true));
        $this->assertFalse((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', true));
        $this->assertTrue((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', 'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', true));
        $this->assertFalse((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', 'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m1/s1', true));

        // Test matches without stereochemistry. Not yet implemented
        $this->assertTrue((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', false));
        $this->assertFalse((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', false));
        $this->assertTrue((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', 'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', false));
        $this->assertTrue((bool)qtype_fwstructmatch_question::compareinchis(
                'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m0/s1', 'InChI=1S/C3H7NO2/c1-2(4)3(5)6/h2H,4H2,1H3,(H,5,6)/t2-/m1/s1', false));
    }

    public function test_is_complete_response() {
        $question = test_question_maker::make_question('fwstructmatch');

        $this->assertFalse($question->is_complete_response(array()));
        $this->assertFalse($question->is_complete_response(array('answer' => '')));
        $this->assertTrue($question->is_complete_response(array('answer' => '0')));
        $this->assertTrue($question->is_complete_response(array('answer' => '0.0')));
        $this->assertTrue($question->is_complete_response(array('answer' => 'x')));
    }

    public function test_is_gradable_response() {
        $question = test_question_maker::make_question('fwstructmatch');

        $this->assertFalse($question->is_gradable_response(array()));
        $this->assertFalse($question->is_gradable_response(array('answer' => '')));
        $this->assertTrue($question->is_gradable_response(array('answer' => '0')));
        $this->assertTrue($question->is_gradable_response(array('answer' => '0.0')));
        $this->assertTrue($question->is_gradable_response(array('answer' => 'x')));
    }

    public function test_grading() {
        $question = test_question_maker::make_question('fwstructmatch');

        $this->assertEquals(array(0, question_state::$gradedwrong),
                $question->grade_response(array('answer' => 'x')));
        $this->assertEquals(array(1, question_state::$gradedright),
                $question->grade_response(array('answer' => 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3')));
        $this->assertEquals(array(0.3, question_state::$gradedpartial),
                $question->grade_response(array('answer' => 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3')));
    }

    public function test_get_correct_response() {
        $question = test_question_maker::make_question('fwstructmatch');

        $this->assertEquals(array('answer' => 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3'),
                $question->get_correct_response());
    }

    public function test_get_question_summary() {
        $sa = test_question_maker::make_question('fwstructmatch');
        $qsummary = $sa->get_question_summary();
        $this->assertEquals('Type the InChI for the most labile silyl PG as its chloride: __________', $qsummary);
    }

    public function test_summarise_response() {
        $sa = test_question_maker::make_question('fwstructmatch');
        $summary = $sa->summarise_response(array('answer' => 'dog'));
        $this->assertEquals('dog', $summary);
    }

    public function test_classify_response() {
        $sa = test_question_maker::make_question('fwstructmatch');
        $sa->start_attempt(new question_attempt_step(), 1);

        $this->assertEquals(array(
                new question_classified_response(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0)),
                $sa->classify_response(array('answer' => 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3')));
        $this->assertEquals(array(
                new question_classified_response(14, 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', 0.3)),
                $sa->classify_response(array('answer' => 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3')));
        $this->assertEquals(array(
                new question_classified_response(15, 'cat', 0.0)),
                $sa->classify_response(array('answer' => 'cat')));
        $this->assertEquals(array(
                question_classified_response::no_response()),
                $sa->classify_response(array('answer' => '')));
    }

    public function test_classify_response_no_star() {
        $sa = test_question_maker::make_question('fwstructmatch', 'TMSCl');
        $sa->start_attempt(new question_attempt_step(), 1);

        $this->assertEquals(array(
                new question_classified_response(13, 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3', 1.0)),
                $sa->classify_response(array('answer' => 'InChI=1S/C3H9ClSi/c1-5(2,3)4/h1-3H3')));
        $this->assertEquals(array(
                new question_classified_response(0, 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3', 0.0)),
                $sa->classify_response(array('answer' => 'InChI=1S/C6H15ClSi/c1-4-8(7,5-2)6-3/h4-6H2,1-3H3')));
        $this->assertEquals(array(
                question_classified_response::no_response()),
                $sa->classify_response(array('answer' => '')));
    }
}
