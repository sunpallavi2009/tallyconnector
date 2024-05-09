{{-- @php
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    // $languages = Utility::languages();
@endphp --}}
<nav class="dash-sidebar light-sidebar">
{{-- <nav class="dash-sidebar light-sidebar {{ Utility::getsettings('transparent_layout') == 1 ? 'transprent-bg' : '' }}"> --}}
    <div class="navbar-wrapper">
        <div class="m-headers logo-col">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                {{-- @if ($users->dark_layout == 1) --}}
                    <img src="{{ asset('assets/images/logo/app-dark-logo.png') }}"
                        class="footer-light-logo">
                {{-- @else
                    <img src="{{ Utility::getsettings('app_dark_logo') ? Utility::getpath('logo/app-dark-logo.png') : asset('assets/images/logo/app-dark-logo.png') }}"
                        class="footer-dark-logo">
                @endif --}}
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                <li class="dash-item dash-hasmenu">
                    <a href="{{ route('dashboard') }}" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
               
                        {{-- <li class="dash-item dash-hasmenu {{ request()->is('excelImport*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('excelImport.index') }}"><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('Excel Import') }}</span>
                            </a>
                        </li>

                        <li class="dash-item dash-hasmenu {{ request()->is('sales*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('sales.index') }}"><span class="dash-micon">
                                    <i class="ti ti-file-invoice"></i></span>
                                <span class="dash-mtext">{{ __('Sales') }}</span>
                            </a>
                        </li>

                        <li class="dash-item dash-hasmenu {{ request()->is('purchase*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('purchase.index') }}"><span class="dash-micon">
                                    <i class="ti ti-report-money"></i></span>
                                <span class="dash-mtext">{{ __('Purchase') }}</span>
                            </a>
                        </li>


                        <li class="dash-item dash-hasmenu {{ request()->is('ecommerce*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('ecommerce.index') }}"><span class="dash-micon">
                                    <i class="fas fa-file"></i></span>
                                <span class="dash-mtext">{{ __('Ecommerce') }}</span>
                            </a>
                        </li> --}}

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('Inventory') }}</span>
                            </a>
                        </li>

                        {{-- <li class="dash-item dash-hasmenu {{ request()->is('bank-statement*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('bank-statement.index') }}"><span class="dash-micon">
                                    <i class="ti ti-building-bank"></i></span>
                                <span class="dash-mtext">{{ __('Bank Satatement') }}</span>
                            </a>
                        </li> --}}

                        {{-- <li class="dash-item dash-hasmenu {{ request()->is('credit-note*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('credit-note.index') }}"><span class="dash-micon">
                                    <i class="ti ti-building-bank"></i></span>
                                <span class="dash-mtext">{{ __('Credit Note') }}</span>
                            </a>
                        </li> --}}

                        {{-- <li class="dash-item dash-hasmenu {{ request()->is('debit-note*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('debit-note.index') }}"><span class="dash-micon">
                                    <i class="ti ti-building-bank"></i></span>
                                <span class="dash-mtext">{{ __('Debit Note') }}</span>
                            </a>
                        </li> --}}

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="ti ti-switch"></i></span>
                                <span class="dash-mtext">{{ __('Other Vouchers') }}</span>
                            </a>
                        </li>

                        {{-- <li class="dash-item dash-hasmenu {{ request()->is('gstr1*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('gstr1.index') }}"><span class="dash-micon">
                                    <i class="fas fa-file"></i></span>
                                <span class="dash-mtext">{{ __('GSTR1') }}</span>
                            </a>
                        </li> --}}

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('GSTR2B') }}</span>
                            </a>
                        </li>

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('Einvoice') }}</span>
                            </a>
                        </li>

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('GST reconcilation') }}</span>
                            </a>
                        </li>

                        <li class="dash-item dash-hasmenu {{ request()->is('#*') ? 'active' : '' }}">
                            <a class="dash-link" href=""><span class="dash-micon">
                                    <i class="fas fa-file-excel"></i></span>
                                <span class="dash-mtext">{{ __('Einvoice Reconcilation') }}</span>
                            </a>
                        </li>
              
            </ul>
        </div>
    </div>
</nav>
