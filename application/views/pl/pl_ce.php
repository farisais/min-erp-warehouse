<script type="text/javascript" src="<?php echo base_url() ?>jqwidgets/globalization/globalize.js"></script>
<script>
$(document).ready(function(){  
    //$("#so-date").jqxDateTimeInput({width: '250px', height: '25px'});
    $("#select-product-popup").jqxWindow({
        width: 600, height: 500, resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
    });
    
$("#jqxExpander").jqxExpander({ width: '100%', expanded: false});

    //=================================================================================
    //
    //   SO Input
    //
    //=================================================================================
    
    var urlSupplier = "<?php echo base_url() ;?>so/get_so_open_list";
    var sourceSupplier =
    {
        datatype: "json",
        datafields:
        [
                    { name: 'so_number'},
                    { name: 'customer'},
                    { name: 'po_cust'},
                    { name: 'po_date'},
                    { name: 'date'},
                    { name: 'status'},
                    { name: 'id_so'}
        ],
        id: 'id_so',
        url: urlSupplier ,
        root: 'data'
    };
    var dataAdapterSupplier = new $.jqx.dataAdapter(sourceSupplier);
    
    
    $("#so-number").jqxInput({ source: dataAdapterSupplier, displayMember: "SO Number", valueMember: "id_so", height: 23, disabled: "true"});
    
    <?php 
    if(isset($is_edit))
    {?>
    $("#so-number").jqxInput('val', {label: '<?php echo $data_edit[0]['so_number'] ?>', value: '<?php echo $data_edit[0]['so']?>'});
    <?php 
    }
    ?>
    $("#select-so-popup").jqxWindow({
        width: 600, height: 500, resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
    });
    
    $("#select-so-grid").jqxGrid(
    {
        theme: $("#theme").val(),
        width: '100%',
        height: 400,
        selectionmode : 'singlerow',
        source: dataAdapterSupplier,
        columnsresize: true,
        autoshowloadelement: false,                                                                                
        sortable: true,
        filterable: true,
        showfilterrow: true,
        autoshowfiltericon: true,
        columns: [
            { text: 'SO Number', dataField: 'so_number', width: 150},
            { text: 'Customer', dataField: 'customer'},
            { text: 'PO Number', dataField: 'po_cust', width: 150}                                      
        ]
    });
    
    $("#so-select").click(function(){
        <?php 
        if(!isset($is_view))
        {?>
        $("#select-so-popup").jqxWindow('open');
        <?php
        }
        ?>
    });
    
    $('#select-so-grid').on('rowdoubleclick', function (event) 
    {
        var args = event.args;
        var data = $('#select-so-grid').jqxGrid('getrowdata', args.rowindex);
        $('#so-number').jqxInput('val', {label: data.so_number, value: data.id_so});
        var url = "<?php echo base_url()?>so/get_so_product_list?id=" + data.id_so;
        var source =
        {
            datatype: "json",
            datafields:
            [
                { name: 'product'},
                { name: 'product_code'},
                { name: 'product_name'},
                { name: 'qty'},
                { name: 'uom'},
                { name: 'unit_name'}
            ],
            id: 'product',
            url: url ,
            root: 'data'
        };
        var dataAdapter = new $.jqx.dataAdapter(source);
        $("#so-product-grid").jqxGrid({source: dataAdapter});
        $("#select-so-popup").jqxWindow('close');
        
        //Populate Required Product from BOM
        $("#pl-product-grid").jqxGrid('clear');
        var amount = 0;
        var url_bom = "<?php echo base_url() ?>bom/get_bom_template_product_from_so?so=" + data.id_so;
        $.get(url_bom, function(data){
            data = JSON.parse(data);
            for(i=0;i<data['data'].length;i++)
            {
                //alert(data['data'][0]);
                var data_input = {};
                data_input['product_code'] = data['data'][i]['dbom_prod_code'];
                data_input['id_product'] = data['data'][i]['dbom_prod'];
                data_input['product'] = data['data'][i]['dbom_prod'];
                data_input['product_name'] = data['data'][i]['dbom_prod_name'];
                data_input['qty'] = data['data'][i]['total_req'];
                data_input['unit']= data['data'][i]['dbom_uom'];
                data_input['unit_name'] = data['data'][i]['dbom_uom_name'];
                data_input['unit_cogs'] = data['data'][i]['unit_cogs'];
                data_input['total_cogs'] = data['data'][i]['total_cogs'];
                var commit0 = $("#pl-product-grid").jqxGrid('addrow', null, data_input);
                //alert(data['data'][i]['total_cogs']);
                amount += data['data'][i]['total_cogs'];
                //alert(amount);
            }
            var culture = {};
            culture.currencysymbol = "Rp. ";
            culture.currencysymbolposition = "before";
            culture.decimalseparator = '.';
            culture.thousandsseparator = ',';
            $("#total-amount").html(dataAdapter.formatNumber((amount), "c2", culture));
            $("#total-value").val((amount));
    
            $("#pl-product-grid").jqxGrid('localizestrings', culture);
            
        });
        
        
        
    });
    
            
    //=================================================================================
    //
    //   Unit Measure Data
    //
    //=================================================================================
    
    var url_unit = "<?php echo base_url() ;?>unit_measure/get_unit_measure_list"
    var unitSource =
    {
         datatype: "json",
         datafields: [
             { name: 'id_unit_measure'},
             { name: 'name'}
         ],
        id: 'id_unit_measure',
        url: url_unit ,
        root: 'data'
    };
    
    var unitAdapter = new $.jqx.dataAdapter(unitSource, {
        autoBind: true
    });
    
    //=================================================================================
    //
    //   SO Product Grid
    //
    //=================================================================================
    $("#so-product-grid").on("bindingcomplete", function(event){
        var culture = {};
        culture.currencysymbol = "Rp. ";
        $("#so-product-grid").jqxGrid('localizestrings', culture);
        
        var rows = $("#so-product-grid").jqxGrid('getrows');
        var amount = 0;
        for(var i=0;i<rows.length;i++)
        {
            amount += rows[i].unit_price * rows[i].qty;
        }
        var culture = {};
        culture.currencysymbol = "Rp. ";
        culture.currencysymbolposition = "before";
        culture.decimalseparator = '.';
        culture.thousandsseparator = ',';
        $("#untaxed-amount").html(dataAdapter.formatNumber(amount, "c2", culture));
        var tax = 0;
        if($("#use-tax").is(":checked"))
        {
            tax = amount * 0.1;
        }
        $("#tax-amount").html(dataAdapter.formatNumber(tax, "c2", culture));
        $("#total-amount").html(dataAdapter.formatNumber((tax + amount), "c2", culture));
        
        $("#subtotal-value").val(amount);
        $("#tax-value").val(tax);
        $("#total-value").val((tax + amount));
        
        
    });
    
    var url = "<?php if(isset($is_edit)){?><?php echo base_url()?>so/get_so_product_list?id=<?php echo $data_edit[0]['so']; ?> <?php }?>";
    var source =
    {
        datatype: "json",
        datafields:
        [
            { name: 'id_so_finish_product'},
            { name: 'product_category'},
            { name: 'merk'},
            { name: 'product_code'},
            { name: 'product_name'},
            { name: 'unit_name'},
            { name: 'uom'},            
            { name: 'category_name'},
            { name: 'qty', type: 'number'},
        ],
        id: 'id_product',
        url: url ,
        root: 'data'
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    $("#so-product-grid").jqxGrid(
    {
        theme: $("#theme").val(),
        <?php if(isset($is_view)){ echo 'disabled: true,';} ?>
        width: '100%',
        height: 250,
        selectionmode : 'singlerow',
        source: dataAdapter,
        editable: false,
        columnsresize: true,
        autoshowloadelement: false,                                                                                
        sortable: true,
        autoshowfiltericon: true,
        rendertoolbar: function (toolbar) {
        },
        columns: [
            { text: 'Product Code', dataField: 'product_code', disabled: true},
            { text: 'Product', dataField: 'product', displayfield: 'product_name', disabled: true},
            { text: 'Unit', dataField: 'uom', displayfield: 'unit_name', disabled: true},
            { text: 'Quantity', dataField: 'qty', cellsformat: 'd2', disabled: true}
        ]
    });
    
    
    //=================================================================================
    //
    //   Project List Product Grid
    //
    //=================================================================================
    $("#pl-product-grid").on("bindingcomplete", function(event){
        recalculateValue(dataAdapter);
    });
    
    
    
    var url = "<?php if(isset($is_edit)){?><?php echo base_url()?>pl/get_pl_product_list_with_valuation?id=<?php echo $data_edit[0]['id_project_list']; ?> <?php }?>";
    var source =
    {
        datatype: "json",
        datafields:
        [
            { name: 'id_project_list_product'},
            { name: 'product_category'},
            { name: 'merk'},
            { name: 'product_code'},
            { name: 'product_name'},
            { name: 'unit_name'},
            { name: 'unit'},
            { name: 'id_product'},
            { name: 'product'},
            { name: 'category_name'},
            { name: 'qty', type: 'number'},
            { name: 'unit_price', type: 'number'},
            { name: 'total_price', type: 'number'},
            { name: 'unit_cogs', type: 'number'},
            { name: 'total_cogs', type: 'number'},
        ],
        id: 'id_product',
        url: url ,
        root: 'data'
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
     var culture = {};
    culture.currencysymbol = "Rp. ";

    $("#pl-product-grid").jqxGrid('localizestrings', culture);
    
    $("#pl-product-grid").jqxGrid(
    {
        theme: $("#theme").val(),
        <?php if(isset($is_view)){ echo 'disabled: true,';} ?>
        width: '100%',
        height: 250,
        selectionmode : 'singlerow',
        source: dataAdapter,
        editable: true,
        columnsresize: true,
        autoshowloadelement: false,                                                                                
        sortable: true,
        autoshowfiltericon: true,
        rendertoolbar: function (toolbar) {
            $("#add-product").click(function(){
                var offset = $("#remove-product").offset();
                $("#select-product-popup").jqxWindow({ position: { x: parseInt(offset.left) + $("#remove-product").width() + 20, y: parseInt(offset.top)} });
                $("#select-product-popup").jqxWindow('open');
            });
            $("#remove-product").click(function(){
                var selectedrowindex = $("#pl-product-grid").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var id = $("#pl-product-grid").jqxGrid('getrowid', selectedrowindex);
                    var commit1 = $("#pl-product-grid").jqxGrid('deleterow', id);
                }
                
            });
        },
        columns: [
            { text: 'Product Code', dataField: 'product_code'},
            { text: 'Product', dataField: 'product', displayfield: 'product_name'},
            { text: 'Unit', dataField: 'unit', displayfield: 'unit_name', columntype: 'dropdownlist',
                createeditor: function (row, value, editor) {
                    editor.jqxDropDownList({ source: unitAdapter, displayMember: 'name', valueMember: 'id_unit_measure' });
                }},
            { text: 'Quantity', dataField: 'qty', displayfield: 'qty', cellsformat: 'd2'},
            { text: 'Unit COGS', dataField: 'unit_cogs', cellsformat: 'c2',
                cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
    
                    var culture = {};
                    culture.currencysymbol = "Rp. ";
                    culture.currencysymbolposition = "before";
                    culture.decimalseparator = '.';
                    culture.thousandsseparator = ',';
                    return "<div style='margin: 4px;' class='jqx-right-align'>" + dataAdapter.formatNumber(value, "c2", culture) + "</div>";
                }
            },
            { text: 'Total COGS', dataField: 'total_cogs',
                cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
                    var total = parseFloat(rowdata.unit_cogs) * parseFloat(rowdata.qty);
                    var culture = {};
                    culture.currencysymbol = "Rp. ";
                    culture.currencysymbolposition = "before";
                    culture.decimalseparator = '.';
                    culture.thousandsseparator = ',';
                    return "<div style='margin: 4px;' class='jqx-right-align'>" + dataAdapter.formatNumber(total, "c2", culture) + "</div>";
                }
            
            }
        ]
    });
    
    $("#pl-product-grid").on("cellvaluechanged", function(event){
        var args = event.args;
        if(args.datafield == 'qty')
        {
            //alert('haha');
            var val = args.newvalue;
            var index = args.rowindex;
            var row = $("#pl-product-grid").jqxGrid('getrowdata', index);
            $("#pl-product-grid").jqxGrid('setcellvalue', index, "total_cogs", val * row['unit_cogs']);
        }
        recalculateValue(dataAdapter);
    });

    $("#pl-product-grid").jqxGrid('setcolumnproperty', 'total_cogs', 'editable', false);
    //=================================================================================
    //
    //   Select Product Grid
    //
    //=================================================================================
    
    var url_select_product = "<?php echo base_url() ;?>product/get_product_with_valuation";
    var source_select_product =
    {
        datatype: "json",
        datafields:
        [
            { name: 'id_product'},
            { name: 'product_category'},
            { name: 'merk'},
            { name: 'product_code'},
            { name: 'product_name'},
            { name: 'name'},
            { name: 'unit_name'},
            { name: 'unit'},            
            { name: 'category_name'},
            { name: 'qty', type: 'number'},
            { name: 'unit_cogs', type: 'number'},
            { name: 'total_cogs', type: 'number'},
        ],
        id: 'id_product',
        url: url_select_product ,
        root: 'data'
    };
    var dataAdapter_select_product = new $.jqx.dataAdapter(source_select_product);
    
    $("#select-product-grid").jqxGrid(
    {
        theme: $("#theme").val(),
        width: '100%',
        height: 400,
        selectionmode : 'singlerow',
        source: dataAdapter_select_product,
        columnsresize: true,
        autoshowloadelement: false,                                                                                
        sortable: true,
        filterable: true,
        showfilterrow: true,
        autoshowfiltericon: true,
        columns: [
            { text: 'Product Code', dataField: 'product_code', width: 150},
            { text: 'Name', dataField: 'product_name'},
            { text: 'Category', dataField: 'category_name', width: 150}, 
            { text: 'Merk', dataField: 'name', width: 100}                                        
        ]
    });
    
    $('#select-product-grid').on('rowdoubleclick', function (event) 
    {
        var args = event.args;
        var data = $('#select-product-grid').jqxGrid('getrowdata', args.rowindex);
        data['qty'] = 0;
        var commit0 = $("#pl-product-grid").jqxGrid('addrow', null, data);
        $("#select-product-popup").jqxWindow('close');
    });
    
     $('#pl-product-grid').on('rowdoubleclick', function (event) 
    {
        var args = event.args;
        var data = $('#pl-product-grid').jqxGrid('getrowdata', args.rowindex);

        //alert(JSON.stringify(data));
    });
    
    <?php
    if(!$class->has_access_to_action(190))
    {?>
     $('#pl-product-grid').jqxGrid('hidecolumn', 'unit_cogs');
     $('#pl-product-grid').jqxGrid('hidecolumn', 'total_cogs');
    <?php  
    }
    ?>
    //=================================================================================
    //
    //   Project List Validate
    //
    //=================================================================================
    $("#pl-validate").click(function(){  
        var data_post = {};
        <?php 
         if(isset($is_edit) && $data_edit[0]['status'] == 'draft')
        {?>
        var param = [];
        var item = {};
        item['paramName'] = 'id';
        item['paramValue'] = <?php echo $data_edit[0]['id_project_list'] ?>;
        param.push(item);        
        load_content_ajax(GetCurrentController(), 151, data_post, param);
        <?php 
        }
        else
        {?>
         data_post['project_list_number'] = $('#pl-number').val();
        data_post['so'] = $("#so-number").val().value;
        data_post['product_detail'] = $('#pl-product-grid').jqxGrid('getrows');
        
        data_post['is_edit'] = $("#is_edit").val(); 
        data_post['id_project_list'] = $("#id_project_list").val(); 
        data_post['action_condition_identifier'] = 'validate_pl';
        load_content_ajax(GetCurrentController(), 116, data_post);
        <?php
        }
        ?>
    });
                
   
});

