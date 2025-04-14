<!-- Header Start -->
<header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6">
    <!-- ========== HEADER ========== -->
    <nav class="w-full flex items-center justify-between" aria-label="Global">
        <ul class="icon-nav flex items-center gap-4">
            <li class="relative xl:hidden">
                <a class="text-xl icon-hover cursor-pointer text-heading" id="headerCollapse"
                    data-hs-overlay="#application-sidebar-brand" aria-controls="application-sidebar-brand"
                    aria-label="Toggle navigation" href="javascript:void(0)">
                    <i class="ti ti-menu-2 relative z-1"></i>
                </a>
            </li>
            <li class="relative"></li>
        </ul>
        <div class="flex items-center gap-4">
            <div class="hs-dropdown relative inline-flex [--placement:bottom-right] sm:[--trigger:hover]">
                <a class="relative hs-dropdown-toggle cursor-pointer align-middle rounded-full">
                    <img class="object-cover w-9 h-9 rounded-full"
                    src="{{ asset('assets/images/profile/user-1.jpg') }}"
                    alt="User Profile" aria-hidden="true">

                </a>
                <div class="card hs-dropdown-menu transition-[opacity,margin] rounded-md duration hs-dropdown-open:opacity-100 opacity-0 mt-2 min-w-max w-[200px] hidden z-[12]"
                    aria-labelledby="hs-dropdown-custom-icon-trigger">
                    <div class="card-body p-0 py-2">
                        <a href="javascript:void(0)"
                            class="flex gap-2 items-center font-medium px-4 py-1.5 hover:bg-gray-200 text-gray-400">
                            <i class="ti ti-user text-xl"></i>
                            <p class="text-sm">
                                @auth
                                    {{ ucfirst(Auth::user()->role) }}
                                @else
                                    Guest
                                @endauth
                            </p>
                        </a>
                        <div class="px-4 mt-[7px] grid">
                            <!-- Logout Form -->
                            <div class="px-4 mt-[7px] grid">
                                <!-- Logout Form -->
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn-outline-primary font-medium text-[15px] w-full hover:bg-blue-600 hover:text-white">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- ========== END HEADER ========== -->
</header>
<!-- Header End -->
