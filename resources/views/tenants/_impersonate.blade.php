@foreach($tenant->domains as $domain)
        <a href="{{ !empty($domain->domain) ? 'http://' . $domain->domain . ':8000' : '#' }}" target="_blank" class="btn btn-sm small btn btn-info">Impersonate</a>
@endforeach