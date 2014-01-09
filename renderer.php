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
 * fwstructmatch question renderer class. Based on the short answer question renderer class. With inspiration from pmatchjme from OU.
 *
 * @package    qtype
 * @subpackage fwstructmatch
 * @copyright  2013 Fredrik Wallner, 2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for fwstructmatch questions.
 *
 * @copyright  2013 Fredrik Wallner, 2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_fwstructmatch_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();
        $currentanswer = $qa->get_last_qt_var('answer');

        $inputname = $qa->get_qt_field_name('answer');
        $inputattributes = array(
            // 'type' => 'text',
            // 'name' => $inputname,
            // 'value' => $currentanswer,
            'id' => $inputname,
            // 'size' => 80,
        );
        $toreplaceid = strtr($inputname, ":", "_") . "_cwc";

        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
        }

        $feedbackimg = '';
        if ($options->correctness) {
            $answer = $question->get_matching_answer(array('answer' => $currentanswer));
            if ($answer) {
                $fraction = $answer->fraction;
            } else {
                $fraction = 0;
            }
            $inputattributes['class'] = $this->feedback_class($fraction);
            $feedbackimg = $this->feedback_image($fraction);
        }

        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
            $inputattributes['size'] = round(strlen($placeholder) * 1.1);
        }
        $input = html_writer::tag('div', html_writer::tag('canvas', "", array('id' => $toreplaceid)) . $this->hidden_fields($qa) . $feedbackimg, $inputattributes);

        if ($placeholder) {
            $inputinplace = html_writer::tag('label', get_string('answer'),
                    array('for' => $inputattributes['id'], 'class' => 'accesshide'));
            $inputinplace .= $input;
            $questiontext = substr_replace($questiontext, $inputinplace,
                    strpos($questiontext, $placeholder), strlen($placeholder));
        }

        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));

        if (!$placeholder) {
            $result .= html_writer::start_tag('div', array('class' => 'ablock'));
            $result .= html_writer::tag('label', get_string('answer', 'qtype_fwstructmatch',
                    html_writer::tag('span', $input, array('class' => 'answer'))),
                    array('for' => $inputattributes['id']));
            $result .= html_writer::end_tag('div');
        }

        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }

        $this->require_js($toreplaceid, $qa, $options->readonly, $options->correctness);

        return $result;
    }

    protected function require_js($toreplaceid, question_attempt $qa, $readonly, $correctness) {
        global $PAGE;
        $jsmodule = array(
            'name'     => 'qtype_fwstructmatch',
            'fullpath' => '/question/type/fwstructmatch/module.js',
            'requires' => array(),
            'strings' => array()
        );
        $topnode = 'div.que.fwstructmatch#q'.$qa->get_slot();
        $name = $toreplaceid . '_editor';
        // Maybe not the most elegant way, but at least it's working...
        $PAGE->requires->js('/question/type/fwstructmatch/cwc/ChemDoodleWeb-libs.js');
        $PAGE->requires->js('/question/type/fwstructmatch/cwc/ChemDoodleWeb.js');
        $PAGE->requires->js('/question/type/fwstructmatch/cwc/sketcher/jquery-ui-1.9.2.custom.min.js');
        $PAGE->requires->js('/question/type/fwstructmatch/cwc/sketcher/ChemDoodleWeb-sketcher.js');
        $PAGE->requires->js_init_call('M.qtype_fwstructmatch.insert_cwc',
                                      array($toreplaceid,
                                            $name,
                                            $topnode,
                                            $readonly),
                                      false,
                                      $jsmodule);
    }

    protected function hidden_fields(question_attempt $qa) {
        $question = $qa->get_question();

        $hiddenfieldshtml = '';
        $inputids = new stdClass();
        $responsefields = array_keys($question->get_expected_data());
        foreach ($responsefields as $responsefield) {
            $hiddenfieldshtml .= $this->hidden_field_for_qt_var($qa, $responsefield);
        }
        return $hiddenfieldshtml;
    }
    protected function hidden_field_for_qt_var(question_attempt $qa, $varname) {
        $value = $qa->get_last_qt_var($varname, '');
        $fieldname = $qa->get_qt_field_name($varname);
        $attributes = array('type' => 'hidden',
                            'id' => str_replace(':', '_', $fieldname),
                            'class' => $varname,
                            'name' => $fieldname,
                            'value' => $value);
        return html_writer::empty_tag('input', $attributes);
    }

    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();

        $answer = $question->get_matching_answer(array('answer' => $qa->get_last_qt_var('answer')));
        if (!$answer || !$answer->feedback) {
            return '';
        }

        return $question->format_text($answer->feedback, $answer->feedbackformat,
                $qa, 'question', 'answerfeedback', $answer->id);
    }

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();

        $answer = $question->get_matching_answer($question->get_correct_response());
        if (!$answer) {
            return '';
        }

        return get_string('correctansweris', 'qtype_fwstructmatch',
                s($question->clean_response($answer->answer)));
    }
}
