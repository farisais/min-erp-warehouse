<script>
$(document).ready(function(){
     $("#tlp").jqxMaskedInput({ height: 15, mask: '####-####-####'});
     $("#fax").jqxMaskedInput({ height: 15, mask: '####-####-####'});
     $('#form-container').jqxValidator({ rules: [
                { input: '#email', message: 'Invalid e-mail!', action: 'keyup', rule: 'email'}], theme: 'summer'
        });
    
});

function SaveData()
{
    var data_post = {};
    
    data_post['name'] = $("#name").val();
    data_post['customer_code'] = $("#customer_code").val();
    data_post['adress'] = $("#adress").val();
    data_post['city'] = $("#city").val();
    data_post['contact'] = $("#contact").val();
    data_post['tlp'] = $("#tlp").val();
    data_post['handphone']=$("#handphone").val();
    data_post['fax'] = $("#fax").val();
    data_post['email'] = $("#email").val();
    data_post['is_edit'] = $("#is_edit").val(); 
    data_post['id_customer'] = $("#id_customer").val(); 
    
    load_content_ajax(GetCurrentController(), 46, data_post);
    
}
function DiscardData()
{
    load_content_ajax(GetCurrentController(), 41 , null);
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
<input type="hidden" id="id_customer" value="<?php echo (isset($is_edit) ? $data_edit[0]['id_customer'] : '') ?>" />
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-center">
        <div>
            <table class="table-form">
                <tbody>
            <tr>
      <td style="vertical-align: top; width: 150px"> Customer Code</td>
      <td style="vertical-align: top; width: 870px;"><input class="field" maxlength="5" type="text" name="customer_code" id="customer_code" value="<?php echo (isset($is_edit) ? $data_edit[0]['customer_code'] : '') ?>">
              </td>
            </tr>
    <tr>
      <td>Customer Name</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" name="name" type="text" id="name" size="40" value="<?php echo (isset($is_edit) ? $data_edit[0]['name'] : '') ?>">
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Address :<br></td>
        <td style="vertical-align: top; padding-bottom: 10px;"><textarea <?php if(isset($is_view)){ echo 'disabled=disabled';} ?> id="adress" style="height: auto" class="field" cols="50" rows="10" type="text" ><?php echo (isset($is_edit) ? $data_edit[0]['adress'] : '') ?></textarea></td>
    </tr>
    <tr>
      <td>Telephone</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> style="width: 83%" class="field" name="tlp" id="tlp" value="<?php echo (isset($is_edit) ? $data_edit[0]['tlp'] : '') ?>">
      </td>
    </tr>
     <tr>
      <td>Handphone</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?>  class="field" name="hanphone" id="handphone" value="<?php echo (isset($is_edit) ? $data_edit[0]['handphone'] : '') ?>">
      </td>
    </tr>
    <tr>
      <td>City</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" type="text" name="city" id="city" value="<?php echo (isset($is_edit) ? $data_edit[0]['city'] : '') ?>">
      </td>
    </tr>
    <tr>
      <td>Contact</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" type="text" name="contact" id="contact" value="<?php echo (isset($is_edit) ? $data_edit[0]['contact'] : '') ?>">
      </td>
    </tr>
    <tr>
      <td>Fax</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" name="fax" id="fax" value="<?php echo (isset($is_edit) ? $data_edit[0]['fax'] : '') ?>">
      </td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" placeholder="someone@mail.com" type="email" name="email" id="email" value="<?php echo (isset($is_edit) ? $data_edit[0]['email'] : '') ?>">
      </td>
    </tr>
                </tbody>
      </table>
        </div>
    </div>
</div>