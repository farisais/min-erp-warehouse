<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>pl/get_pl_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'id_project_list'},
                    { name: 'so'},
                    { name: 'so_number'},
                    { name: 'customer_name'},
                    { name: 'project_list_number'},
                    { name: 'date'},
                    { name: 'note'},
                    { name: 'status'},
                    
                ],
                id: 'id_pl',
                url: url,
                root: 'data'
            };
             var cellclass = function (row, columnfield, value) 
            {
                if (value == 'submit') {
                    return 'green';
                }
            }
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
                    { text: 'Project List Number', dataField: 'project_list_number', width: 200},
                    { text: 'Sales Order', dataField: 'so_number', width: 200},
                    { text: 'Customer', dataField: 'customer_name', width: 200},
                    { text: 'Status', dataField: 'status', cellclassname: cellclass},
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
            item['paramValue'] = row.id_project_list;
            param.push(item);        
            data_post['id_project_list'] = row.id_project_list;
            load_content_ajax(GetCurrentController(), 204 ,data_post, param);
            
        }
       
        });
    
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 113, null, null);
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
        item['paramValue'] = row.id_project_list;
        param.push(item);        
        data_post['id_project_list'] = row.id_project_list;
        load_content_ajax(GetCurrentController(), 114 ,data_post, param);
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
       if(confirm("Are you sure you want to void Project List : " + row.project_list_number))
        {
            var data_post = {};
            data_post['id_project_list'] = row.id_project_list;
            load_content_ajax(GetCurrentController(), 115 ,data_post);
        }
    }
    else
    {
        alert('Select menu you want to delete first');
    }
}

</script>
<style>
.green {
    color: green;
}
</style>
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">
    <div class="form-full">
        <div id="jqxgrid">
        </div>
    </div>
</div>