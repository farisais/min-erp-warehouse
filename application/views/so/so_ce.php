<script type="text/javascript" src="<?php echo base_url() ?>jqwidgets/globalization/globalize.js"></script>
<script>
$(document).ready(function(){  
    $("#so-date").jqxDateTimeInput({width: '250px', height: '25px'<?php if(isset($is_view)){ echo ',disabled: true';} ?>});
    $("#select-product-popup").jqxWindow({
        width: 600, height: 500, resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
    });
    
    <?php 
    if(isset($is_edit))
    {?>
    $("#so-date").jqxDateTimeInput('val', <?php echo "'" . date( 'd/m/Y' , strtotime($data_edit[0]['date'])) . "'" ?>); 
    <?php 
    }
    ?>
    $("#clear-so-date").click(function(){
        <?php 
        if(!isset($is_view))
        {?>
        $("#so-date").val(null);
        <?php
        }
        ?>
    });

    //=================================================================================
    //
    //   Customer Input
    //
    //=================================================================================
    
    var urlSupplier = "<?php echo base_url() ;?>customer/get_customer_list";
    var sourceSupplier =
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
                    { name: 'email'},
        ],
        id: 'id_customer',
        url: urlSupplier ,
        root: 'data'
    };
    var dataAdapterSupplier = new $.jqx.dataAdapter(sourceSupplier);
    
    
    $("#customer-name").jqxInput({ source: dataAdapterSupplier, displayMember: "name", valueMember: "id_customer", height: 23, disabled: "true"});
    
    <?php 
    if(isset($is_edit))
    {?>
    $("#customer-name").jqxInput('val', {label: '<?php echo $data_edit[0]['customer_name'] ?>', value: '<?php echo $data_edit[0]['customer']?>'});
    <?php 
    }
    ?>
    $("#select-customer-popup").jqxWindow({
        width: 600, height: 500, resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
    });
    
    $("#select-customer-grid").jqxGrid(
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
            { text: 'Code', dataField: 'customer_code', width: 75},
            { text: 'Name', dataField: 'name'},
            { text: 'City', dataField: 'city'},
            { text: 'Telephone', dataField: 'tlp', width: 150}                                      
        ]
    });
    
    $("#customer-select").click(function(){
        <?php 
        if(!isset($is_view))
        {?>
        $("#select-customer-popup").jqxWindow('open');
        <?php
        }
        ?>
    });
    
    $('#select-customer-grid').on('rowdoubleclick', function (event) 
    {
        var args = event.args;
        var data = $('#select-customer-grid').jqxGrid('getrowdata', args.rowindex);
        $('#customer-name').jqxInput('val', {label: data.name, value: data.id_customer});
        $("#select-customer-popup").jqxWindow('close');
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
    //   So Product Grid
    //
    //=================================================================================
    $("#so-product-grid").on("bindingcomplete", function(event){
        recalculateValue(dataAdapter);
    });
    
    var url = "<?php if(isset($is_edit)){?><?php echo base_url()?>so/get_so_product_list?id=<?php echo $data_edit[0]['id_so']; ?> <?php }?>";
    var source =
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
            { name: 'unit_name', value: 'unit', values: { source: unitAdapter.records, value: 'id_unit_measure', name: 'name' } },
            { name: 'unit'},            
            { name: 'category_name'},
            { name: 'qty', type: 'number'},
            { name: 'unit_price', type: 'number'},
            { name: 'total_price', type: 'number'}
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
        height: 450,
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
                <?php 
                if(!isset($is_view))
                {?>
                $("#select-product-popup").jqxWindow('open');
                <?php
                }
                ?>
            });
            $("#remove-product").click(function(){
                var selectedrowindex = $("#so-product-grid").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var id = $("#so-product-grid").jqxGrid('getrowid', selectedrowindex);
                    var commit1 = $("#so-product-grid").jqxGrid('deleterow', id);
                }
                
            });
        },
        columns: [
            { text: 'Product Code', dataField: 'product_code'},
            { text: 'Product', dataField: 'product_name'},
            { text: 'Unit', dataField: 'unit', displayfield: 'unit_name', columntype: 'dropdownlist',
                createeditor: function (row, value, editor) {
                    editor.jqxDropDownList({ source: unitAdapter, displayMember: 'name', valueMember: 'id_unit_measure' });
                }},
            { text: 'Quantity', dataField: 'qty', cellsformat: 'd2'}, 
            { text: 'Unit Price', dataField: 'unit_price',cellsformat: 'c2',
                cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
    
                    var culture = {};
                    culture.currencysymbol = "Rp. ";
                    culture.currencysymbolposition = "before";
                    culture.decimalseparator = '.';
                    culture.thousandsseparator = ',';
                    return "<div style='margin: 4px;' class='jqx-right-align'>" + dataAdapter.formatNumber(value, "c2", culture) + "</div>";
                },
                validation: function (cell, value) {
                    if (value < 0) {
                      return { result: false, message: "Price should be greate than 0" };
                    }
                    return true;
                }
            },
            { text: 'Total Price', dataField: 'total_price', 
                cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
                    var total = parseFloat(rowdata.unit_price) * parseFloat(rowdata.qty);
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
    
    $("#so-product-grid").on('cellvaluechanged', function (event) 
    {
        recalculateValue(dataAdapter);
    });
    
    $("#use-tax").click(function(){
        recalculateValue(dataAdapter);
    });

    $("#so-product-grid").jqxGrid('setcolumnproperty', 'product_name', 'editable', false);
    $("#so-product-grid").jqxGrid('setcolumnproperty', 'product_code', 'editable', false);
    $("#so-product-grid").jqxGrid('setcolumnproperty', 'total_price', 'editable', false);
    
    //=================================================================================
    //
    //   Select Product Grid
    //
    //=================================================================================
    
    var url_select_product = "<?php echo base_url() ;?>product/get_product_list";
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
            { name: 'unit_price', type: 'number'},
            { name: 'total_price', type: 'number'}
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
        data['unit_price'] = 0;
        data['total_price'] = 0;
        var commit0 = $("#so-product-grid").jqxGrid('addrow', null, data);
        $("#select-product-popup").jqxWindow('close');
    });
    
     $('#so-product-grid').on('rowdoubleclick', function (event) 
    {
        var args = event.args;
        var data = $('#so-product-grid').jqxGrid('getrowdata', args.rowindex);

        //alert(JSON.stringify(data));
    });
    //=================================================================================
    //
    //   discount
    //
    //=================================================================================
    
    var source = [
                    {name: "Amount", value: "amount"},
                    {name: "Percentage", value: "percentage"}
		        ];
                // Create a jqxDropDownList
                $("#discount-select").jqxDropDownList({ source: source, valueMember: 'value', displayMember: 'name',selectedIndex: 0, width: '200px', height: '25px'});
          
                $("#discount-value").jqxNumberInput({ width: '150px', height: '25px'});
                
                <?php 
                if(isset($is_edit))
                {?>
                    $("#discount-select").jqxDropDownList('val', '<?php echo $data_edit[0]['discount_type'] ?>');
                    $("#discount-value").jqxNumberInput('val', <?php echo $data_edit[0]['discount_value'] ?>);
                    recalculateValue(dataAdapter);
                <?php    
                }
                ?>
                
                $("#discount-value").on('change', function(){
                    recalculateValue(dataAdapter);
                });
                
                $("#discount-select").on('change', function(){
                    if($("#discount-select").val() == 'percentage')
                    {
                        $("#discount-value").jqxNumberInput({digits: 2});
                    }
                    else
                    {
                        $("#discount-value").jqxNumberInput({digits: 8});
                    }
                    $("#discount-value").val(0);
                    recalculateValue(dataAdapter);
                });
    //=================================================================================
    //
    //   SO Validate
    //
    //=================================================================================
    $("#so-validate").click(function(){  
        var data_post = {};
        <?php 
        if(isset($is_edit))
        {?>
        var param = [];
        var item = {};
        item['paramName'] = 'id';
        item['paramValue'] = <?php echo $data_edit[0]['id_so'] ?>;
        param.push(item);        
        data_post['is_edit'] = $("#is_edit").val(); 
        data_post['id_so'] = $("#id_so").val();
        load_content_ajax(GetCurrentController(), 130, data_post, param);
        <?php 
        }
        else
        {?>
        data_post['action_condition_identifier'] = 'validate';
        load_content_ajax(GetCurrentController(), 130, data_post);
        <?php
        }
        ?>
    });
    
    $("#invoice").click(function(){
        var data_post = {};
        data_post['id_so'] = $("#id_so").val();
        load_content_ajax('warehouse', 137, data_post);
    });
    
    $("#delivery-order").click(function(){
        var data_post = {};
        data_post['id_so'] = $("#id_so").val();
        load_content_ajax('warehouse', 84, data_post);
    });
                
   
});

