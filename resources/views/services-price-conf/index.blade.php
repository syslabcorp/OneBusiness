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
        .selectable li { padding: 0.4em; font-size: 14px; }
        .selectable li + li { border-top: none;}
        
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
          border: none;
          border-radius: 4px;
        }

        .rightBorder {border-right: 2px solid #ccc;}

        #serviceAppWrapper, #serviceAppWrapper select, #serviceAppWrapper select option {font-size: 14px !important;}

    </style>
@endsection
@section('content')
    <div class="container-fluid" id="serviceAppWrapper">
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

                    <div class="col-md-12 col-xs-12" style="margin-top: 20px;">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <div class="row">
                                <div class="col-sm-6">
                                  <strong>Service per Branch Configuration</strong>
                                </div>
                                <div class="col-sm-6 text-right">
                                  @if(\Auth::user()->checkAccessById(37, "E"))
                                  <button type="button" class="btn btn-success btn-md btn-copy" disabled="true">
                                    Copy to Branch
                                  </button>
                                  @endif
                                  <button type="button" class="btn btn-success btn-md" @click="confNext"
                                    {{ \Auth::user()->checkAccessById(37, "V") ? '' : 'disabled' }}>
                                    Show
                                  </button>
                                </div>
                              </div>
                            </div>

                            <div class="panel-body">
                                <hr>
                                <div v-if="confStep === 1">
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="row form-group">
                                              <label for="corp_nam" class="col-md-4 control-label">Corporation</label>
                                              <div class="col-md-8">
                                                  <select class="form-control required" id="corp_type" name="corp_type" v-model="selectedCorporationId" @change="loadBranches">
                                                      <option v-for="corporation in corporations" :value="corporation.id" :selected="selectedCorporationId==corporation.id">@{{ corporation.name }}</option>
                                                  </select>
                                              </div>
                                          </div>
                                          <hr>
                                          <div class="panel panel-default">
                                            <div class="panel-heading">
                                              <div class="row">
                                                <div class="col-md-7">
                                                  Branches for the corporation
                                                </div>
                                                <div class="col-md-5">
                                                  <select class="form-control" @change="filterBranchList($event)">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                    <option value="2">All</option>
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="panel-body" id="branchList">
                                                <ul v-if="listBranches.length > 0" id="selectableBranches" class="selectable">
                                                    <li v-for="branch in listBranches" :branchid="branch.id" v-if="(showBranchStatus == branch.isActive) || showBranchStatus == 2" :class="(selectedBranchIds.includes(branch.id.toString())) ? 'ui-widget-content ui-selected' : 'ui-widget-content'">@{{ branch.name }} </li>
                                                </ul>
                                                <div v-if="listBranches.length == 0" style="color: #900;">No branch for this corporation</div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div style="height: 75px;"></div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                  <div class="row">
                                                    <div class="col-md-7">
                                                      List of Services
                                                    </div>
                                                    <div class="col-md-5">
                                                      <select class="form-control" v-model="serviceStatus" @change="filterServiceList($event)">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                        <option value="2">All</option>
                                                      </select>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="panel-body" id="serviceList">
                                                    <div v-if="listServices.length == 0" style="color: #900;">
                                                      <span v-if="serviceStatus == 2">No services found</span>
                                                      <span v-if="serviceStatus == 1">No active services</span>
                                                      <span v-if="serviceStatus == 0">No inactive services</span>
                                                    </div>
                                                    <div v-else>
                                                      <ul id="selectableServices3" class="selectable selectableServices">
                                                        <li v-for="service in listServices" :serviceid="service.id" :class="(selectedServiceIds.includes(service.id.toString())) ? 'ui-widget-content ui-selected' : 'ui-widget-content'">@{{ service.code }}</li>
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
        <form action="{{ route('services-price-conf.update', [1]) }}" method="POST">
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
            <button class="pull-left btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-reply"></i> Back</button>
            <button class="btn btn-primary" type="submit">Copy</button>
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
            el: '#serviceAppWrapper',
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
                selectedCorporationId: 0,
                corpBranches: [],
                listBranches: [],
                selectedBranchIds: [],
                services: [],
                listServices: [],
                selectedServiceIds: [],
                showBranchStatus: 1,
                serviceStatus: 1
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

                  if(self.selectedBranchIds.length && self.selectedServiceIds.length) {
                    window.location = "{{ route('services-price-conf.create') }}?branch_ids=" + this.selectedBranchIds + 
                      "&service_ids=" + this.selectedServiceIds + "&corpID=" + this.selectedCorporationId;
                  } else {
                    $('#page-content-togle-sidebar-sec').prepend('\
                    <div class="row alert-nothing">\
                      <div class="alert alert-danger col-md-8 col-md-offset-2" style="border-radius: 3px;">\
                        <span class="fa fa-close"></span> <em>Please select Branch and Service first...</em>\
                      </div>\
                    </div>\
                    ');
                    setTimeout(function() {
                      $('.alert-nothing').remove();
                    }, 3000);
                  }
                },
                confBack: function() {
                    var self = this;
                    self.confStep -= 1;

                    // self.activateSelectable();

                },
                filterBranchList: function(event) {
                  var self = this;
                  var filterOption = event.target.value;

                  if(filterOption == 0) self.showBranchStatus = 0;
                  else if(filterOption == 1) self.showBranchStatus = 1;
                  else self.showBranchStatus = 2;

                  self.listBranches = [];
                  for(var i = 0; i < self.corpBranches.length; i++) {
                    if(self.corpBranches[i].isActive == filterOption || filterOption == 2) {
                      self.listBranches.push(self.corpBranches[i]);
                    }
                  }
                },
                filterServiceList: function(event) {
                    var self = this;

                    self.listServices = [];
                    for(var i = 0; i < self.services.length; i++) {
                      if(self.services[i].isActive == this.serviceStatus || this.serviceStatus == 2) {
                        self.listServices.push(self.services[i]);
                      }
                    }

                    setTimeout(function() {
                      self.activateSelectable();
                    }, 500);
                },
                loadBranches: function() {
                    var self = this;

                    if(self.selectedCorporationId) {
                        if(localStorage.getItem("selectedCorporationId") !== null) {
                            localStorage.selectedCorporationId = self.selectedCorporationId;
                        } else {
                            localStorage.setItem("selectedCorporationId", self.selectedCorporationId);
                        }

                        var fetchUrl = '{{ route('ajax.fetch.branches', ['corp_id' => ':corpId']) }}';
                        fetchUrl = fetchUrl.replace(':corpId', self.selectedCorporationId);

                        axios.get(fetchUrl)
                          .then(function (responsedBranches) {
                            var branches = responsedBranches.data;
                            listBranchs = branches;

                            self.corpBranches = [];
                            self.listBranches = [];

                            branches.map(function(branch) {
                              self.corpBranches.push({
                                id: branch.Branch,
                                name: branch.ShortName,
                                isActive: branch.Active,
                              });
                            });

                            for(var i = 0; i < self.corpBranches.length; i++) {
                              if(self.corpBranches[i].isActive == 1) {
                                self.listBranches.push(self.corpBranches[i]);
                              }
                            }
                          })
                          .catch(function (error) {
                            console.log(error);
                          });
                    }
                    setTimeout(function() {
                      self.activateSelectable();
                    }, 500);
                },
                loadServices: function() {
                    var self = this;
                    var fetchUrl = '{{ route('ajax.fetch.services') }}';

                    axios.get(fetchUrl)
                      .then(function (responsedServices) {
                        var responseServices = responsedServices.data;
                        self.services = [];

                        responseServices.map(function(service, index) {
                          self.services.push({
                            id: service.id,
                            code: service.code,
                            description: service.description,
                            isActive: service.isActive,
                            activeCounter: service.activeCounter,
                            inactiveCounter: service.inactiveCounter,
                          });
                        });

                        self.listServices = [];

                        for(var i = 0; i < self.services.length; i++) {
                          if(self.services[i].isActive == self.serviceStatus) {
                            self.listServices.push(self.services[i]);
                          }
                        }

                        setTimeout(function() {
                          self.activateSelectable();
                        }, 500);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                    ;
                },
                activateSelectable: function() {
                    var self = this;

                    $("#selectableBranches").selectable({
                      selected: function( event, ui ) {
                            if(self.selectedBranchIds.indexOf(ui.selected.getAttribute('branchid')) == -1)
                                self.selectedBranchIds.push(ui.selected.getAttribute('branchid'));
                        },
                        unselected: function( event, ui ) {
                          var storedBranchIds = self.selectedBranchIds;

                          var dataIndex = storedBranchIds.indexOf(ui.unselected.getAttribute('branchid'));
                          if (dataIndex > -1) {
                              storedBranchIds.splice(dataIndex, 1);
                          }

                          self.selectedBranchIds = storedBranchIds;
                        },
                        stop: function( event, ui ) {
                          if(self.selectedBranchIds.length && self.selectedServiceIds.length) {
                            $('.btn-copy').prop('disabled', false);
                          }else {
                            $('.btn-copy').prop('disabled', true);
                          }
                        },
                    });

                    
                    $(".selectableServices").selectable({
                        selected: function( event, ui ) {
                            if(self.selectedServiceIds.indexOf(ui.selected.getAttribute('serviceid')) == -1)
                                self.selectedServiceIds.push(ui.selected.getAttribute('serviceid'));
                        },
                        unselected: function( event, ui ) {
                            var storedSelectedServiceIds = self.selectedServiceIds;

                            var dataIndex = storedSelectedServiceIds.indexOf(ui.unselected.getAttribute('serviceid'));
                            if (dataIndex > -1) {
                                storedSelectedServiceIds.splice(dataIndex, 1);
                            }

                            self.selectedServiceIds = storedSelectedServiceIds;
                        },
                        stop: function( event, ui ) {
                          if(self.selectedBranchIds.length && self.selectedServiceIds.length) {
                            $('.btn-copy').prop('disabled', false);
                          }else {
                            $('.btn-copy').prop('disabled', true);
                          }
                        },
                    });

                },
                setLocalStorageVariables: function() {
                    var self = this;

                    if(localStorage.getItem("selectedCorporationId")) {
                        self.selectedCorporationId = localStorage.getItem("selectedCorporationId");
                        self.loadBranches();
                    }
                }
            },
            mounted: function() {
                var self = this;

                self.loadServices();
                self.setLocalStorageVariables();

                if(self.selectedCorporationId === 0) {
                    self.selectedCorporationId = self.corporations[0].id;
                    self.loadBranches();
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
    </script>
@endsection