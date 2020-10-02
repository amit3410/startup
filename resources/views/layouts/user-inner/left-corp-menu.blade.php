<?php

$resOtherDocsReq =   App\Helpers\Helper::getOtherDocsReq($userKycid);
$otherDocShow=0;
if($resOtherDocsReq && $resOtherDocsReq->count()){
    $otherDocShow=1;
}
?>
<div class="list-section">
	     <div class="kyc">
		   <h2>{{trans('forms.Corp_left_SideBar.Label.lbl_kyc')}}</h2>
		   <p class="marT15 marB15">{{trans('forms.Corp_left_SideBar.Label.lbl_para')}}</p>
		    <ul class="menu-left">
                    
                    
                    
		    <li><a class="<?=(Route::currentRouteName()=="company_profile-show")?'active':'' ?>" href="{{route('company_profile-show')}}">{{trans('forms.Corp_left_SideBar.Label.item1')}}</a></li>
                    
                    <li><a class="<?=(Route::currentRouteName()=="company-address-show")?'active':'' ?>" href="{{route('company-address-show')}}">{{trans('forms.Corp_left_SideBar.Label.item2')}}</a></li>
                    <li><a class="<?=(Route::currentRouteName()=="shareholding_structure")?'active':'' ?>" href="{{route('shareholding_structure')}}">{{trans('forms.Corp_left_SideBar.Label.item3')}}</a></li>
                    <li><a class="<?=(Route::currentRouteName()=="financial-show")?'active':'' ?>" href="{{route('financial-show')}}">{{trans('forms.Corp_left_SideBar.Label.item4')}}</a></li>
                    <li><a class="<?=(Route::currentRouteName()=="documents-show")?'active':'' ?>" href="{{route('documents-show')}}">{{trans('forms.Corp_left_SideBar.Label.item5')}}</a></li>
                    @if($otherDocShow==1)
                    <li><a class="<?=(Route::currentRouteName()=="upload_corp_other_document")?'active':'' ?>" href="{{route('upload_corp_other_document')}}">{{trans('forms.Corp_left_SideBar.Label.item6')}}</a></li>
                     @endif
		    </ul>
                   
                   
                   <ul class="menu-left">
                       <li><a class="" href="{{route('changepassword')}}">Change Password</a></li>
                        <li>
                            <a  href="{{ route('frontend_logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{trans('common.Button.sign_out')}}
                            </a>
                            <form id="logout-form" action="{{ route('frontend_logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                        </ul>
		</div>
	   </div>