<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive('admin.report.user.transaction') }}" role="presentation">
        <a href="{{ route('admin.report.user.transaction') }}" class="nav-link text-dark" type="button">
            <i class="las la-credit-card"></i> @lang('User Transaction')
        </a>
    </li>
    <li class="nav-item {{ menuActive('admin.report.seller.transaction') }}" role="presentation">
        <a href="{{ route('admin.report.seller.transaction') }}" class="nav-link text-dark" type="button">
            <i class="las la-wallet"></i> @lang('Seller Transaction')
        </a>
    </li>
</ul>
