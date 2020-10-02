<div class="table-responsive">
        <table class="table table-striped table-sm">
                <thead>
                        <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Document For</th>
                                <th>Document Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                        </tr>
                </thead>
                <tbody>
                   <?php 
                   $docFor=Helpers::getDocForDropDown();
                   $docType=Helpers::getDocTypeDropDown();
                  // dd($resData);
                   ?>
                   @php $i = ($resData->currentpage()-1)* $resData->perpage() + 1;@endphp
                    @foreach ($resData as $obj)
                   
                       <?php $route='';?>
                        <tr>
                            <td> <a href ="{{$route}}">{{ $i++ }}</a></td>
                                <td>{{ ucwords($obj->doc_name) }}</td>
                                <td>{{ $docFor[$obj->doc_for] }}</td>
                                <td>{{ $docType[$obj->type]}}</td>
                                <td> @if($obj->is_active=='0')
                                     Inactive
                                     @else
                                     Active
                                      @endif</td>
                                <td class="postion-relative">
                                 <div class="action-btn-i text-left" data-toggle="dropdown">
                                                <ul class="cursor-pointer">
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                </ul>
                                                
                                        </div>   
                                
                                    <div class="dropdown-menu text-left drop-block dropdown-menu-btn">
                                        <a class="dropdown-item" href="{{route('edit_other_document',['id'=>$obj->id])}}">   
                                     Edit</a>

                            <a class="dropdown-item" href="{{route('active_inactive_other_document',['id'=>$obj->id,'actInct'=>$obj->is_active])}}">
                                     @if($obj->is_active=='0')
                                     Active
                                     @else
                                     Inactive
                                      @endif
                                     </a>
                                     </div>
                                
                                </td>
                        </tr>
                        @endforeach

                        
                </tbody>
        </table>
 
        <div class="d-md-flex align-items-center mt-4">
                <div class="ml-md-auto">
                    {!! $resData->appends(request()->input())->links() !!}
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