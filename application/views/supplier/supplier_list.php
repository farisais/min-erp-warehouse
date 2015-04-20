<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>supplier/get_supplier_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'id_supplier'},
                    { name: 'supplier_code'},
                    { name: 'name'},
                    { name: 'address'},
                    { name: 'city'},
                    { name: 'contact'},
                    { name: 'tlp'},
                    { name: 'email'},
                ],
                id: 'id_supplier',
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
                    { text: 'Name', dataField: 'name'},
                    { text: 'Code', dataField: 'supplier_code'},
                    { text: 'Address', dataField: 'address'},
                    { text: 'City', dataField: 'city', width: 100}, 
                    { text: 'Contact', dataField: 'contact', width: 100},
                    { text: 'Call Number', dataField: 'tlp', width: 100},
                    { text: 'Email', dataField: 'email', width: 100}
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
            item['paramValue'] = row.id_supplier;
            param.push(item);        
            data_post['id_supplier'] = row.id_supplier;
            load_content_ajax(GetCurrentController(), 194 ,data_post, param);
            
        }
       
        });
                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 27, null, null);
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
        item['paramValue'] = row.id_supplier;
        param.push(item);        
        data_post['id_supplier'] = row.id_supplier;
        load_content_ajax(GetCurrentController(), 28 ,data_post, param);
    }
    else
    {
        alert('Select supplier you want to edit first');
    }                            
}

function DeleteData()
{
    var row = $('#jqxgrid').jqxGrid('getrowdata', parseInt($('#jqxgrid').jqxGrid('getselectedrowindexes')));
        
    if(row != null)
    {
       if(confirm("Are you sure you want to delete supplier : " + row.name))
        {
            var data_post = {};
            data_post['id_supplier'] = row.id_supplier;
            load_content_ajax(GetCurrentController(), 29 ,data_post);
        }
    }
    else
    {
        alert('Select supplier you want to delete first');
    }
}

</script>
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-full">
        <div id="jqxgrid">
        </div>
    </div>
</div>