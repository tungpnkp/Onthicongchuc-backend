<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ url('/') }}">
            <img src="{{asset('assets/images/logo.jpg')}}" class="header-brand-img light-logo1" alt="logo"
                 width="100px">
        </a><!-- LOGO -->
        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a>
        <!-- sidebar-toggle-->
    </div>

    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
{{--            <div class="user-pic">--}}
{{--                <img src="../assets/images/users/10.jpg" alt="user-img" class="avatar-xl rounded-circle">--}}
{{--            </div>--}}
            <div class="user-info">
                <h6 class=" mb-0 text-dark">{{\Auth::user()->name}}</h6>
                <span class="text-muted app-sidebar__user-name text-sm">Administrator</span>
            </div>
        </div>
    </div>
    <div class="sidebar-navs" style="text-align: center;display: block">
        <ul style="display: inline-block;">
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Logout">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fe fe-power"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <ul class="side-menu">
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.post.index') }}"><i
                    class="side-menu__icon ti-book"></i><span
                    class="side-menu__label">Bài viết</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.notification.index') }}"><i
                    class="side-menu__icon ti-book"></i><span
                    class="side-menu__label">Thông báo</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.category.index') }}"><i
                    class="side-menu__icon ti-book"></i><span
                    class="side-menu__label">Danh mục</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.document.index') }}"><i
                    class="side-menu__icon ti-book"></i><span
                    class="side-menu__label">Lý thuyết</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.exam.index') }}"><i
                    class="side-menu__icon ti-book"></i><span
                    class="side-menu__label">Trắc nghiệm</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.user.index') }}"><i
                    class="side-menu__icon ti-user"></i><span
                    class="side-menu__label">Khách hàng</span><i class="angle fa fa-angle-right"></i></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.account.index') }}"><i
                    class="side-menu__icon ti-user"></i><span
                    class="side-menu__label">Admin</span><i class="angle fa fa-angle-right"></i></a>
        </li>
    </ul>
</aside>
<!--/APP-SIDEBAR-->
