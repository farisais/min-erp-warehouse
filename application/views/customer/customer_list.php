<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>customer/get_customer_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'id_customer'},
                    { name: 'customer_code'},
                    { name: 'name'},
                    { name: 'adress'},
                    { name: 'city'},
                    { name: 'contact'},
                    { name: 'tlp'},
                    { name: 'handphone'},
                    { name: 'email'},
                    { name: 'customer_code'},
                ],
                id: 'id_customer',
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
                    { text: 'Code', dataField: 'customer_code', width: 100},
                    { text: 'Name', dataField: 'name', width: 200},
                    { text: 'Address', dataField: 'adress'},
                    { text: 'City', dataField: 'city', width: 100}, 
                    { text: 'Contact', dataField: 'contact', width: 100},
                    { text: 'Handphone', dataField: 'handphone', width: 100},
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
            item['paramValue'] = row.id_customer;
            param.push(item);        
            data_post['id_customer'] = row.id_customer;
            load_content_ajax(GetCurrentController(), 198 ,data_post, param);
            
        }
       
    });
                        
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 43, null, null);
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
        item['paramValue'] = row.id_customer;
        param.push(item);        
        data_post['id_customer'] = row.id_customer;

        load_content_ajax(GetCurrentController(), 44 ,data_post, param);
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
       if(confirm("Are you sure you want to delete menu : " + row.name))
        {
            var data_post = {};
            data_post['id_customer'] = row.id_customer;
            load_content_ajax(GetCurrentController(), 45 ,data_post);
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