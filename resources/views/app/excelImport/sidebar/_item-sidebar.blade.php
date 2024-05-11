<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('excelImport.items.create') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.items.create' ? ' active' : '' }}">
                {{ __('Import Item') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('excelImport.items.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'excelImport.items.show' ? ' active' : '' }}">
                {{ __('View Item') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
