<!-- /Notification -->
<?php
$user = \Illuminate\Support\Facades\Auth::user();
?>
<div class="d-flex  ml-auto header-right-icons header-search-icon">
    <div class="dropdown profile-1">
        <a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
										<span>
											<img src="{{$user->avatar}}" alt="profile-user"
                                                 class="avatar  profile-user brround cover-image">
										</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
            <div class="drop-heading">
                <div class="text-center">
                    <h5 class="text-dark mb-0">{{$user->name}}</h5>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
            <div class="dropdown-divider m-0"></div>
            <a class="dropdown-item" href="{{route('admin.user.changePassword')}}">
                <i class="dropdown-icon mdi mdi-account-outline"></i> Đổi mật khẩu
            </a>
            <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
               class="dropdown-item"><i class="dropdown-icon mdi mdi-account-outline"></i>
                Đăng xuất
            </a>
            <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;"><input
                    type="hidden" name="_token" value="{{csrf_token()}}"></form>
        </div>
    </div>
</div>
<!-- /Notification Ends -->
