<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.index') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.index' ? ' active' : '' }}">
                {{ __('Ledger Master') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.items.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.items.show' ? ' active' : '' }}">
                {{ __('Item Master') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Sales Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Purchase Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Bank Statement') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Receipt Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Payment Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('excelImport.ledgers.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.ledgers.show' ? ' active' : '' }}">
                {{ __('Journal Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
