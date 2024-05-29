
<a class="btn btn-sm small btn btn-warning" href="{{ route('tenants.edit', $tenant->id) }}" data-bs-toggle="tooltip"
    data-bs-placement="bottom" data-bs-original-title="{{ __('Edit') }}">
    <i class="ti ti-edit text-white"></i>
</a>

{!! Form::open([
    'method' => 'DELETE',
    'class' => 'd-inline',
    'route' => ['tenants.destroy', $tenant->id],
    'id' => 'delete-form-' . $tenant->id,
]) !!}
<a href="javascript:void(0);" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
    id="delete-form-1" data-bs-original-title="{{ __('Delete') }}">
    <i class="ti ti-trash text-white"></i>
</a>
{!! Form::close() !!}

