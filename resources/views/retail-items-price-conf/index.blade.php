@extends('layouts.app')
@section('header-scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll { overflow-x: auto; overflow-y: auto; }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body { padding: 15px !important; }

        a.disabled { pointer-events: none; cursor: default; color: transparent; }

        .modal { z-index: 10001 !important; }

        #feedback { font-size: 14px; }
            
        /* css for selectable */
        .selectable .ui-selecting { background: #b8d4ea; }
        .selectable .ui-selected { background: #76acd6; color: white; }
        .selectable { list-style-type: none; margin: 0; padding: 0; }
        .selectable li { margin: 5px; padding: 0.4em; font-size: 14px; }
        /* end of -------- css for selectable */
        
        /* css for fixed first column of the table (for confStep 2) */

        .customThCss {text-align: center; vertical-align: middle; width: 300px !important;}
        .rightBorder {border-right: 2px solid #ccc;}
        .priceField {width: 50px; text-align: center;}
        .selectedRow, tbody > tr:hover {background-color: #b8d4ea;}

        table.fixedColumn > thead > tr { background-color: #ddd; padding-top: 20px; padding-bottom: 20px;}
        table.fixedColumn > thead > tr > th:first-child, table.fixedColumn > tbody > tr > td:first-child  { position: absolute; display: inline-block; background-color: #ccc; width: 140px; vertical-align: middle; }
        table.fixedColumn > tbody > tr > td {padding-bottom: 15px !important;}
        table.fixedColumn > tbody > tr > td:first-child {text-align: center;}


        table.fixedColumn > thead > tr > th:nth-child(2), table.fixedColumn > tbody > tr > td:nth-child(2) { padding-left:150px !important; }
        /* end of ----------------- css for fixed first column of the table (for confStep 2) */

        
        #retailItemPCAppWrapper, #retailItemPCAppWrapper select, #retailItemPCAppWrapper select option {font-size: 14px !important;}


    </style>
@endsection
@section('content')
    <div class="container-fluid" id="retailItemPCAppWrapper">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu <span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
                    </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif

                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Items per Branch :: Price Configuration</h3>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Price Configuration for Retail Items by Branch
                                
                            </div>
                            <div class="panel-body">
                                <div v-if="confStep === 2">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <button type="button" class="btn btn-success btn-sm">Set Active</button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <button type="button" class="btn btn-success btn-sm">Set Redeemable</button>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 5px;">
                                                <div class="col-xs-6">
                                                    <button type="button" class="btn btn-success btn-sm">Unset Active</button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <button type="button" class="btn btn-success btn-sm">Unset Redeemable</button>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>Points per peso (SRP)</label><br>
                                                    <input type="text" name="">
                                                    <button type="button" class="btn btn-success btn-sm"  style="margin-left: 5px;">Set Points</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Price</label><br>
                                                    <input type="text" name="">
                                                    <button type="button" class="btn btn-success btn-sm"  style="margin-left: 5px;">Set Price</button>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div v-if="confStep === 1">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <label for="corp_nam" class="col-md-4 control-label">Corporation</label>
                                                <div class="col-md-8">
                                                    <select class="form-control required" id="corp_type" name="corp_type" v-model="ri_selectedCorporationId" @change="loadBranches">
                                                        <option value="">Choose Corporation</option>
                                                        <option v-for="corporation in corporations" :value="corporation.id" :selected="ri_selectedCorporationId==corporation.id">@{{ corporation.name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <hr>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Branches for the corporation

                                                    <select class="form-group pull-right" @change="filterBranchList($event)">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                        <option value="2">All</option>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body" id="branchList">
                                                    <ul id="selectableBranches" class="selectable">
                                                        <li v-for="branch in corpBranches" :branchid="branch.id" class="ui-widget-content" v-if="(showBranchStatus == branch.isActive) || showBranchStatus == 2" :class="(ri_selectedBranchIds.includes(branch.id.toString())) ? 'ui-selected' : ''">@{{ branch.name }} </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <label for="product_id" class="col-md-4 control-label">Products</label>
                                                <div class="col-md-8">
                                                    <select class="form-control required" id="product_id" name="product_id" v-model="selectedProductId" @change="loadItems">
                                                        <option v-for="product in products" :value="product.id" :selected="selectedProductId==product.id">@{{ product.name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <hr>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Item Codes for this Product

                                                    <select class="form-group pull-right" @change="filterServiceList($event)">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                        <option value="2">All</option>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body" id="serviceList">
                                                    <ul id="selectableRetailItems" class="selectable">
                                                        <li v-for="retailItem in retailItems" :serviceid="retailItem.id" class="ui-widget-content" v-if="(showRetailItemStatus == retailItem.isActive) || showRetailItemStatus == 2" :class="(selectedRetailItemIds.includes(retailItem.id.toString())) ? 'ui-selected' : ''">@{{ retailItem.code }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /confStep=1 -->
                                
                                <div v-if="confStep === 2">
                                    <div class="table-responsive">
                                        <div class="bootstrap-table">
                                            <div class="fixed-table-container table-no-bordered" style="padding-bottom: 0px;">
                                                <div class="fixed-table-body">
                                                    <table id="table" class="table table-hover table-no-bordered fixedColumn">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    Item Code
                                                                </th>
                                                                <th class="customThCss rightBorder" colspan="6" v-for="todo">
                                                                    Branch 1
                                                                </th>
                                                                <th class="customThCss rightBorder" colspan="6">
                                                                    Branch 2
                                                                </th>
                                                                <th class="customThCss rightBorder" colspan="6">
                                                                    Branch 3
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <br>
                                                                    <br>
                                                                    <br>
                                                                </th>
                                                                <th>
                                                                    Active
                                                                </th>
                                                                <th>
                                                                    Redeem
                                                                </th>
                                                                <th>
                                                                    Points
                                                                </th>
                                                                <th>
                                                                    SRP
                                                                </th>
                                                                <th>
                                                                    % MarkUp
                                                                </th>
                                                                <th class="rightBorder">
                                                                    Net
                                                                </th>
                                                                <th>
                                                                    Active
                                                                </th>
                                                                <th>
                                                                    Redeem
                                                                </th>
                                                                <th>
                                                                    Points
                                                                </th>
                                                                <th>
                                                                    SRP
                                                                </th>
                                                                <th>
                                                                    % MarkUp
                                                                </th>
                                                                <th class="rightBorder">
                                                                    Net
                                                                </th>
                                                                <th>
                                                                    Active
                                                                </th>
                                                                <th>
                                                                    Redeem
                                                                </th>
                                                                <th>
                                                                    Points
                                                                </th>
                                                                <th>
                                                                    SRP
                                                                </th>
                                                                <th>
                                                                    % MarkUp
                                                                </th>
                                                                <th class="rightBorder">
                                                                    Net
                                                                </th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr @click="toggleRowToEditable($event)"> 
                                                                <td>
                                                                    ABC-101
                                                                    <div class="text-center"><i class="glyphicon glyphicon-cog" title="SequelSports Membership Fee"></i> Last Cost: <strong>15.50</strong></div>
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                            </tr>
                                                            <tr @click="toggleRowToEditable($event)"> 
                                                                <td>
                                                                    ABC-102
                                                                    <div class="text-center"><i class="glyphicon glyphicon-cog" title="SequelSports Membership Fee"></i> Last Cost: <strong>18.00</strong></div>
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="" class="childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="0" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    <input type="text" name="" value="100" class="priceField childControl">
                                                                </td> 
                                                                <td>
                                                                    48.39
                                                                </td> 
                                                                <td class="rightBorder">
                                                                    7.50
                                                                </td> 
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-footer">
                                <a href="{{ url('/retail-items-price-conf') }}" class="btn btn-info btn-md pull-right" v-if="confStep === 2">Back</a> 

                                <div class="pull-right">
                                    <button type="button" class="btn btn-info btn-md" @click="confNext" v-if="confStep === 1">Show</button>
                                    <button type="button" class="btn btn-primary btn-md" v-if="confStep === 2" style="margin-left: 5px;">Save</button>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('footer-scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        var serviceApp = new Vue({
            el: '#retailItemPCAppWrapper',
            data: {
                confStep: 1,
                corporations: [
                    @foreach($corporations as $corporation)
                    {
                        id: {{ $corporation->corp_id }},
                        name: '{{ $corporation->corp_name }}',
                    },
                    @endforeach
                ],
                ri_selectedCorporationId: 0,
                products: [
                    @foreach($products as $product)
                    {
                        id: {{ $product->ProdLine_ID }},
                        name: '{{ $product->Product }}',
                    },
                    @endforeach
                ],
                selectedProductId: 0,
                corpBranches: [],
                ri_selectedBranchIds: [],
                retailItems: [],
                selectedRetailItemIds: [],
                showBranchStatus: 1,
                showRetailItemStatus: 1
            },
            methods: {
                toggleRowToEditable: function(event) {
                    var element = event.srcElement.closest('TR');
                    element.classList.toggle('selectedRow');

                    var childControls = element.querySelectorAll('.childControl');
                    var currentDisabledStatus = childControls[0].getAttribute('disabled');
                  
                    if(currentDisabledStatus == null || !currentDisabledStatus) {
                        for(var i = 0; i < childControls.length; i++) {
                            childControls[i].setAttribute('disabled', 'true');
                        }

                    } else {
                       for(var i = 0; i < childControls.length; i++) {
                           childControls[i].removeAttribute('disabled');
                       } 
                    }
                        

                },
                confNext: function() {
                    var self = this;

                    if(self.ri_selectedBranchIds.length && self.selectedRetailItemIds.length) {
                        self.confStep = 2;

                        

                    } else {
                        alert('Please select branch and retail items first, to proceed to next step.');
                    }

                    
                },
                confBack: function() {
                    var self = this;
                    self.confStep -= 1;

                    self.activateSelectable();

                },
                filterBranchList: function(event) {
                    var self = this;
                    var filterOption = event.target.value;

                    if(filterOption == 0) self.showBranchStatus = 0;
                    else if(filterOption == 1) self.showBranchStatus = 1;
                    else self.showBranchStatus = 2;
                },
                filterServiceList: function(event) {
                    var self = this;
                    var filterOption = event.target.value;

                    if(filterOption == 0) self.showRetailItemStatus = 0;
                    else if(filterOption == 1) self.showRetailItemStatus = 1;
                    else self.showRetailItemStatus = 2;
                },
                loadBranches: function() {
                    var self = this;

                    if(self.ri_selectedCorporationId) {
                        if(localStorage.getItem("ri_selectedCorporationId") !== null) {
                            localStorage.ri_selectedCorporationId = self.ri_selectedCorporationId;
                        } else {
                            localStorage.setItem("ri_selectedCorporationId", self.ri_selectedCorporationId);
                        }

                        var fetchUrl = '{{ route('ajax.fetch.branches', ['corp_id' => ':corpId']) }}';
                        fetchUrl = fetchUrl.replace(':corpId', self.ri_selectedCorporationId);

                        axios.get(fetchUrl)
                          .then(function (responsedBranches) {
                            var branches = responsedBranches.data;

                            self.corpBranches = [];

                            branches.map(function(branch) {
                                self.corpBranches.push({
                                    id: branch.Branch,
                                    name: branch.ShortName,
                                    isActive: branch.Active,
                                });
                            });
                          })
                          .catch(function (error) {
                            console.log(error);
                          });
                    }
                },
                loadItems: function() {
                    var self = this;

                    if(self.selectedProductId) {
                        if(localStorage.getItem("selectedProductId") !== null) {
                            localStorage.selectedProductId = self.selectedProductId;
                        } else {
                            localStorage.setItem("selectedProductId", self.selectedProductId);
                        }

                        var fetchUrl = '{{ route('ajax.fetch.retail-items', ['product_id_csv' => ':productIdCSV']) }}';
                        fetchUrl = fetchUrl.replace(':productIdCSV', self.selectedProductId);

                        axios.get(fetchUrl)
                          .then(function (responsedServices) {
                            var responseServices = responsedServices.data;
                            self.retailItems = [];

                            responseServices.map(function(service, index) {
                                self.retailItems.push({
                                    id: service.id,
                                    code: service.code,
                                    isActive: service.isActive,
                                });
                            });
                            
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }
                },
                activateSelectable: function() {
                    var self = this;

                    $("#selectableBranches").selectable({
                      selected: function( event, ui ) {
                            if(self.ri_selectedBranchIds.indexOf(ui.selected.getAttribute('branchid')) == -1)
                                self.ri_selectedBranchIds.push(ui.selected.getAttribute('branchid'));
                        },
                        unselected: function( event, ui ) {
                            var storedBranchIds = self.ri_selectedBranchIds;

                            var dataIndex = storedBranchIds.indexOf(ui.unselected.getAttribute('branchid'));
                            if (dataIndex > -1) {
                                storedBranchIds.splice(dataIndex, 1);
                            }

                            self.ri_selectedBranchIds = storedBranchIds;
                        },
                        stop: function( event, ui ) {
                            if (typeof(Storage) !== "undefined") {
                                localStorage.setItem("ri_selectedBranchIds", JSON.stringify(self.ri_selectedBranchIds));
                            } else {
                                console.log('Sorry! No Web Storage support..');
                            }
                        },
                    });

                    
                    $("#selectableRetailItems").selectable({
                        selected: function( event, ui ) {
                            if(self.selectedRetailItemIds.indexOf(ui.selected.getAttribute('serviceid')) == -1)
                                self.selectedRetailItemIds.push(ui.selected.getAttribute('serviceid'));
                        },
                        unselected: function( event, ui ) {
                            var storedSelectedServiceIds = self.selectedRetailItemIds;

                            var dataIndex = storedSelectedServiceIds.indexOf(ui.unselected.getAttribute('serviceid'));
                            if (dataIndex > -1) {
                                storedSelectedServiceIds.splice(dataIndex, 1);
                            }

                            self.selectedRetailItemIds = storedSelectedServiceIds;
                        },
                        stop: function( event, ui ) {
                            if (typeof(Storage) !== "undefined") {
                                localStorage.setItem("selectedRetailItemIds", JSON.stringify(self.selectedRetailItemIds));
                            } else {
                                console.log('Sorry! No Web Storage support..');
                            }
                        },
                    });

                },
                setLocalStorageVariables: function() {
                    var self = this;

                    if(localStorage.getItem("ri_selectedBranchIds") !== null) {
                        self.ri_selectedBranchIds = JSON.parse(localStorage.getItem("ri_selectedBranchIds"));
                    }

                    if(localStorage.getItem("selectedRetailItemIds") !== null) {
                        self.selectedRetailItemIds = JSON.parse(localStorage.getItem("selectedRetailItemIds"));
                    }

                    if(localStorage.getItem("ri_selectedCorporationId")) {
                        self.ri_selectedCorporationId = localStorage.getItem("ri_selectedCorporationId");
                        self.loadBranches();
                    }

                    if(localStorage.getItem("selectedProductId")) {
                        self.selectedProductId = localStorage.getItem("selectedProductId");
                        self.loadItems();
                    }
                }
            },
            mounted: function() {
                var self = this;

                self.setLocalStorageVariables();
                if(self.ri_selectedCorporationId === 0) {
                    self.ri_selectedCorporationId = self.corporations[0].id;
                    self.loadBranches();
                }

                if(self.selectedProductId === 0) {
                    self.selectedProductId = self.products[0].id;
                    self.loadItems();
                }

                $(function() {
                    self.activateSelectable();

                    $("#menu-toggle").click(function(e) {
                       e.preventDefault();
                       $("#togle-sidebar-sec").toggleClass("active");
                    });

                    $('.childControl').attr('disabled', 'true');
                });
            }
        });

        
        // matches polyfill
        this.Element && function(ElementPrototype) {
            ElementPrototype.matches = ElementPrototype.matches ||
            ElementPrototype.matchesSelector ||
            ElementPrototype.webkitMatchesSelector ||
            ElementPrototype.msMatchesSelector ||
            function(selector) {
                var node = this, nodes = (node.parentNode || node.document).querySelectorAll(selector), i = -1;
                while (nodes[++i] && nodes[i] != node);
                return !!nodes[i];
            }
        }(Element.prototype);

        // closest polyfill
        this.Element && function(ElementPrototype) {
            ElementPrototype.closest = ElementPrototype.closest ||
            function(selector) {
                var el = this;
                while (el.matches && !el.matches(selector)) el = el.parentNode;
                return el.matches ? el : null;
            }
        }(Element.prototype);
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection