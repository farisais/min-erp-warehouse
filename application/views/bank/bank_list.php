<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>bank/get_bank_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'id_bank'},
                    { name: 'bank_name'},
                    { name: 'bank_account'},
                    { name: 'bank_user'}
                ],
                id: 'id_bank',
                url: url,
                root: 'data'
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                theme: $("#theme").val(),
                width: '100%',
                height: '95%',
                source: dataAdapter,
                groupable: true,
                columnsresize: true,
                autoshowloadelement: false,                                                                                
                filterable: true,
                showfilterrow: true,
                sortable: true,
                autoshowfiltericon: true,
                columns: [
                    { text: 'Bank Name', dataField: 'bank_name',width: 200},
                    { text: 'User', dataField: 'bank_user'},
                    { text: 'Bank Account', dataField: 'bank_account', width: 150}
                ]
            });
            
        $("#jqxgrid").on("rowdoubleclick", function(event){
        var row = $('#jqxgrid').jqxGrid('getrowdata', event.args.rowindex);
        
        if(row != null)
        {
            var data_post = {};
            var param = [];
            var item = {};
            item['paramName'] = 'id';
            item['paramValue'] = row.id_bank;
            param.push(item);        
            data_post['id_bank'] = row.id_bank;
            load_content_ajax(GetCurrentController(), 196 ,data_post, param);
            
        }
       
    });
                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 95, null, null);
}

function EditData()
{
    var row = $('#jqxgrid').jqxGrid('getrowdata', parseInt($('#jqxgrid').jqxGrid('getselectedrowindexes')));
    if(row != null)
    {
        var data_post = {};
        var param = [];
        var item = {};
        item['paramName'] = 'id';
        item['paramValue'] = row.id_bank;
        param.push(item);        
        data_post['id_bank'] = row.id_bank;
        load_content_ajax(GetCurrentController(), 96 ,data_post, param);
    }
    else
    {
        alert('Select menu you want to edit first');
    }                            
}

function DeleteData()
{
    var row = $('#jqxgrid').jqxGrid('getrowdata', parseInt($('#jqxgrid').jqxGrid('getselectedrowindexes')));
        
    if(row != null)
    {
       if(confirm("Are you sure you want to delete bank : " + row.bank))
        {
            var data_post = {};
            data_post['id_bank'] = row.id_bank;
            load_content_ajax(GetCurrentController(), 97 ,data_post);
        }
    }
    else
    {
        alert('Select bank you want to delete first');
    }
}

</script>
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-full">
        <div id="jqxgrid">
        </div>
    </div>
</div>