<style>
    .rounded-circle {
        padding: 5px;
        margin: 5px;
    }

    .ml-8 {
        margin-left: 10px;
        margin-right: 10px;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 999;
    }

</style>
<div class="col-sm-12">
    <div class="row">

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.b2b') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.b2b' ? ' active' : '' }}">
                {{ __('B2B') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.b2cs') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.b2cs' ? ' active' : '' }}">
                {{ __('B2CS') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.b2cl') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.b2cl' ? ' active' : '' }}">
                {{ __('B2CL') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.cdnr') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.cdnr' ? ' active' : '' }}">
                {{ __('CDNR') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.cdnur') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.cdnur' ? ' active' : '' }}">
                {{ __('CDNUR') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.exp') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.exp' ? ' active' : '' }}">
                {{ __('EXP') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('dashboard') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'dashboard' ? ' active' : '' }}">
                {{ __('HSN') }}
            </a>
        </div>

        <div class="col-xl-1 ml-8">
            <a href="{{ route('gstr1.nil') }}"
               class="list-group-item list-group-item-action border-0 text-center rounded-circle {{ Request::route()->getName() == 'gstr1.nil' ? ' active' : '' }}">
                {{ __('NIL') }}
            </a>
        </div>

    </div>
</div>
