<!-- Main Header / Header Style Two -->
<header class="main-header header-style-two fixed-header">

    <!-- Header Lower -->
    <div class="header-lower">
        <div class="auto-container">
            <div class="inner-container">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                        
                    <!-- Logo Box -->
                    <div class="logo-box">
                        <div class="logo"><a href="{{route('home')}}"><img src="assets/images/logo-2.svg" alt="" title=""></a></div>
                    </div>
                    <!-- End Logo Box -->
                    
                    <!-- Search Box -->
                    <div class="search-box-outer d-none d-lg-block" style="width: 30%;">
                        <livewire:actions.location-search />
                    </div>
                    <!-- End Search Box -->
                    
                    <!-- Nav Outer -->
                    <div class="nav-outer">
                        <!-- Main Menu -->
                        <nav class="main-menu navbar-expand-lg">
                            <div class="navbar-header">
                                <!-- Toggle Button -->    	
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            
                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    <li><a href="{{route('home')}}" class="text-black {{ request()->routeIs('home') ? 'active' : ''}}">{{ __('Home') }}</a></li>
                                    <li><a href="{{ route('buy')}}" class="text-black {{ request()->routeIs('buy') ? 'active' : ''}}">{{ __('Buy') }}</a></li>
                                    <li><a href="#" class="text-black {{ request()->routeIs('rent') ? 'active' : ''}}">{{ __('Rent') }}</a></li>
                                    <li><a href="#" class="text-black {{ request()->routeIs('saved-search') ? 'active' : ''}}"> {{ __('Saved Search') }} </a></li>
                                    <li><a href="#" class="text-black {{ request()->routeIs('whistlist') ? 'active' : ''}}"> {{ __('Whistlist') }} </a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <!-- End Nav Outer -->
                    
                    <!-- Outer Box -->
                    <div class="outer-box d-flex align-items-center">
                        <!-- Search Button for Mobile -->
                        <div class="search-box-btn d-block d-lg-none me-3">
                            <span class="icon flaticon-search"></span>
                        </div>
                        
                        <!-- Login/Register Button -->
                        <div class="header_button-box">
                            <a href="{{ route('login') }}" class="theme-btn btn-style-one">
                                <span class="btn-wrap">
                                    <span class="text-one">{{ __('Login/Register') }}</span>
                                    <span class="text-two">{{ __('Login/Register') }}</span>
                                </span>
                            </a>
                        </div>
                        
                        <!-- Mobile Navigation Toggler -->
                        <div class="mobile-nav-toggler ms-3"><span class="icon flaticon-menu"></span></div>
                    </div>
                    <!-- End Outer Box -->
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Lower -->

    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="icon flaticon-close-1"></span></div>
        
        <nav class="menu-box">
            <div class="nav-logo"><a href="{{route('home')}}"><img src="assets/images/logo.svg" alt="" title=""></a></div>
            
            <!-- Mobile Search Box -->
            <div class="mobile-search-box my-4">
                <livewire:actions.location-search-mobile />
            </div>
            
            <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
        </nav>
    </div>
    <!-- End Mobile Menu -->

</header>
<!-- End Main Header -->