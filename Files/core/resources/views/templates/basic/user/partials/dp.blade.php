<div class="user">
    <span class="side-sidebar-close-btn"><i class="las la-times"></i></span>

    <div class="thumb">
        <a href="{{ route('user.profile.setting') }}">
            <img src="{{ getAvatar(getFilePath('userProfile') . '/' . auth()->user()->image) }}" alt="@lang('user')">
        </a>
    </div>
    <div class="content">
        <h6 class="title"><a class="text--base cl-white" href="javascript:void(0)">{{ auth()->user()->fullname }}</a></h6>
    </div>
</div>
