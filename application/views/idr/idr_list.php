<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>idr/get_idr_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'idr_number'},
                    { name: 'so'},
                    { name: 'so_number'},
                    { name: 'date'},
                    { name: 'status'},
                    { name: 'id_internal_delivery_return'},
                       
                ],
                id: 'id_internal_delivery_return',
                url: url,
                root: 'data'
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#jqxgrid").jqxGrid(
            {
                theme: $("#theme").val(),
                width: '100%',
                height: '100%',
                source: dataAdapter,
                groupable: true,
                columnsresize: true,
                autoshowloadelement: false,                                                                                
                filterable: true,
                showfilterrow: true,
                sortable: true,
                autoshowfiltericon: true,
                columns: [
                    { text: 'Internal Delivery Return No.', dataField: 'idr_number'},
                    { text: 'Sales Order', dataField: 'so_number'},
                    { text: 'Status', dataField: 'status', width: 100},
                                       
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
            item['paramValue'] = row.id_internal_delivery_return;
            param.push(item);        
            data_post['id_internal_delivery_return'] = row.id_internal_delivery_return;
            load_content_ajax(GetCurrentController(), 203 ,data_post, param);
            
        }
       
        });
            
                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 158, null, null);
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
        item['paramValue'] = row.id_internal_delivery_return;
        param.push(item);        
        data_post['id_internal_delivery'] = row.id_internal_delivery_return;
        load_content_ajax(GetCurrentController(), 185 ,data_post, param);
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
       if(confirm("Are you sure you want to void Final Assembling : " + row.idr_number))
        {
            var data_post = {};
            data_post['id_internal_delivery_return'] = row.id_internal_delivery_return;
            load_content_ajax(GetCurrentController(), 187 ,data_post);
        }
    }
    else
    {
        alert('Select menu you want to delete first');
    }
}

</script>
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-full">
        <div id="jqxgrid">
        </div>
    </div>
</div>