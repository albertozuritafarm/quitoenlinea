<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function getAssets(request $request){
        $count = $request['count'];
        $add = $count+1;
        $assets = \App\bank_accounts_assets::all();
        $returnId = '<div class="form-group" id="formGroupAssetsId'.$add.'">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Actividad Economica Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="assetId_'.$add.'" name="assetId" class="form-control registerForm" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                            <option selected="true" value="">--Escoja Una---</option>';
        foreach($assets as $ass){
            $returnId .= '<option value="'.$ass->id.'">'.$ass->name.'</option>';
        }
        
        $returnId .= '
                                @endforeach
                            </select>
                        </div>';
        
        $returnValue = '<div class="form-group" id="formGroupAssetsValue'.$add.'">
                              <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Valor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                              <input type="text" class="form-control" id="assetValue_'.$add.'" name="assetValue" placeholder="Valor">
                            </div>';
                
        $returnData = [
            'id' => $returnId,
            'value' => $returnValue
        ];
        return $returnData;
    }
}