function recalculateValue(dataAdapter)
{
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
    if($("#discount-select").val() == 'amount')
    {
        //alert("amount");
        amount = amount - $("#discount-value").val();
    }
    else
    {
         amount = amount * (1 - ($("#discount-value").val() / 100));
    }
    
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
}


function SaveData()
{
    var data_post = {};
    <?php 
    if(isset($is_edit) && $data_edit[0]['status'] != 'void' || !isset($is_edit))
    {?>
    data_post['date'] = $("#so-date").val('date').format('yyyy-mm-dd');
    data_post['note'] = $("#notes").html();
    data_post['po_cust'] = $('#po-customer').val();
    data_post['customer'] = $("#customer-name").val().value;
    data_post['sub_total'] = $("#subtotal-value").val();
    data_post['total_price'] = $("#total-value").val();
    data_post['tax'] = $("#tax-value").val();
    data_post['discount_type'] = $("#discount-select").val();
    data_post['discount_value'] = $("#discount-value").val();
    data_post['product_detail'] = $('#so-product-grid').jqxGrid('getrows');
    
    data_post['is_edit'] = $("#is_edit").val(); 
    data_post['id_so'] = $("#id_so").val(); 
    //alert(JSON.stringify(data_post));
    load_content_ajax(GetCurrentController(), 76, data_post);
    <?php   
    }
    ?>
}
function DiscardData()
{
    load_content_ajax('administrator', 72 , null);
}

