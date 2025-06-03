<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
      <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">
      <!-- nav bar -->
      <div class="w-100 mb-4 d-flex">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./index.html">
          <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
            <g>
              <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
              <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
              <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
            </g>
          </svg>
        </a>
      </div>

      <p class="text-muted nav-heading mt-4 mb-1">
        <span>المدرسة</span>
      </p>
      <ul class="navbar-nav flex-fill w-100 mb-2">
        {{-- <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.dashboard') }}</span>
          </a>
        </li> --}}
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item w-100">
            <a class="nav-link {{ Request::is('admin/classes*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
              <i class="fe fe-award fe-16"></i>
              <span class="ml-3 item-text">{{ __('admin.classes') }}</span>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link {{ Request::is('admin/muhafezs*') ? 'active' : '' }}" href="{{ route('admin.muhafezs.index') }}">
                <i class="fe fe-award fe-16"></i>
                <span class="ml-3 item-text">{{ __('admin.muhafezs') }}</span>
              </a>
            </li>
          </ul>
      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/students*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
            <i class="fe fe-award fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.students') }}</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/attendance*') ? 'active' : '' }}" href="{{ route('admin.attendances.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.attendances') }}</span>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/quraan*') ? 'active' : '' }}" href="{{ route('admin.quraans.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.quraans') }}</span>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/werds*') ? 'active' : '' }}" href="{{ route('admin.werds.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.werds') }}</span>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/behaviors*') ? 'active' : '' }}" href="{{ route('admin.behaviors.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.behaviors') }}</span>
          </a>
        </li>
      </ul>

      {{-- <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/exams*') ? 'active' : '' }}" href="{{ route('admin.exams.index') }}">
            <i class="fe fe-file-text fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.exams') }}</span>
          </a>
        </li>
      </ul> --}}



      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/summaries*') ? 'active' : '' }}" href="{{ route('admin.summaries.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.summaries') }}</span>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/researchs*') ? 'active' : '' }}" href="{{ route('admin.researchs.index') }}">
            <i class="fe fe-calendar fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.researchs') }}</span>
          </a>
        </li>
      </ul>

      {{-- <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
          <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
            <i class="fe fe-file-text fe-16"></i>
            <span class="ml-3 item-text">{{ __('admin.reports') }}</span>
          </a>
        </li>
      </ul> --}}

    </nav>
  </aside>
