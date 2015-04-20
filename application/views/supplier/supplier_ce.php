<script>
$(document).ready(function(){
     $("#telp").jqxMaskedInput({ height: 15, mask: '####-###-####'});
     $("#fax").jqxMaskedInput({ height: 15, mask: '####-###-####'});
     $("#npwp").jqxMaskedInput({ height: 15, mask: '###.###.####.#-###.###'});
     $("#no-rek").jqxMaskedInput({ height: 15, mask: '####################'});
     $('#form-container').jqxValidator({ rules: [
                { input: '#email', message: 'Invalid e-mail!', action: 'keyup', rule: 'email'}], theme: 'summer'
        });

});


function SaveData()
{
    var data_post = {};
    
    data_post['supplier_code'] = $("#supplier-code").val();
    data_post['name'] = $("#name").val();
    data_post['address'] = $("#address").val();
    data_post['city'] = $("#city").val();
    data_post['npwp'] = $("#npwp").val();
    data_post['contact'] = $("#contact").val();
    data_post['tlp'] = $("#telp").val();
    data_post['handphone'] = $("#handphone").val();
    data_post['fax'] = $("#fax").val();
    data_post['email'] = $("#email").val();
    data_post['rekening'] = $("#no-rek").val();
    data_post['is_edit'] = $("#is_edit").val(); 
    data_post['id_supplier'] = $("#id_supplier").val(); 
    
    load_content_ajax(GetCurrentController(), 30, data_post);
    
}
function DiscardData()
{
    load_content_ajax(GetCurrentController(), 26 , null);
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
<input type="hidden" id="id_supplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['id_supplier'] : '') ?>" />
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-center">
        <div>
    <table class="table-form">
        <tbody>
      <tr>
        <td style="vertical-align: top; width: 150px;">Kode Supplier: <br></td>
        <td style="vertical-align: top; width: 870px;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> style="text-transform:uppercase" maxlength="3" class="field" id="supplier-code" name="KodeSupplier" type="text" value="<?php echo (isset($is_edit) ? $data_edit[0]['supplier_code'] : '') ?>">
          <br></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Nama Supplier :<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="name" name="NamaSupplier" type="text" value="<?php echo (isset($is_edit) ? $data_edit[0]['name'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Alamat :<br></td>
        <td style="vertical-align: top; padding-bottom: 10px;"><textarea <?php if(isset($is_view)){ echo 'disabled=disabled';} ?>id="address" style="height: auto" class="field" cols="50" rows="10" type="text" name="Alamat"><?php echo (isset($is_edit) ? $data_edit[0]['address'] : '') ?></textarea></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Kota<br>
        </td>
          <td width="228" style="vertical-align: top; width: 228px;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="city" name="Kota" value="<?php echo (isset($is_edit) ? $data_edit[0]['city'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">NPWP:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="npwp" name="NPWP" value="<?php echo (isset($is_edit) ? $data_edit[0]['npwp'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Contact:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="contact" name="ContactSupplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['contact'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Telephone:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> style="width: 83%" class="field" id="telp" name="TelephoneSupplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['tlp'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Handphone:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="handphone" name="HandphoneSupplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['handphone'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Fax:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="fax" name="FaxSupplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['fax'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">Email:<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="email" placeholder="someone@mail.com" name="EmailSupplier" value="<?php echo (isset($is_edit) ? $data_edit[0]['email'] : '') ?>"></td>
      </tr>
      <tr>
        <td style="vertical-align: top;">No Rek.<br></td>
        <td style="vertical-align: top;"><input <?php if(isset($is_view)){ echo 'disabled="true"';} ?> class="field" id="no-rek" name="no-rek" value="<?php echo (isset($is_edit) ? $data_edit[0]['rekening'] : '') ?>"></textarea></td>
      </tr>
    </tbody>
            </table>
        </div>
    </div>
</div>