<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->
<aside id="application-sidebar-brand"
    class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full  transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed xl:top-5 xl:left-auto top-0 left-0 with-vertical h-screen z-[999] shrink-0  w-[270px] shadow-md xl:rounded-2xl rounded-none bg-white left-sidebar   transition-all duration-300">
    <div class="p-4">

        {{-- <h1 class="text-gray-500 text-lg font-bold">Web Kasir</h1> --}}
        <a href="{{ url('/') }}" class="text-nowrap">
            <img src="{{ asset('assets/images/logos/logo-light.svg') }}" alt="Logo-Light" />
        </a>

    </div>
    <div class="scroll-sidebar" data-simplebar="">
        <nav class=" w-full flex flex-col sidebar-nav px-4 mt-5">
            <ul id="sidebarnav" class="text-gray-600 text-sm">
                <li class="text-xs font-bold pb-[5px]">
                    <i class="ti ti-dots nav-small-cap-icon text-lg hidden text-center"></i>
                    <span class="text-xs text-gray-400 font-semibold">HOME</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link gap-3 py-2.5 my-1 text-base  flex items-center relative  rounded-md text-gray-500  w-full"
                        href="{{ url('/') }}">
                        <i class="ti ti-layout-dashboard ps-2  text-2xl"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="text-xs font-bold mb-4 mt-6">
                    <i class="ti ti-dots nav-small-cap-icon text-lg hidden text-center"></i>
                    <span class="text-xs text-gray-400 font-semibold">FEATURES</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link gap-3 py-2.5 my-1 text-base   flex items-center relative  rounded-md text-gray-500  w-full"
                        href="{{ url('/products') }}">
                        <i class="ti ti-package ps-2 text-2xl"></i> <span>Product</span>

                    </a>
                </li>
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link gap-3 py-2.5 my-1 text-base   flex items-center relative  rounded-md text-gray-500  w-full"
                            href="{{ url('/users') }}">
                            <i class="ti ti-user-plus ps-2 text-2xl"></i> <span>User</span>
                        </a>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full"
                        href="{{ url('/sales') }}">
                        <i class="ti ti-shopping-cart ps-2 text-2xl"></i> <span>Sales</span>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>

