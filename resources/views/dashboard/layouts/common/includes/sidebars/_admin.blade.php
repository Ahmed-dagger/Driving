<div class="menu-item">
    <div class="pb-2 menu-content">
        <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{check_guard()->name}} Dashboard</span>
    </div>
</div>


<div class="menu-item">
    <a class="menu-link {{ request()->routeIS('admin.settings') ? 'active' : '' }}" href="{{route('admin.settings')}}">
        <span class="menu-icon">
            <i class="bi bi-grid fs-3"></i>
        </span>
        <span class="menu-title">{{ trans('dashboard/admin.settings') }}</span>
    </a>
</div>

<div class="menu-item">
    <a class="menu-link {{ request()->routeIS('admin.codes.index') ? 'active' : '' }}" href="{{route('admin.codes.index')}}">
        <span class="menu-icon">
            <i class="bi bi-grid fs-3"></i>
        </span>
        <span class="menu-title">{{ trans('dashboard/admin.codes') }}</span>
    </a>
</div>

<div class="menu-item">
    <a class="menu-link {{ request()->routeIS('admin.learners.index') ? 'active' : '' }}" href="{{route('admin.learners.index')}}">
        <span class="menu-icon">
            <i class="bi bi-grid fs-3"></i>
        </span>
        <span class="menu-title">{{ trans('dashboard/admin.learners') }}</span>
    </a>
</div>

<div class="menu-item">
    <a class="menu-link {{ request()->routeIS('admin.instructors.index') ? 'active' : '' }}" href="{{route('admin.instructors.index')}}">
        <span class="menu-icon">
            <i class="bi bi-grid fs-3"></i>
        </span>
        <span class="menu-title">{{ trans('dashboard/admin.instructors') }}</span>
    </a>
</div>

<div class="menu-item">
    <a class="menu-link {{ request()->routeIS('admin.packages.index') ? 'active' : '' }}" href="{{route('admin.packages.index')}}">
        <span class="menu-icon">
            <i class="bi bi-grid fs-3"></i>
        </span>
        <span class="menu-title">{{ trans('dashboard/admin.packages') }}</span>
    </a>
</div>
