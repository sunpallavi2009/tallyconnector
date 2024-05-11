<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('excelImport.bank.create') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.bank.create' ? ' active' : '' }}">
                {{ __('Import Bank Statement') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('excelImport.bank.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.bank.show' ? ' active' : '' }}">
                {{ __('View Bank Statement') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
