<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('excelImport.payment.create') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.payment.create' ? ' active' : '' }}">
                {{ __('Import Payment Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('excelImport.payment.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.payment.show' ? ' active' : '' }}">
                {{ __('View Payment Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
