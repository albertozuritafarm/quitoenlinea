<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PassivesController extends Controller
{
    public function getPassives(request $request){
        $count = $request['count'];
        $add = $count+1;
        $assets = \App\bank_accounts_passives::all();
        $returnId = '<div class="form-group" id="formGroupPassivesId'.$add.'">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Actividad Economica Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="passiveId_'.$add.'" name="passiveId" class="form-control registerForm" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                            <option selected="true" value="">--Escoja Una---</option>';
        foreach($assets as $ass){
            $returnId .= '<option value="'.$ass->id.'">'.$ass->name.'</option>';
        }
        
        $returnId .= '
                                @endforeach
                            </select>
                        </div>';
        
        $returnValue = '<div class="form-group" id="formGroupPassivesValue'.$add.'">
                              <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Valor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                              <input type="text" class="form-control" id="passiveValue_'.$add.'" name="passiveValue" placeholder="Valor">
                            </div>';
                
        $returnData = [
            'id' => $returnId,
            'value' => $returnValue
        ];
        return $returnData;
    }
}
