@php
    $users = \Auth::user();
    // $currantLang = $users->currentLanguage();
    // $languages = Utility::languages();
@endphp
<header class="dash-header transprent-bg">
{{-- <header class="dash-header {{ Utility::getsettings('transparent_layout') == 1 ? 'transprent-bg' : '' }}"> --}}
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span>
                            <img src="{{ asset('assets/images/avatar/avatar.png') }}"
                                class="rounded-circle mr-1">
                        </span>
                        <span class="hide-mob ms-2">{{ __('Hi,') }} {{ Auth::user()->name }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('central.profile.show') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        <a href="javascript:void(0)" class="dropdown-item"
                            onclick="document.getElementById('logout-form').submit()">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form"> @csrf </form>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                {{-- @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                        <a class="btn btn-primary btn-active-color-primary btn-outline-secondary me-3"
                            href="{{ route('impersonate.leave') }}"><i class="ti ti-ban"></i>
                            {{ __('Exit Impersonation') }}
                        </a>
                    </li>
                @endImpersonating --}}
                <li class="dash-h-item theme_mode">
                    <a class="dash-head-link add_dark_mode me-0" role="button">
                        {{-- <i class="ti {{ Auth::user()->dark_layout == 0 ? 'ti-sun' : 'ti-moon' }}"></i> --}}
                    </a>
                </li>
                {{-- @if (Auth::user()->type != 'Super Admin')
                    @can('manage-announcement')
                        <li class="dash-h-item theme_mode">
                            <a class="dash-head-link  me-0" href="{{ route('show.announcement.list') }}" role="button">
                                <i class="ti ti-confetti"></i>
                            </a>
                        </li>
                    @endcan
                @endif --}}
                {{-- <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" id="kt_activities_toggle"
                        data-bs-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false"
                        aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <span
                            class="bg-danger dash-h-badge
                                @if (auth()->user()->unreadnotifications->count()) dots @endif"><span
                                class="sr-only"></span></span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        <div class="noti-header">
                            <h5 class="m-0">{{ __('Notification') }}</h5>
                        </div>
                        <div class="noti-body ps">
                            @foreach (auth()->user()->notifications->where('read_at', '=', '') as $notification)
                                <div class="d-flex align-items-start my-4">
                                    @if ($notification->type == 'App\Notifications\Superadmin\RegisterNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('New Domain Request') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('New') }}
                                                    {{ isset($notification->data['data']['domain']['email']) ? $notification->data['data']['domain']['email'] : '' }}{{ __(' User Create and') }}
                                                    {{ __('User Domain Name:') }}
                                                    {{ isset($notification->data['data']['domain']['domain_name']) ? $notification->data['data']['domain']['domain_name'] : '' }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $notification->type == 'App\Notifications\Superadmin\ConatctNotification' ||
                                            $notification->type == 'App\Notifications\Admin\ConatctNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('New Enquiry') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('New') }}
                                                    {{ isset($notification->data['data']['email']) ? $notification->data['data']['email'] : '' }}{{ __(' Enquiry Details') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($notification->type == 'App\Notifications\Superadmin\ApproveNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Domain Verified') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Domain') }}
                                                    {{ isset($notification->data['data']['alldata']['domain_name']) ? $notification->data['data']['alldata']['domain_name'] : '' }}{{ __(' is Verified By SuperAdmin') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($notification->type == 'App\Notifications\Superadmin\DisapprovedNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Domain Unverified') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Domain') }}
                                                    {{ isset($notification->data['data']['alldata']['domain_name']) ? $notification->data['data']['alldata']['domain_name'] : '' }}{{ __(' is not Verified By SuperAdmin') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $notification->type == 'App\Notifications\Superadmin\ApproveOfflineNotification' ||
                                            $notification->type == 'App\Notifications\Admin\ApproveOfflineNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Offline Payment Request Verified') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Plan Update Request') }}
                                                    {{ isset($notification->data['data']['alldata']['email']) ? $notification->data['data']['alldata']['email'] : '' }}{{ __(' is Verified By Super Admin') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        $notification->type == 'App\Notifications\Superadmin\DisapprovedOfflineNotification' ||
                                            $notification->type == 'App\Notifications\Admin\DisapprovedOfflineNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Offline Payment Request Unverified') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Request Payment') }}
                                                    {{ isset($notification->data['data']['alldata']['email']) ? $notification->data['data']['alldata']['email'] : '' }}{{ __(' is Disapprove By Super Admin') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($notification->type == 'App\Notifications\Superadmin\SupportTicketNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('New Ticket Opened') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('New') }}
                                                    {{ isset($notification->data['data']['alldata']['ticket_id']) ? $notification->data['data']['alldata']['ticket_id'] : '' }}{{ __(' Ticket Opened') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($notification->type == 'App\Notifications\Superadmin\ReceiveTicketReplyNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Received Ticket Reply') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Ticket id') }}
                                                    {{ isset($notification->data['data']['alldata']['ticket_id']) ? $notification->data['data']['alldata']['ticket_id'] : '' }}{{ __(' New Reply') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($notification->type == 'App\Notifications\Superadmin\SupportTicketReplyNotification')
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-device-desktop"></i>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <a href="javascript:void(0);">
                                                    <h6>{{ __('Send Ticket Reply') }}</h6>
                                                </a>
                                                <a href="javascript:void(0);" class="text-hover-danger"><i
                                                        class="ti ti-x"></i></a>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <p class="mb-0 text-muted">
                                                    {{ __('Your Ticket id') }}
                                                    {{ isset($notification->data['data']['alldata']['ticket_id']) ? $notification->data['data']['alldata']['ticket_id'] : '' }}{{ __(' New Reply') }}
                                                </p>
                                                <span
                                                    class="text-sm ms-2 text-nowrap">{{ Utility::date_time_format($notification->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li> --}}
                {{-- <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ Str::upper($currantLang) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-width dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach ($languages as $language)
                            <a class="dropdown-item @if ($language == $currantLang) text-danger @endif"
                                href="{{ route('change.language', $language) }}">{{ Str::upper($language) }}</a>
                        @endforeach
                    </div>
                </li> --}}
            </ul>
        </div>
    </div>
</header>
{{-- @push('javascript')
    <script>
        $(document).on("click", "#kt_activities_toggle", function() {
            $.ajax({
                url: '{{ route('read.notification') }}',
                data: {
                    _token: $("meta[name='csrf-token']").attr('content')
                },
                method: 'post',
            }).done(function(data) {
                if (data.is_success) {
                    $("#kt_activities_toggle").find(".animation-blink").remove();
                }
            });
        });
    </script>
@endpush --}}