</script>
<input type="hidden" id="prevent-interruption" value="true" />
<input type="hidden" id="is_edit" value="<?php echo (isset($is_edit) ? 'true' : 'false') ?>" />
<input type="hidden" id="id_so" value="<?php echo (isset($is_edit) ? $data_edit[0]['id_so'] : '') ?>" />
<div class="document-action">
    <?php 
    if(!isset($is_view))
    {?>
    <?php 
    if(isset($is_edit) && $data_edit[0]['status'] == 'draft')
    {
        if($data_edit[0]['status'] != 'void')
        {?>
        <button style="margin-left: 20px;" id="so-validate">Validate</button>
        <?php    
        }
    }
    ?>
    
    <?php 
    if(isset($is_edit) && $data_edit[0]['status'] == 'deliver')
    {
        if($data_edit[0]['status'] != 'void')
        {?>
        <button id="invoice">Invoice</button>
        <?php    
        }
    }
    ?>
    <?php
    }
    ?>
    
    
    
    <ul class="document-status">
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'draft' ? 'class="status-active"' : '') ?> >
            <span class="label">Draft</span>
            <span class="arrow">
                <span></span>
            </span>
        </li>
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'open' ? 'class="status-active"' : '') ?>>
            <span class="label">Open</span>
            <span class="arrow">
                <span></span>
            </span>
        </li>
        <li <?php echo (isset($is_edit) && $data_edit[0]['status'] == 'deliver' ? 'class="status-active"' : '') ?>>
            <span class="label">Deliver</span>
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
        <div><h1 style="font-size: 18pt; font-weight: bold;">Sales Order / <span><?php echo (isset($is_edit) ? $data_edit[0]['so_number'] : ''); ?></span></h1></div>
        <div>
            <table class="table-form">
                <tr>
                    <td>
                        <div class="label">
                            SO Date
                        </div>
                        <div class="column-input" colspan="2">
                            <div id="so-date"></div>
                        </div>
                    </td>
                    </tr>
                <tr>
                    <td>
                        <div class="label">
                            PO Customer
                        </div>
                        <div class="column-input" colspan="2">
                            <input style="display:inline; width: 70%; font: -webkit-small-control; padding-left: 5px;" class="field" type="text" id="po-customer" name="name" value="<?php echo (isset($is_edit) ? $data_edit[0]['po_cust'] : '') ?>"/>
                        </div>
                    </td>
                    <td>
                        <div class="label">
                            Customer
                        </div>
                        <div class="column-input" colspan="2">
                            <input style="display:inline; width: 70%" class="field" type="text" id="customer-name" name="name" value=""/>
                            <button id="customer-select">...</button>
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
                        <div id="so-product-grid"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <table style="float: right; text-align: right">
                            <tr>
                                <td></td>
                                <td>Untaxed Amount : </td>
                                <td style="width: 150px;"><div id="untaxed-amount">Rp. 0</div><input type="hidden" id="subtotal-value" value="<?php echo (isset($is_edit) ? $data_edit[0]['sub_total'] : '0') ?>"/></td>
                            </tr>
                            <tr>
                                <td><div id="discount-select"></div></td>
                                <td>Discount : </td>
                                <td style="width: 150px;"><div id="discount-value"></div><input type="hidden" id="discount" value="<?php echo (isset($is_edit) ? $data_edit[0]['sub_total'] : '0') ?>"/></td>
                            </tr>
                            <tr>
                                <td style="padding-right: 10px;"><!--<div id="tax-select">--></div></td>
                                <td><input type="checkbox" id="use-tax" style="display: inline-block;" <?php echo (isset($is_edit) && ($data_edit[0]['tax'] != null || $data_edit[0]['tax'] > 0) ? 'checked=true' : '') ?> />Taxes (10%) : </td>
                                <td><div id="tax-amount">Rp. 0</div><input type="hidden" id="tax-value" value="<?php echo (isset($is_edit) ? $data_edit[0]['tax'] : '0') ?>"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-top: solid thin black;">Total Amount : </td>
                                <td style="border-top: solid thin black;"><div id="total-amount">Rp. 0</div><input type="hidden" id="total-value" value="<?php echo (isset($is_edit) ? $data_edit[0]['total_price'] : '0') ?>"/></td>
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

<div id="select-customer-popup">
    <div>Select Customer</div>
    <div>
        <table class="table-form">
            <tr>
                <td style="width: 80%" colspan="2">
                    <div id="select-customer-grid"></div>
                </td>
            </tr>
        </table>
    </div>
</div>