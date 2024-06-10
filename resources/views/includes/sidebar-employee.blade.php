<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!--Sidebar Scroll -->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('employee.dashboard') }}" class="text-nowrap logo-img">
                <!-- <img src="{{asset('images/pp-logo.png')}}" width="180" alt="" /> -->
                <img src="{{ Storage::url($company_logo) }}" width="180" alt="Company Logo" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('employee.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('employee.profile_page') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-circle"></i>
                        </span>
                        <span class="hide-menu">Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('employee.my_modules')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-book-2"></i>
                        </span>
                        <span class="hide-menu">Quiz</span>
                    </a>
                </li>
                
                <!-- Discussions Section -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span>
                            <i class="ti ti-messages"></i>
                        </span>
                        <span class="hide-menu">Discussions</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('employee.discussion') }}" class="sidebar-link">
                                <span class="hide-menu">Homepage</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('employee.create-post') }}" class="sidebar-link">
                                <span class="hide-menu">Ask Questions</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('employee.check-post') }}" class="sidebar-link">
                                <span class="hide-menu">Check Posted Questions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#" aria-expanded="false">
                        <span>
                            <i class="ti ti-info-circle"></i>
                        </span>
                        <span class="hide-menu">Tutorials</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('logout') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu">Log Out</span>
                    </a>
                </li>
                
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll -->
</aside>