<div class="content-header">
    <!-- Left Section -->
    <div class="d-flex align-items-center">
        <!-- Toggle Sidebar -->
        <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
            <i class="fa fa-fw fa-bars"></i>
        </button>
        <!-- END Toggle Sidebar -->

        <!-- Toggle Mini Sidebar -->
        <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
            <i class="fa fa-fw fa-ellipsis-v"></i>
        </button>
        <!-- END Toggle Mini Sidebar -->

        <!-- Open Search Section (visible on smaller screens) -->
        <button type="button" class="btn btn-sm btn-dual d-md-none" data-toggle="layout" data-action="header_search_on">
            <i class="fa fa-fw fa-search"></i>
        </button>
        <!-- END Open Search Section -->

        <!-- Search Form (visible on larger screens) -->
        <form class="d-none d-md-inline-block" action="#" method="GET">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-alt" placeholder="Recherche.." name="search">
                <div class="input-group-append">
                    <span class="input-group-text bg-body border-0">
                        <i class="fa fa-fw fa-search"></i>
                    </span>
                </div>
            </div>
        </form>
        <!-- END Search Form -->
    </div>
    <!-- END Left Section -->

    <!-- Right Section -->
    <div class="d-flex align-items-center">
        <!-- User Dropdown -->
        <div class="dropdown d-inline-block ml-2">
            <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src="{{ asset('assets/media/avatars/avatar10.jpg') }}" alt="Avatar" style="width: 21px;">
                <span class="d-none d-sm-inline-block ml-2">{{ Auth::user()->name }}</span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-primary-dark rounded-top">
                    <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('assets/media/avatars/avatar10.jpg') }}" alt="">
                    <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>
                    <p class="mb-0 text-white-50 font-size-sm">{{ Auth::user()->email }}</p>
                </div>
                <div class="p-2">
                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('logout') }}">
                        <span class="font-size-sm font-w500">Se Déconnecter</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- END User Dropdown -->
    </div>
    <!-- END Right Section -->
</div>
<!-- END Header Content -->

<!-- Header Search -->
<div id="page-header-search" class="overlay-header bg-white">
    <div class="content-header">
        <form class="w-100" action="#" method="GET">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-alt-danger" data-toggle="layout" data-action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <input type="text" class="form-control" placeholder="Recherche..." name="search">
            </div>
        </form>
    </div>
</div>
<!-- END Header Search -->

<!-- Header Loader -->
<div id="page-header-loader" class="overlay-header bg-white">
    <div class="content-header">
        <div class="w-100 text-center">
            <i class="fa fa-fw fa-circle-notch fa-spin"></i>
        </div>
    </div>
</div>
<!-- END Header Loader -->
