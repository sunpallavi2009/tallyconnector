<table class="table mt-10">
    <thead>
        <tr>
            <th>Type</th>
            <th>Count</th>
            <th>INV AMT</th>
            <th>IGST</th>
            <th>CGST</th>
            <th>SGST</th>
            <th>CESS</th>
            <th>TXT AMT</th> 
             
        </tr>
    </thead>
    <tbody>
        @if(is_array($jsonData) && isset($jsonData['data']))
            @foreach ($jsonData['data']['sec_sum'] as $row)
                @if(isset($row['sec_nm']) && ($row['sec_nm'] === 'B2B' || $row['sec_nm'] === 'B2CS' || $row['sec_nm'] === 'CDNR' || $row['sec_nm'] === 'NIL' || $row['sec_nm'] === 'CDNUR' || $row['sec_nm'] === 'B2CL' || $row['sec_nm'] === 'EXP'  ))
                    <tr>
                        <td><a href="{{ route('gstAuth.'.$row['sec_nm']) }}">{{ $row['sec_nm'] }}</a></td>
                        <td>{{ $row['ttl_rec'] }}</td>
                        <td>{{ $row['ttl_val'] ?? '' }}</td>
                        <td>{{ $row['ttl_igst'] ?? '' }}</td>
                        <td>{{ $row['ttl_sgst'] ?? '' }}</td>
                        <td>{{ $row['ttl_cgst'] ?? '' }}</td>
                        <td>{{ $row['ttl_cess'] ?? '' }}</td>
                        <td>{{ $row['ttl_tax'] ?? '' }}</td> 
                        
                        <!-- Add more columns as needed -->
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="5">No data available</td>
            </tr>
        @endif
        </tbody>
</table>
@push('javascript')

@endpush