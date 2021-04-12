<div id="tableUsers_length" class="dataTables_length border floatRight form-group tableSearch inline" style="margin-top:0px;margin-right: -25px;">
    <label style="width:auto">
        Mostrar
        <select id="pagination" class="form-control selectSearch"  style="width:75px; display:inline">
            <option value="10" @if($items == 10) selected @endif >10</option>
            <option value="25" @if($items == 25) selected @endif >25</option>
            <option value="50" @if($items == 50) selected @endif >50</option>
            <option value="100" @if($items == 100) selected @endif >100</option>
        </select>
        Registros
    </label>
</div>