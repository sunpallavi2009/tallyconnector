<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-6">
            <a href="{{ route('debit-note.index') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'debit-note.index' ? ' active' : '' }}">
                {{ __('Import Debit Note') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6">
            <a href="{{ route('debit-note.show') }}"
               class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'debit-note.show' ? ' active' : '' }}">
                {{ __('View Debit Note') }}
                <div class="float-end">
                    <i class="ti ti-chevron-right"></i>
                </div>
            </a>
        </div>

    </div>
</div>
