<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive('admin.report.user.login.history') }}" role="presentation">
        <a href="{{ route('admin.report.user.login.history') }}" class="nav-link text-dark" type="button">
            <i class="las la-credit-card"></i> @lang('User Login History')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.report.seller.login.history') }}" role="presentation">
        <a href="{{ route('admin.report.seller.login.history') }}" class="nav-link text-dark" type="button">
            <i class="las la-wallet"></i> @lang('Seller Login History')
        </a>
    </li>
</ul>
