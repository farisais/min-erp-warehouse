<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>mr/get_mr_list";
         var source =
            {
                datatype: "json",
                datafields:
                [   
                    { name: 'id_mr'},
                    { name: 'mr_number'},
                    { name: 'project_list'},
                    { name: 'project_list_number'},
                    { name: 'date', type: 'date'},
                    { name: 'status'},
                       
                ],
                id: 'id_mr',
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
                    { text: 'Material Rquest No.', dataField: 'mr_number', width: 300},
                    { text: 'Date', dataField: 'date', cellsformat: 'dd/MM/yyyy',filtertype: 'date'},
                    { text: 'Project List No.', dataField: 'project_list_number'},
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
            item['paramValue'] = row.id_mr;
            param.push(item);        
            data_post['id_mr'] = row.id_mr;
            load_content_ajax(GetCurrentController(), 200 ,data_post, param);
            
        }
       
    });
            
                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 78, null, null);
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
        item['paramValue'] = row.id_mr;
        param.push(item);        
        data_post['id_mr'] = row.id_mr;
        load_content_ajax(GetCurrentController(), 79 ,data_post, param);
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
       if(confirm("Are you sure you want to Void Material Request : " + row.mr_number))
        {
            var data_post = {};
            data_post['id_mr'] = row.id_mr;
            //alert(JSON.stringify(data_post))
            load_content_ajax(GetCurrentController(), 80 ,data_post);
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