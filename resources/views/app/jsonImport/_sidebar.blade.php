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
            <a href="{{ route('jsonImport.sales.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.sales.show' ? ' active' : '' }}">
                {{ __('Sales Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.purchase.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.purchase.show' ? ' active' : '' }}">
                {{ __('Purchase Invoices') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.bank.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.bank.show' ? ' active' : '' }}">
                {{ __('Bank Statement') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.receipt.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.receipt.show' ? ' active' : '' }}">
                {{ __('Receipt Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.payment.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.payment.show' ? ' active' : '' }}">
                {{ __('Payment Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-3">
            <a href="{{ route('jsonImport.journal.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'jsonImport.journal.show' ? ' active' : '' }}">
                {{ __('Journal Voucher') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
