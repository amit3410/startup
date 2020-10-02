<div class="table-responsive">
        <?php
        $reqStatus=Helpers::getReqStatusDropDown();
        $docStatus=Helpers::getDocStatusDropDown();
        $docMonitor=Helpers::getMonitoringDropDown();
       
        ?>
        <table class="table table-striped table-sm">
                <thead>
                        <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile No.</th>
                                <th>Document Name</th>
                                <th>Request Status</th>
                                <th>Document Status</th>
                                <th>Actions</th>
                        </tr>
                </thead>
                <tbody>
                    @php $i = ($usersList->currentpage()-1)* $usersList->perpage() + 1;@endphp
                    @foreach ($usersList as $user)
                    @php $bounty = Helpers::getKycDetails($user->user_id); 
                        if(isset($bounty) && $bounty['is_kyc_completed']==1) {
                         $boun = 'Completed';
                         $boun_status = 'st_complete';
                        } else {
                         $boun = 'Pending';
                         $boun_status = 'st_not_complete';
                        }
                        if(isset($bounty) && $bounty['is_finalapprove']==1 && $bounty['is_api_pulled'] == 1) {
                         $boun_appr = 'Approved';
                         $boun_appr_status = 'text-approved';
                        }
                        else if(isset($bounty) && $bounty['is_finalapprove']==2 && $bounty['is_api_pulled'] == 1) {
                         $boun_appr = 'Not Approved';
                         $boun_appr_status = 'text-disapproved';
                        }


                        else {
                         $boun_appr = 'No Action';
                         $boun_appr_status = 'text-disapproved';
                        }
                        
                    @endphp
                        <?php
                       
                        $route=route("document_detail",['id'=>$user->id,'doc_no'=>$user->doc_no]);
                        
                        
                        ?>
                        <tr>
                            <td> <a href ="{{$route}}">{{ $i++ }}</a></td>
                                <td>{{ ucwords($user->f_name)." ".ucwords($user->l_name) }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_no }}</td>
                                <td>{{(isset($user->doc_no) && !empty($user->doc_no)) ? $docMonitor[$user->doc_no] : '' }}</td>
                                <td><span class="status-icon {{($user->status==2)? 'st_complete':'st_not_complete'}}"> </span> {{ (isset($user->status) && !empty($user->status)) ? $reqStatus[$user->status] : '' }}</td>

                                <td><span class="{{ ($user->doc_status==1)?'text-approved':(($user->doc_status==2)?'text-disapproved':'status-icon st_not_complete') }}">{{  $docStatus[$user->doc_status] }}</span></td>
                                <!--				-->
                                <td>
                                        <div class="action-btn-i" data-toggle="dropdown">
                                                <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                </ul>
                                                <ul class="show-invoice">
                                                        <li><a href="Invoice.php">Get Invoice</a></li>
                                                        <li><a href="ContractDetails.php">Contract Details</a></li>
                                                        <li><a href="#" onclick="db_blockchain()">Blockchain Details</a></li>
                                                </ul>
                                        </div>


                                        <div class="dropdown-menu text-left dropdown-menu-btn">
                                          <a class="dropdown-item" href="{{$route}}">View Detail</a>
                                             
                                        </div>
                                </td>
                        </tr>
                        @endforeach
                </tbody>
        </table>
 
        <div class="d-md-flex align-items-center mt-4">
                <div class="ml-md-auto">
                    {!! $usersList->appends(request()->input())->links() !!}
                </div>
        </div>
</div>

<style>
button.custom-btn-action {
    background: transparent;
    border: 0px;
    padding: 2px 15px;
}
button.custom-btn-action a {
    padding: 5px 0px;
}
button.custom-btn-action a:hover{
    background: transparent;
}
</style>