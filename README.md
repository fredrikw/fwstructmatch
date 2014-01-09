fwstructmatch
=============

Plugin to Moodle to allow for question answered with chemical structure.

By installing this into the moodle/question/type folder you will get a new question type that is answered by drawing 
a structure in the ChemDoodle Web Sketcher. The drawing is converted to InChI and compared to the given answer(s).
The teacher can give multiple correct or semi-correct answers.

The InChI conversion is performed with OpenBabel (http://www.openbabel.org) that needs to be installed (in /usr/local/bin/obabel
or else the code needs amending in question.php). 
