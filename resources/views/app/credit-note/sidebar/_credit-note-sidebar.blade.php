<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('credit-note.index') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'credit-note.index' ? ' active' : '' }}">
                {{ __('Import Credit Note') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('credit-note.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'credit-note.show' ? ' active' : '' }}">
                {{ __('View Credit Note') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
