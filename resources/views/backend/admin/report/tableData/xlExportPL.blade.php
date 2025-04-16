<table>
    <thead>
        <tr>
            <th colspan="7">
                @if ($image)
                    <img width='100px' src="{{ $image }}" alt="">
                @endif
            </th>
        </tr>

        <tr>
            <th colspan="7" style="text-align: center; font-size: 20px; font-weight: bolder;">
                <h1>PROFIT AND LOSS REPORT</h1>
            </th>
        </tr>

        <tr>
            <th colspan="7"> </th>
        </tr>
        
        <tr>
            @php
                $totalEarning = 0;
                $totalExpense = 0;
                $netProfit = 0;
                $projectIncVatTax = 0;
                // $netProfit += $project_value->project_value ?? 0;
                // $netProfit = $netProfit - ($project_value->vat_tax ?? 0);
                $projectExVatTax = ($project_value->project_value ?? 0) - ($project_value->vat_tax ?? 0);
                
                if (count($projectEarnings) > 0) {
                    foreach ($projectEarnings as $projectEarning) {
                        $totalEarning += $projectEarning->amount ?? 0;
                        $netProfit += $projectEarning->amount ?? 0;
                    }
                }

                if (count($projectExpenses) > 0) {
                    foreach ($projectExpenses as $projectExpense) {
                        $totalExpense += $projectExpense->amount ?? 0;
                        $netProfit = $netProfit - ($projectExpense->amount ?? 0);
                    }
                }
            @endphp
            
            <th colspan="7" style="text-align: center; font-weight: bolder;">Project Value v/s Project Expense</th>
        </tr>

        <tr>
            <th colspan="4" style="text-align: right">Project Value (Including Vat and Tax)</th>
            <td colspan="3" style="text-align: left">{{ number_format((float)($project_value->project_value ?? 0) ?? 0, 2, '.', '') }}</td>
        </tr>

        @foreach ($projectExpenses as $projectExpense)
            <tr>
                <td colspan="4" style="text-align: right">{{ $projectExpense->purpose }}</td>
                <td colspan="3" style="text-align: left">{{ number_format((float)$projectExpense->amount ?? 0, 2, '.', '') }}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="4" style="text-align: right">Total Expense</th>
            <td colspan="3" style="text-align: left">{{ number_format((float)$totalExpense ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr></tr>

        <tr>
            <th colspan="7" style="text-align: center; font-weight: bolder;">Project Value v/s Project Earning</th>
        </tr>

        <tr>
            <th colspan="4" style="text-align: right">Project Value (Including Vat and Tax)</th>
            <td colspan="3" style="text-align: left">{{ number_format((float)($project_value->project_value ?? 0) ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr>
            <th colspan="4" style="text-align: right">Project Value (Excluding vat and tax)</th>
            <td colspan="3" style="text-align: left">{{ number_format((float)($projectExVatTax ?? 0) ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: right">Vat and Tax</td>
            <td colspan="3" style="text-align: left">{{ number_format((float)($project_value->vat_tax ?? 0) ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: right">Total Earning</td>
            <td colspan="3" style="text-align: left">{{ number_format((float)$totalEarning ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: right">Total Expense</td>
            <td colspan="3" style="text-align: left">{{ number_format((float)$totalExpense ?? 0, 2, '.', '') }}</td>
        </tr>

        <tr></tr>

        <tr style="background: #2453DE">
            <td colspan="4" style="text-align: right; color: #fff; font-weight: 700;">Net Profit</td>
            <td colspan="3" style="text-align: left; color: #fff; font-weight: 700;">{{ number_format((float)$netProfit ?? 0, 2, '.', '') }}</td>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <td colspan="7"> </td>
        </tr>

        <tr>
            <td colspan="7">
                <p><strong>ServicEngine Ltd.</strong></p>
            </td>
        </tr>

        <tr>
            <td colspan="7">
                <p><strong><b>Branch Office:</b></strong> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100</p>
            </td>
        </tr>

        <tr>
            <td colspan="7">
                <b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) 
            </td>
        </tr>

        <tr>
            <td colspan="7">
                Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176
            </td>
        </tr>

        <tr>
            <td colspan="7">
                sebpo.com
            </td>
        </tr>
    </tfoot>
    
    <!-- end tbody -->
</table>
<!-- end table -->