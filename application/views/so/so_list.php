<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>so/get_so_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'so_number'},
                    { name: 'customer'},
                    { name: 'name'},
                    { name: 'po_cust'},
                    { name: 'date'},
                    { name: 'total_price'},
                    { name: 'tax'},
                    { name: 'sub_total'},
                    { name: 'note'},
                    { name: 'id_so'},
                    { name: 'status'}
                    
                ],
                id: 'id_so',
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
                    { text: 'Sales Order Number', dataField: 'so_number', width: 200},
                    { text: 'Customer', dataField: 'customer', displayfield: 'name'},
                    { text: 'Customer PO No.', dataField: 'po_cust', width: 200},
                    { text: 'Status', dataField: 'status'},
                                       
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
            item['paramValue'] = row.id_so;
            param.push(item);        
            data_post['id_so'] = row.id_so;
            load_content_ajax(GetCurrentController(), 207 ,data_post, param);
            
        }
       
        });
 

                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 73, null, null);
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
        item['paramValue'] = row.id_so;
        param.push(item);        
        data_post['id_so'] = row.id_so;
        load_content_ajax(GetCurrentController(), 74 ,data_post, param);
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
       if(confirm("Are you sure you want to void Sales Order : " + row.so_number))
        {
            var data_post = {};
            data_post['id_so'] = row.id_so;
            load_content_ajax(GetCurrentController(), 75 ,data_post);
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