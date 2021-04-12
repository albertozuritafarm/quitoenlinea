/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//First Step Btn Next
    document.getElementById("firstStepBtnNext").onclick = function () {
        nextStep("firstStep", "secondStep");
    };
//Second Step Btn Back
    document.getElementById("secondStepBtnBack").onclick = function () {
        nextStep("secondStep", "firstStep");
    };
});
function nextStep(div1, div2)  { //div1: Div to show - div2: div to hide
    event.preventDefault();
    var div = document.getElementById(div1);
    $(div).addClass('hidden');
    var div = document.getElementById(div2);
    $(div).removeClass('hidden');

    var wizard = document.getElementById(div1 + "Wizard");
    $(wizard).removeClass('wizard_activo');
    $(wizard).addClass('wizard_inactivo');
    var wizard = document.getElementById(div2 + "Wizard");
    $(wizard).removeClass('wizard_inactivo');
    $(wizard).addClass('wizard_activo');
}