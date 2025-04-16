<img height="50" width="150" src="{{ $image }}" alt="">

<h4 style="text-align: center;">PROFIT AND LOSS REPORT</h4>

<style>
    table{
        border-collapse: collapse; 
    }
</style>

<table>
    <thead>
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
            
            <th style="text-align: center; font-weight: bolder;">Project Value v/s Project Expense</th>
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
            <th style="text-align: center; font-weight: bolder;">Project Value v/s Project Earning</th>
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
</table>

<br>
<br>

<div>
    <span><strong>ServicEngine Ltd.</strong></span>
    <br>
    <span><strong><b>Branch Office:</b></strong> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100</span>
    <span><b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) </p>
    <span>Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176</span>
    <span>sebpo.com</span>
</div>

<!-- end table -->