<ul>
    
    <!-- <li class="text-muted menu-title">Navigation</li> -->
    @foreach($modules as $module)
    @foreach($subModuleGroup as $group)
    @if($module->id==$group->module_id)
    @if($group->count>1)
    <li class="has_sub">
        <a href="javascript:void(0);" class="waves-effect"><i class="{{$module->icon}}" style="font-weight: 600"></i> <span> {{$module->name}} </span> <span class="menu-arrow"></span></a>
        <ul class="list-unstyled">
            @foreach($subModules as $subModule)
            @if($module->id==$subModule->module_id)
            <li><a href="{{url($subModule->link)}}"><span class="ti-angle-double-right"></span> {{$subModule->name}}</a></li>
            @endif
            <!-- <li><a href="{{url('admin/users/merchants')}}">Merchants</a></li>
            <li><a href="{{url('admin/users/delivery-boys')}}">Delivery Boys</a></li>
            <li><a href="{{url('admin/users/admins')}}">Admin Users</a></li> -->
            @endforeach
        </ul>
    </li>
    @else
    <li>
        @foreach($subModules as $subModule)
        @if($module->id==$subModule->module_id)
        <a href="{{url($subModule->link)}}" class="waves-effect"><i class="{{$module->icon}}" style="font-weight: 600"></i> <span> {{$subModule->name}} </span> <!-- <span class="menu-arrow"></span> --></a>
        @endif
        @endforeach
    </li>
    @endif
    @endif
    @endforeach
    @endforeach
 
    <!-- <li class="has_sub">
        <a href="javascript:void(0);" class="waves-effect"><i class="ti-home"></i> <span> Category </span> <span class="menu-arrow"></span></a>
        <ul class="list-unstyled">
            <li><a href="{{url('admin/category')}}">Category</a></li>
        </ul>
    </li>

   

    <li class="has_sub">
        <a href="javascript:void(0);" class="waves-effect"><i class="ti-home"></i> <span> Roles & Permissions </span> <span class="menu-arrow"></span></a>
        <ul class="list-unstyled">
            <li><a href="{{url('admin/roles-permissions/roles')}}">Roles</a></li>
            <li><a href="{{url('admin/roles-permissions/permissions')}}">Permissions</a></li>
        </ul>
    </li> -->
</ul>