function recalculateValue(dataAdapter)
{
    var rows = $("#pl-product-grid").jqxGrid('getrows');
    //alert(JSON.stringify(rows));
    var amount = 0;
    for(var i=0;i<rows.length;i++)
    {
        amount += rows[i].total_cogs;
    }
    //alert(amount);
    var culture = {};
    culture.currencysymbol = "Rp. ";
    culture.currencysymbolposition = "before";
    culture.decimalseparator = '.';
    culture.thousandsseparator = ',';
    
    $("#total-amount").html(dataAdapter.formatNumber((amount), "c2", culture));
    $("#total-value").val((amount));
}

function SaveData()
{
    var data_post = {};
    <?php 
    if(isset($is_edit) && $data_edit[0]['status'] != 'void' )
    {?>
    data_post['project_list_number'] = 
    data_post['so'] = $("#so-number").val().value;
    data_post['product_detail'] = $('#pl-product-grid').jqxGrid('getrows');
    
    data_post['is_edit'] = $("#is_edit").val(); 
    data_post['id_project_list'] = $("#id_project_list").val(); 
    //alert(JSON.stringify(data_post));
    load_content_ajax(GetCurrentController(), 116, data_post);
    <?php   
    }
    ?>
}
function DiscardData()
{
    load_content_ajax(GetCurrentController(), 112 , null);
}

