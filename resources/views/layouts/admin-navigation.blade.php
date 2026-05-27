<nav x-data="{ sidebarOpen: false }" class="bg-green-950 text-slate-100 w-full md:w-64 md:min-h-screen flex-shrink-0">

    <!-- Top Section: Logo & Mobile Toggle -->
    <div class="flex items-center justify-between h-16 px-4 bg-slate-900">
        <!-- LOGO -->
        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-wider text-teal-400 flex items-center space-x-2">
            @php $logo = \App\Models\SiteSetting::get('site_logo'); @endphp
            @if($logo)
            <img src="{{ Storage::url($logo) }}" class="h-8 w-20">
            @else
            AETERNA
            @endif
        </a>

        <!-- Mobile Hamburger -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-400 hover:text-white">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>
    </div>

    <!-- Navigation Links -->
    <div :class="{'block': sidebarOpen, 'hidden': md-hidden}" class="hidden md:block px-4 py-4 space-y-1 flex-1">

        <p class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Management</p>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-home class="w-5 h-5" />
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.users.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-user class="w-5 h-5" />
            <span>Users</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.settings.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-wrench-screwdriver class="w-5 h-5" />
            <span>Settings</span>
        </a>

        <a href="{{ route('admin.product-types.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.product-types.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-tag class="w-5 h-5" />
            <span>Product Types</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.categories.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-folder class="w-5 h-5" />
            <span>Categories</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.products.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-beaker class="w-5 h-5" />
            <span>Products</span>
        </a>

        <a href="{{ route('admin.suppliers.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.suppliers.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-truck class="w-5 h-5" />
            <span>Suppliers</span>
        </a>

        <p class="px-2 pt-6 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Operations</p>

        <a href="{{ route('admin.stock-movements.create') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.stock-movements.create') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
            <span>Record Stock</span>
        </a>

        <a href="{{ route('admin.stock-movements.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.stock-movements.index') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-rectangle-stack class="w-5 h-5" />
            <span>Inventory Ledger</span>
        </a>

        <a href="{{ route('admin.sales.index') }}"
            class="flex items-center space-x-3 px-2 py-2 rounded-md text-sm transition-colors 
        {{ request()->routeIs('admin.sales.*') ? 'bg-teal-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <x-heroicon-o-receipt-percent class="w-5 h-5" />
            <span>Sales History</span>
        </a>
    </div>

    <!-- Bottom Section: User Profile -->
    <div class="hidden md:block mt-auto border-t border-slate-700 p-4">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center text-sm font-bold text-white">
                {{ auth()->user()->username }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Logout">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                </button>
            </form>
        </div>
    </div>
</nav>