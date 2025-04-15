<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive(['admin.report.user.notification.history', 'admin.users.notification.log']) }}" role="presentation">
        <a href="{{ route('admin.report.user.notification.history') }}" class="nav-link text-dark" type="button">
            <i class="las la-credit-card"></i> @lang('User Notification History')
        </a>
    </li>
    <li class="nav-item {{ menuActive(['admin.report.seller.notification.history', 'admin.sellers.notification.log']) }}" role="presentation">
        <a href="{{ route('admin.report.seller.notification.history') }}" class="nav-link text-dark" type="button">
            <i class="las la-wallet"></i> @lang('Seller Notification History')
        </a>
    </li>
</ul>
