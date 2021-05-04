<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    {{-- <div class="c-sidebar-brand d-lg-down-none">
        <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#full"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#signet"></use>
        </svg>
    </div> --}}
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{ route('home') }}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg> Dashboard<span class="badge badge-info"></span>
            </a>
        </li>

        @if (auth()->user()->is_admin)
            <li class="c-sidebar-nav-title">Admin</li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{ route('admin.pages.index') }}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                    </svg> Pages
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{ route('admin.pages.index') }}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                    </svg> Breadcrumb
                </a>
            </li>

            <li class="c-sidebar-nav-title">{{ __('Manage Checklists') }}</li>
            {{-- Create checklist group --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link" href="{{ route('admin.checklist_groups.create') }}">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                    </svg> {{ __('Create new checklist group') }}
                </a>
            </li>
            @foreach (\App\Models\ChecklistGroup::with('checklists')->get() as $group)
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle"
                        href="{{ route('admin.checklist_groups.edit', $group->id) }}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}"></use>
                        </svg>{{ $group->name }}
                    </a>

                    <ul class="c-sidebar-nav-dropdown-items">
                        {{-- Create checklist --}}
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link"
                                href="{{ route('admin.checklist_groups.checklists.create', $group) }}"><span
                                    class="c-sidebar-nav-icon"></span>{{ __('Create new checklist') }}
                            </a>
                        </li>
                        @foreach ($group->checklists as $checklist)
                            <li class="c-sidebar-nav-item">
                                <a class="c-sidebar-nav-link"
                                    href="{{ route('admin.checklist_groups.checklists.edit', [$group, $checklist]) }}"><span
                                        class="c-sidebar-nav-icon"></span>{{ $checklist->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        @endif

            <li class="c-sidebar-nav-title">{{ __('Account') }}</li>
            <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg> {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent"
        data-class="c-sidebar-minimized"></button>
</div>
