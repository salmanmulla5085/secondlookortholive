<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('admin_dashboard') }}"
            target="_blank">
            <img src="<?= url("public/img/logo.png") ?>" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold d-none">Admin</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">

        <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'admin_dashboard' ? 'active' : '' }}" href="{{ route('admin_dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-sm opacity-10" style="color:#5e72e4 !important"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pages</h6>
            </li> -->

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'schedule') == true ? 'active' : '' }}" href="{{ route('schedule') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>

                    </div>
                    <span class="nav-link-text ms-1">Manage Appointments</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'AvailableScheduleSlots') == true ? 'active' : '' }}" href="{{ route('AvailableScheduleSlots') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>

                    </div>
                    <span class="nav-link-text ms-1">Manage Doctors Availability</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin_doctors') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'admin_doctors']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Doctors</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin_patients') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'admin_patients']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Patients</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'joints') == true ? 'active' : '' }}" href="{{ route('joints') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Joints</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{  str_contains(request()->url(), 'admin/plan') == true ? 'active' : '' }}" href="{{ route('admin.plan') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-collection text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Plans</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{  str_contains(request()->url(), 'billing') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'billing']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Payments</span>
                </a>
            </li>

            <!-- Static Pages Dropdown -->
            <!-- Sidenav HTML -->
            <li class="nav-item has-dropdown">
                <a class="nav-link collapsed" href="#staticPagesMenu" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="staticPagesMenu">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Static Pages</span>
                    <!-- Arrow added through CSS -->
                </a>
                <div class="collapse" id="staticPagesMenu">
                    <ul class="nav ms-4 ps-3">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('page', ['page' => 'admin/add-static-page']) }}">Add Static Page</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('page', ['page' => 'admin/list-static-pages']) }}">Static Pages</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('page', ['page' => 'admin/add-page-section']) }}">Add Page Section</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('page', ['page' => 'admin/list-page-sections']) }}">Page Sections</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- New Testimonials Menu Item -->

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/testimonials') == true ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Testimonials</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/faq') == true ? 'active' : '' }}" href="{{ route('admin.faq.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i> <!-- You can change the icon here -->
                    </div>
                    <span class="nav-link-text ms-1">Manage FAQs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/articles') == true ? 'active' : '' }}" href="{{ route('admin.articles') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Article</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'admin/categories') == true ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Categories</span>
                </a>
            </li>


            <li class="nav-item d-none">
                <a class="nav-link {{ str_contains(request()->url(), 'user-management') == true ? 'active' : '' }}" href="{{ route('page', ['page' => 'user-management']) }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <!-- <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>                         -->
                        <i class="ni ni-app text-info text-sm opacity-10"></i>

                    </div>
                    <span class="nav-link-text ms-1">Admin Users</span>
                </a>
            </li>

            <!-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            
             -->
        </ul>
    </div>

</aside>