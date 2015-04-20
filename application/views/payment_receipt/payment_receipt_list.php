<script>
 $(document).ready(function () {
        var url = "<?php echo base_url() ;?>payment_receipt/get_payment_receipt_list";
         var source =
            {
                datatype: "json",
                datafields:
                [
                    { name: 'id_payment_receipt'},
                    { name: 'po'},
                    { name: 'po_number'},
                    { name: 'payment_date', type: 'date'},
                    { name: 'payment_receipt_number'},
                    { name: 'status'},
                    { name: 'total_price', type: 'number'},
                    { name: 'total_payment', type: 'number'},
                    
                ],
                id: 'id_payment_receipt',
                url: url,
                root: 'data'
            };
            var cellclass = function (row, columnfield, value) 
            {
                if (value == 'close') {
                    return 'green';
                }
            }
            var dataAdapter = new $.jqx.dataAdapter(source);
            $('#jqxgrid').jqxGrid(
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
                    { text: 'Payment Receipt', dataField: 'payment_receipt_number', width: 200},
                    { text: 'Purchase Order', dataField: 'po_number', width: 200},
                    { text: 'Date', dataField: 'payment_date', cellsformat: 'dd/MM/yyyy',filtertype: 'date'},
                    { text: 'Total Price', dataField: 'total_price', width: 200, cellsformat: 'c2'},
                    { text: 'Total Payment', dataField: 'total_payment', width: 200, cellsformat: 'c2'},
                    { text: 'Status', dataField: 'status', width: 100, cellclassname: cellclass}
                    
                ]
            });
            
            $("#jqxgrid").on("bindingcomplete", function (event) {
                var localizationobj = {};
                localizationobj.currencysymbol = "Rp. ";
                $("#jqxgrid").jqxGrid('localizestrings', localizationobj); 
            }); 
            
        $("#jqxgrid").on("rowdoubleclick", function(event){
        var row = $('#jqxgrid').jqxGrid('getrowdata', event.args.rowindex);
        
        if(row != null)
        {
            var data_post = {};
            var param = [];
            var item = {};
            item['paramName'] = 'id';
            item['paramValue'] = row.id_payment_receipt;
            param.push(item);        
            data_post['id_payment_receipt'] = row.id_payment_receipt;
            load_content_ajax(GetCurrentController(), 206 ,data_post, param);
            
        }
       
        });
            
        });  
</script>
<script>
function CreateData()
{
    load_content_ajax(GetCurrentController(), 101, null, null);
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
        item['paramValue'] = row.id_payment_receipt;
        param.push(item);        
        data_post['id_payment_receipt'] = row.id_payment_receipt;
        load_content_ajax(GetCurrentController(), 102 ,data_post, param);
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
       if(confirm("Are you sure you want to void Payment Receipt : " + row.payment_receipt_number))
        {
            var data_post = {};
            data_post['id_payment_receipt'] = row.id_payment_receipt;
            load_content_ajax(GetCurrentController(), 104 ,data_post);
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