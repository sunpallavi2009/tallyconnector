<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('excelImport.purchase.create') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.purchase.create' ? ' active' : '' }}">
                {{ __('Import Purchase Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('excelImport.purchase.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.purchase.show' ? ' active' : '' }}">
                {{ __('View Purchase Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
