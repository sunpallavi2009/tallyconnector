{!! Form::open([
    'method' => 'DELETE',
    'class' => 'd-inline',
    'route' => ['debit-note.destroy', $debits->id],
    'id' => 'delete-form-' . $debits->id,
]) !!}
<a href="javascript:void(0);" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
   id="delete-form-1" data-bs-original-title="{{ __('Delete') }}">
    <i class="ti ti-trash text-white"></i>
</a>
{!! Form::close() !!}
