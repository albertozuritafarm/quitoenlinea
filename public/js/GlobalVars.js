/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var ROUTE = '';

$( document ).ajaxError(function( event, jqxhr, settings, exception ) {
    if ( jqxhr.status== 401 ) {
      window.location.href = ROUTE + "/logout";
    }
});
