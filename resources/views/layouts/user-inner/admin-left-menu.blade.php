<!-- SIDEBAR - START -->

<div class="list-section">
    <div class="kyc">
        <ul class="">
            <li><a class="{{($curouteName=='backend_dashboard')?'active':''}}" onclick="window.location.href = '{{ route('backend_dashboard') }}'">Dashboard</a></li>

            <li class="parent"><a class="" href="javascript:;">Manage Users</a>
                <ul class="sub-menu sub-cls submenu mgr-user">
                   @can('individual_user')
                    <li class="marL15"><a class="{{($curouteName=='individual_user'||($curouteName=='user_detail' && @$benifinary['is_by_company']=='0'))?'active':''}}" onclick="window.location.href = '{{ route('individual_user') }}'">Individual Users</a></li>
                    @endcan
                    @can('corporate_user')
                    <li class="marL15"><a class="{{($curouteName=='corporate_user'||$curouteName=='corp_user_detail'||($curouteName=='user_detail' && @$benifinary['is_by_company']=='1'))?'active':''}}" onclick="window.location.href = '{{ route('corporate_user') }}'">Corporate Users</a></li>
                @endcan
                </ul>

            </li>

            <!--<li ><a class="" href="javascript:;">Manage Trading Users</a>
                <ul class="sub-menu sub-cls submenu mgr-user">
                   @can('individual_trading_user')
                    <li class="marL15"><a class="{{($curouteName=='individual_trading_user'||($curouteName=='user_trading_detail' && @$benifinary['is_by_company']=='0'))?'active':''}}" onclick="window.location.href = '{{ route('individual_trading_user') }}'">Trading Users</a></li>
                    @endcan
                </ul>
            </li>-->
            
            <li><a class="{{($curouteName=='profile_monitoring')?'active':''}}" onclick="window.location.href = '{{ route('profile_monitoring') }}'">Profile Monitoring</a></li>
            @php $roleData = \Helpers::getUserRole() @endphp
        @if($roleData[0]->is_superadmin == 1)
        <li class="parent">
           
            <a class="" href="javascript:;">Access Management</a>
               <ul class="sub-menu sub-cls submenu mgr-user">
                    <li class="marL15">
                        <a class="nav-link" onclick="window.location.href = '{{ route('get_role') }}'">Manage Roles</a>
                    </li>

                    <li class="marL15">
                        <a class="nav-link" onclick="window.location.href = '{{ route('get_role_user') }}'">Manage Users</a>
                    </li>
                </ul>
            
        </li>
              @endif


         <li><a class="{{($curouteName=='other_document')?'active':''}}" onclick="window.location.href = '{{ route('other_document') }}'">Document Master</a></li>
    

        </ul>
        <ul class="menu-left">
            <li>
                <a  href="{{ route('backend_logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Sign Out
                </a>
                <form id="logout-form" action="{{ route('backend_logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

        </ul>

    </div>
</div>
<!--  SIDEBAR - END -->