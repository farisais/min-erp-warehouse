<script>
$(document).ready(function(){
    
});

function SaveData()
{
    var data_post = {};
    
    data_post['bank_name'] = $("#bank_name").val();
    data_post['bank_account'] = $("#bank_account").val();
    data_post['bank_user'] = $("#bank_user").val();
    data_post['is_edit'] = $("#is_edit").val(); 
    data_post['id_bank'] = $("#id_bank").val(); 
    
    load_content_ajax(GetCurrentController(), 98, data_post);
    
}
function DiscardData()
{
    load_content_ajax(GetCurrentController(), 94 , null);
}

</script>
<script>
$(document).ready(function(){
     
});
</script>
<style>
.table-form
{
    margin: 30px;
    width: 100%;
}

.table-form tr td
{
    
}

.table-form tr
{
    height: 35px;
}

.field 
{ 
    border: 1px solid #c4c4c4; 
    height: 15px; 
    width: 80%; 
    font-size: 11px; 
    padding: 4px 4px 4px 4px; 
    border-radius: 4px; 

} 

select.field
{
    height: 25px;
    width: calc(80% + 8px); 
    
}
 
.field:focus 
{ 
    outline: none; 
    border: 1px solid #7bc1f7; 
} 

.label
{
    font-size: 11pt;
    width: 160px;
    padding-right: 20px;
    font: -webkit-small-control;
}

.column-input
{

}


</style>
<input type="hidden" id="prevent-interruption" value="true" />
<input type="hidden" id="is_edit" value="<?php echo (isset($is_edit) ? 'true' : 'false') ?>" />
<input type="hidden" id="id_bank" value="<?php echo (isset($is_edit) ? $data_edit[0]['id_bank'] : '') ?>" />
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-center">
        <div>
            <table class="table-form">
                <tr>
                    <td class="label">
                        Bank Name
                    </td>
                    <td class="column-input">
                        <input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" type="text" id="bank_name" name="bank_name" value="<?php echo (isset($is_edit) ? $data_edit[0]['bank_name'] : '') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Payee Name
                    </td>
                    <td class="column-input">
                        <input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" type="text" id="bank_user" name="bank_user" value="<?php echo (isset($is_edit) ? $data_edit[0]['bank_user'] : '') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Bank Account
                    </td>
                    <td class="column-input">
                        <input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> maxlength="20" class="field" type="text" id="bank_account" name="bank_account" value="<?php echo (isset($is_edit) ? $data_edit[0]['bank_account'] : '') ?>"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>