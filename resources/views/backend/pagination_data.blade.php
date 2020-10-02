<div class="table-responsive">
        <table class="table table-striped table-sm">
                <thead>
                        <tr>
                                <th width="70px">Sr. No.</th>
                                <th id="namediv">Name</th>
                                <th width="180px">Email</th>
                                <th>Mobile No.</th>
                                <th>Created on</th>
                                <th width="120px">KYC Status</th>
                                <th>Profile Status</th>
                                <!--<th>Users Source</th>-->
                                <th>Status</th>
                                <th>Actions</th>
                        </tr>
                </thead>
                @php $userType = '';@endphp
               
                @if($usersList && count($usersList) > 0)
                <tbody> 

                    @php $i = ($usersList->currentpage()-1)* $usersList->perpage() + 1;@endphp

                    
                    @foreach ($usersList as $user)
                    @php $bounty = Helpers::getKycDetailsbyType($user->user_id,0);
                        if(isset($bounty) && $bounty['is_kyc_completed']==1) {
                         $boun = 'Completed';
                         $boun_status = 'st_complete';
                        } else {
                         $boun = 'Pending';
                         $boun_status = 'st_not_complete';
                        }
                        if(isset($bounty) && $bounty['is_finalapprove']==1) {
                         $boun_appr = 'Approved';
                         $boun_appr_status = 'text-approved';
                        }
                        else if(isset($bounty) && $bounty['is_finalapprove']==2) {
                         $boun_appr = 'Not Approved';
                         $boun_appr_status = 'text-disapproved';
                        }


                        else {
                         $boun_appr = 'No Action';
                         $boun_appr_status = 'text-disapproved';
                        }
                        
                    @endphp
                        <?php
                        
                        $userType = $user->user_type;
                        if($user->user_type=='1'){
                            $route=route("user_detail",['user_id' => $user->user_id,'user_type' => $user->user_type,'user_kyc_id'=>$user->user_kyc_id,'is_by_company'=>$user->is_by_company]);

                        }else if($user->user_type=='2'){
                             $route=route("corp_user_detail",['user_id' => $user->user_id,'user_type' => $user->user_type,'user_kyc_id'=>$user->user_kyc_id,'is_by_company'=>$user->is_by_company]);
                        }
                        $status =   ['0'=>'Inactive','1'=>'Active'];
                        $statusAction =   ['1'=>'Inactive','0'=>'Active'];
                        $userSouce = $user->user_source;
                        if($userSouce == "trading") {
                            $userS = "Trading";
                        } else {
                            $userS = "Compliance";
                        }
                        

                        $assesmentData = Helpers::getAssesmentRankData($user->user_kyc_id, 1);
                        if ($assesmentData && $assesmentData->count()) {
                            $arrRankName = Helpers::getRankNames();
                            $rank_decision = isset($assesmentData[0]->avg_rank) ? $arrRankName[$assesmentData[0]->avg_rank] : '';
                            } else {
                                $arrRankName = [];
                                $rank_decision = "Pending";
                            }
                        ?>
                        <tr>
                                <td> <a href ="{{$route}}">{{ $i++ }}</a></td>
                                <td>{{ ($user->user_type == 1) ? ucwords($user->f_name)." ".ucwords($user->l_name) : ucwords(@$user->corp_name) }}</td>
                                <td style="word-break: break-all">{{ $user->email }}</td>
                                <td>{{ $user->phone_no }}</td>
                                <td>{{(isset($user->created_at) && !empty($user->created_at)) ? Helpers::getDateByFormat($user->created_at,'Y-m-d H:i:s', 'd F Y') : '' }}</td>
                                <td><span class="status-icon {{$boun_status}}"> </span> {{ isset($boun) ? $boun : '' }}</td>


                                <!--<td><span class="{{ $boun_appr_status }}">{{ isset($boun_appr) ? $boun_appr : '' }}</span></td>-->
                                <td>{{$rank_decision}}</td>
                                <td><span class="{{ $boun_appr_status }}">{{ isset($boun_appr) ? $boun_appr : '' }}</span></td>
                                <!--<td>{{ isset($userS) ? $userS : '' }}</td>-->
                                <!--				-->
                                <td class="postion-relative">
                                        <div class="action-btn-i" data-toggle="dropdown">
                                                <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                </ul>
                                                
                                        </div>

                                        <div class="dropdown-menu text-left">
                                            <button class="custom-btn-action"><a class="dropdown-item" href="{{$route}}">View Detail</a></button>
                                            <?php if($userS == 'Trading') { ?>
                                            <button class="custom-btn-action"><a class="btn btn-save m-0" data-toggle="modal" data-target="#sendtoTrading" data-url="{{route('sendtotrading_user',['id' => $user->user_id])}}" data-height="350px" data-width="100%" data-placement="top">
                                                Send to Trading </a></button>
                                            <?php  } ?>
                                            
                                            
                                            
                                            <button  class="custom-btn-action" data-toggle="modal" data-target="#changeStatus"  data-url="{{route('individual_status',['user_id'=>$user->user_id,'current_status'=>$user->is_active,'curouteName'=>$curouteName,'user_type' => $user->user_type])}}" data-height="95px" data-width="100%" data-placement="top">  {{$statusAction[$user->is_active]}}</button>
                                            

                                           <!-- <a   href="{{route('lock_unlock',['user_id'=>$user->user_id,'block_statatus'=>$user->block_status_id])}}">
                                             <button type="submit" class="custom-btn-action">{{($user->block_status_id==1)?'Unlock':'Lock'}}</button>
                                            </a> -->
                                        </div>
                                </td>
                        </tr>
                        @endforeach
                </tbody>
                @else
                    

                    <tr>
                        <th colspan="9"><h3 style='color: green'>NO Record Found</h3></th>
                               
                        </tr>

                @endif
                
        </table>
 
        <div class="d-md-flex align-items-center mt-4">
                <div class="ml-md-auto">
                    {!! $usersList->appends(request()->input())->links() !!}
                </div>
        </div>
</div>

<div class="modal" id="changeStatus">
        <div class="modal-dialog modal-xs">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Change Status</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
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
.postion-relative{position: relative;}
.action-btn-i > ul{cursor: pointer;}
</style>
<script>
var userType = '{{$userType}}';
if(userType == 2) {
    document.getElementById('namediv').innerHTML = 'Company Name';
}
</script>