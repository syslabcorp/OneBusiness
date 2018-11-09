@extends('layouts.app')
@section('header-scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll { overflow-x: auto; overflow-y: auto; }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body { padding: 15px !important; }

        a.disabled { pointer-events: none; cursor: default;  }

        .modal { z-index: 10001 !important; }

        #feedback { font-size: 14px; }
            
        /* css for selectable */
        .selectable .ui-selecting { background: #b8d4ea; }
        .selectable .ui-selected { background: #76acd6; color: white; }
        .selectable { list-style-type: none; margin: 0; padding: 0; }
        .selectable li { padding: 0.4em; font-size: 14px; border-bottom-width: 0px;}
        .selectable li:last-child {
          border-bottom-width: 1px;
        }
        /* end of -------- css for selectable */
        
        /* css for fixed first column of the table (for confStep 2) */

        .customThCss {text-align: center; vertical-align: middle; width: 300px !important;}
        .rightBorder {border-right: 2px solid #ccc;}
        .priceField {width: 50px; text-align: center;}

        table.fixedColumn > thead > tr { padding-top: 20px; padding-bottom: 20px;}
        table.fixedColumn td:not(.rightBorder), table.fixedColumn th:not(.rightBorder) {
          border-right: 1px solid #ccc;
        }
        table.fixedColumn > tbody > tr > td {text-align: center;}
        table.fixedColumn > thead > tr + tr th {
          font-weight: normal;
        }
        table.fixedColumn {
          border: 1px solid #ccc;
        }
        table.fixedColumn .childControl {
          background-color: #fff;
          background-image: none;
          border: 1px solid #ccc;
          border-radius: 4px;
          padding: 3px 0px;
          min-height: 28px;
        }
        table.fixedColumn .min-width {
          min-width: 80px;
          display: block; 
        }

        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>th:nth-child(2),
        table.fixedColumn tr>td:nth-child(1), table.fixedColumn tr>td:nth-child(2) {
          position: sticky;
          background: #FFF;
          z-index: 999;
          width: 100px;
          box-shadow: 1px 0px #ccc;
        }
        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>td:nth-child(1) {
          left: 0;
        }
        table.fixedColumn tr>th:nth-child(2), table.fixedColumn tr>td:nth-child(2) {
          left: 100px;
        }

        .selectedRow input {
          border: none;
          background: none;
        }

        #retailItemPCAppWrapper, #retailItemPCAppWrapper select, #retailItemPCAppWrapper select option {font-size: 14px !important;}


    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                  <div class="sidebar-nav">
                    <ul></ul>
                  </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif

                    <div class="col-md-12 col-xs-12" style="margin-top: 20px;" id="retailItemPCAppWrapper">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-6">
                                  <strong>Retail Items Pricing</strong>
                                </div>
                                <div class="col-sm-6 text-right">
                                  @if(\Auth::user()->checkAccessById(36, "E"))
                                    <button type="button" class="btn btn-success btn-md btn-copy" disabled="true" v-if="confStep === 1">Copy to Branch</button>
                                  @endif
                                  <button type="button" class="btn btn-success btn-md" @click="confNext" v-if="confStep === 1"
                                    {{ \Auth::user()->checkAccessById(36, "V") ? '' : 'disabled' }}>Show</button>
                                </div>
                              </div>
                            </div>
                            <div class="panel-body">
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
                                                  <div class="row">
                                                    <div class="col-sm-7">
                                                      Branches for the corporation
                                                    </div>
                                                    <div class="col-sm-5">
                                                      <select class="form-control" @change="filterBranchList($event)">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                        <option value="2">All</option>
                                                      </select>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="panel-body" id="branchList">
                                                    <ul v-if="corpBranches.length > 0" id="selectableBranches" class="selectable">
                                                        <li v-for="branch in corpBranches" :branchid="branch.id" class="ui-widget-content" v-if="(showBranchStatus == branch.isActive) || showBranchStatus == 2" :class="(ri_selectedBranchIds.includes(branch.id.toString())) ? 'ui-selected' : ''">@{{ branch.name }} </li>
                                                    </ul>
                                                    <div v-else style="color: #900;">No branch for this corporation</div>
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
                                                  <div class="row">
                                                    <div class="col-sm-7">
                                                      Item Codes for this Product
                                                    </div>
                                                    <div class="col-sm-5">
                                                      <select class="form-control" @change="filterServiceList($event)">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                        <option value="2">All</option>
                                                      </select>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="panel-body" id="serviceList">
                                                  <div v-if="listItems.length == 0" style="color: #900;">
                                                    <span v-if="showRetailItemStatus == 2">No items for this product line</span>
                                                    <span v-if="showRetailItemStatus == 1">No active items</span>
                                                    <span v-if="showRetailItemStatus == 0">No inactive items</span>
                                                  </div>
                                                  
                                                  <div v-if="listItems.length > 0">
                                                    <ul id="selectableRetailItems" class="selectable">
                                                        <li v-for="retailItem in listItems" :serviceid="retailItem.id" class="ui-widget-content" :class="(selectedRetailItemIds.includes(retailItem.id.toString())) ? 'ui-selected' : ''">@{{ retailItem.code }}</li>
                                                    </ul>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
  <div class="modal modal-copy fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('retail-items-price-conf.update', [1]) }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="corpID" value="">
          <input type="hidden" name="branch_id" value="">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Copy Configuration to other branch</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    @foreach($corporations as $company)
                      @if($company->branches()->where('Active', '=', 1)->count())
                      <th>{{ $company->corp_name }}</th>
                      @endif
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    @foreach($corporations as $company)
                      @if($company->branches()->where('Active', '=', 1)->count())
                      <td>
                        @foreach($company->branches()->where('Active', '=', 1)->orderBy('ShortName','ASC')->get() as $branch)
                          <div class="form-group">
                            <label style="font-weight: normal;">
                              <input type="checkbox" name="branch_ids[]" value="{{ $branch->Branch }}">
                              {{ $branch->ShortName }}
                            </label>
                          </div>
                        @endforeach
                      </td>
                      @endif
                    @endforeach
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button class="pull-left btn btn-default" data-dismiss="modal"><i class="fa fa-reply"></i> Back</button>
            <button class="btn btn-primary set-copy">Copy</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('footer-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script type="text/javascript">
      var listBranchs = [];
      
      $('body').on('click', '.btn-copy', function(event) {
        if ($('input[name="branch_ids[]"]:checked').length) {
          $('.set-copy').prop('disabled', false)
        }
        else {
          $('.set-copy').prop('disabled', true)
        }

        if($('#selectableBranches .ui-selected').length != 1) {
          $('#page-content-togle-sidebar-sec').prepend('\
            <div class="row alert-nothing">\
              <div class="alert alert-danger col-md-8 col-md-offset-2" style="border-radius: 3px;">\
                <span class="fa fa-close"></span> <em>Please select only one(1) branch...</em>\
              </div>\
            </div>\
            ');
            setTimeout(function() {
              $('.alert-nothing').remove();
            }, 3000);
            return;
        }

        $('.modal-copy input[name="branch_id"]').val($('#selectableBranches .ui-selected').attr('branchid'));
        $('.modal-copy input[name="corpID"]').val($('select[name="corp_type"]').val());
        
        $('.modal-copy').modal('show');
      });

        var serviceApp = new Vue({
            el: '#retailItemPCAppWrapper',
            data: {
                confStep: 1,
                corporations: [
                  @foreach($corporations as $corporation)
                    @if($corporation->database_name)
                    {
                      id: {{ $corporation->corp_id }},
                      name: '{{ $corporation->corp_name }}',
                    },
                    @endif
                  @endforeach
                ],
                ri_selectedCorporationId: 0,
                products: [
                    @foreach($products as $product)
                      @if($product->Active == 1)
                      {
                        id: {{ $product->ProdLine_ID }},
                        name: '{{ $product->Product }}',
                      },
                      @endif
                    @endforeach
                ],
                selectedProductId: 0,
                corpBranches: [],
                listBranches: [],
                ri_selectedBranchIds: [],
                retailItems: [],
                listItems: [],
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
                    window.location = "{{ route('retail-items-price-conf.create') }}?branch_ids=" + this.ri_selectedBranchIds + 
                      "&item_ids=" + this.selectedRetailItemIds + "&corpID=" + this.ri_selectedCorporationId;
                  } else {
                    $('#page-content-togle-sidebar-sec').prepend('\
                    <div class="row alert-nothing">\
                      <div class="alert alert-danger col-md-8 col-md-offset-2" style="border-radius: 3px;">\
                        <span class="fa fa-close"></span> <em>Please select Branch and Product first...</em>\
                      </div>\
                    </div>\
                    ');
                    setTimeout(function() {
                      $('.alert-nothing').remove();
                    }, 3000)
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

                    self.listItems = [];
                    for(var i = 0; i < self.retailItems.length; i++) {
                      if(self.retailItems[i].isActive == filterOption || filterOption == 2) {
                        self.listItems.push(self.retailItems[i]);
                      }
                    }
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
                            listBranchs = branches;

                            self.corpBranches = [];

                            branches.map(function(branch) {
                                self.corpBranches.push({
                                    id: branch.Branch,
                                    name: branch.ShortName,
                                    isActive: branch.Active,
                                });
                            });

                            self.ri_selectedBranchIds = []

                            setTimeout(function() {
                              self.activateSelectable();
                            }, 500);
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
                          .then(function (responsedRetailItems) {
                            var responseRetailItems = responsedRetailItems.data;
                            self.retailItems = [];

                            responseRetailItems.map(function(retailItem, index) {
                              self.retailItems.push({
                                  id: retailItem.id,
                                  code: retailItem.code,
                                  description: retailItem.description,
                                  isActive: retailItem.isActive,
                                  activeCounter: retailItem.activeCounter,
                                  inactiveCounter: retailItem.inactiveCounter,
                              });
                            });

                            self.listItems = [];
                            for(var i = 0; i < self.retailItems.length; i++) {
                              if(self.retailItems[i].isActive == 1) {
                                self.listItems.push(self.retailItems[i]);
                              }
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        self.activateSelectable();
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
                          if(self.ri_selectedBranchIds.length && self.selectedRetailItemIds.length) {
                            $('.btn-copy').prop('disabled', false);
                          }else {
                            $('.btn-copy').prop('disabled', true);
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
                          if(self.ri_selectedBranchIds.length && self.selectedRetailItemIds.length) {
                            $('.btn-copy').prop('disabled', false);
                          }else {
                            $('.btn-copy').prop('disabled', true);
                          }
                        },
                    });

                },
                setLocalStorageVariables: function() {
                    var self = this;

                    if(localStorage.getItem("ri_selectedCorporationId")) {
                        self.ri_selectedCorporationId = localStorage.getItem("ri_selectedCorporationId");
                        self.loadBranches();
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

                });
            }
        });
        
        $('body').on('change', function(event){
          if ($('input[name="branch_ids[]"]:checked').length) {
            $('.set-copy').prop('disabled', false)
          }
          else {
            $('.set-copy').prop('disabled', true)
          }
        });
    </script>
    
@endsection