</script>
<input type="hidden" id="prevent-interruption" value="true" />
<input type="hidden" id="is_edit" value="<?php echo (isset($is_edit) ? 'true' : 'false') ?>" />
<input type="hidden" id="id_project_list" value="<?php echo (isset($is_edit) ? $data_edit[0]['id_project_list'] : '') ?>" />
<div class="document-action">
    <?php 
    if(isset($is_edit) && $data_edit[0]['status'] != 'submit')
    {
        if($data_edit[0]['status'] != 'void')
        {?>
        <button style="margin-left: 20px;" id="pl-validate">Validate</button>
        <?php    
        }
    }
    ?>
    
    <ul class="document-status">
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'draft' ? 'class="status-active"' : '') ?> >
            <span class="label">Draft</span>
            <span class="arrow">
                <span></span>
            </span>
        </li>
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'submit' ? 'class="status-active"' : '') ?>>
            <span class="label">Submit</span>
            <span class="arrow">
                <span></span>
            </span>
        </li>
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'close' ? 'class="status-active"' : '') ?>>
            <span class="label">Close</span>
            <span class="arrow">
                <span></span>
            </span>
        </li>
    </ul>
    
</div>
<div id='form-container' style="font-size: 13px; font-family: Arial, Helvetica, Tahoma">

    <div class="form-center" style="padding: 30px;">
        <div><h1 style="font-size: 18pt; font-weight: bold;">Project List / <span><?php echo (isset($is_edit) ? $data_edit[0]['project_list_number'] : ''); ?></span></h1></div>
        <div>
            <table class="table-form">
                <tr>
                    <td>
                        <div class="label">
                            SO Number
                        </div>
                        <div class="column-input" colspan="2">
                            <input style="display:inline; width: 70%" class="field" type="text" id="so-number" name="name" value=""/>
                            <button id="so-select">...</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 80%" colspan="2">
                        <div id='jqxExpander'>
                            <div>
                                Sales Order Product Detail
                            </div>
                            <div>
                                <div id="so-product-grid"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">                       
                         <div class="row-color" style="width: 100%;">
                            <button style="width: 30px;" id="add-product">+</button>
                            <button style="width: 30px;" id="remove-product">-</button>
                            <div style="display: inline;"><span>Add / Remove Product</span></div>
                        </div>
                    </td>
                </tr>
                 <tr>
                    <td style="width: 80%;" colspan="2">
                        <div id="pl-product-grid"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <table style="float: right; text-align: right">
                            <tr>
                                <td></td>
                                <td style="border-top: solid thin black;">Total Cost : </td>
                                <td style="border-top: solid thin black;"><div id="total-amount">Rp. 0</div><input type="hidden" id="total-value" value=""/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 80%;padding-top: 20px;" colspan="2">
                        <div class="label">
                            Notes
                        </div>
                        <textarea class="field" id="notes" cols="10" rows="20" style="height: 50px;"><?php echo (isset($is_edit) ? $data_edit[0]['note'] : '') ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div id="select-product-popup">
    <div>Select Product</div>
    <div>
        <table class="table-form">
            <tr>
                <td style="width: 80%" colspan="2">
                    <div id="select-product-grid"></div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div id="select-so-popup">
    <div>Select Sales Order</div>
    <div>
        <table class="table-form">
            <tr>
                <td style="width: 80%" colspan="2">
                    <div id="select-so-grid"></div>
                </td>
            </tr>
        </table>
    </div>
</div>