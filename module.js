/*
The MIT License (MIT)

Copyright (c) 2013 Fredrik Wallner

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

/**
 * JavaScript to show editor and errors
 *
 * @since      2.0
 * @package    qtype_fwstructmatch
 * @copyright  2013 Fredrik Wallner
 * @license    MIT
 */

M.qtype_fwstructmatch={
    insert_cwc : function(Y, toreplaceid, name, topnode, readonly){
        if(readonly) {
            window[name] = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
        }
        else {
            window[name] = new ChemDoodle.SketcherCanvas(toreplaceid, 400, 300, {useServices:false, oneMolecule:true});
            // TODO: Make oneMolecule (and the check for empty canvas below) dependent on setting for question.
            var inputdiv = Y.one(topnode);
            inputdiv.ancestor('form').on('submit', function (){
            	var mol = window[name].getMolecule();
                if(mol.atoms.length == 1 && mol.atoms[0].label == 'C') {
                    Y.one(topnode+' input.answer').set('value', '');
                }
                else {
                    Y.one(topnode+' input.answer').set('value', ChemDoodle.writeMOL(mol));
                }
            }, this);
        }
        var lastmol = Y.one(topnode+' input.answer').get('value');
        if(lastmol.length > 0) {
            var cmcmol = ChemDoodle.readMOL(lastmol);
            window[name].loadMolecule(cmcmol);
        }
    },
    show_error : function (Y, topnode) {
        var errormessage = '';
        Y.one(topnode+ ' .ablock').insert(errormessage, 1);
    }
}
