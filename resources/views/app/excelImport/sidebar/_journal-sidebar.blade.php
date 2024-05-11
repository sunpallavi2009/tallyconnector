<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('excelImport.journal.create') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.journal.create' ? ' active' : '' }}">
                {{ __('Import Journal Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('excelImport.journal.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.journal.show' ? ' active' : '' }}">
                {{ __('View Journal Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
