<table class="table table-borderless mb-0" style="width: 100%">
    <tbody>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" colspan="2">
                <img style="max-width: 200px;" src="{{asset('storage/userImages')}}/{{$employee->userInfo->image ?? ''}}" alt="">
            </th>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row">{{__('pages.Full Name')}} :</th>
            <td class="text-muted">{{$employee->name_en}}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row">{{__('pages.Mobile')}} :</th>
            <td class="text-muted">{{$employee->mobile}}</td>
        </tr>
        <tr style="text-align: left" >
            <th class="ps-0" scope="row">{{__('pages.Email')}} :</th>
            <td class="text-muted">{{$employee->email}}</td>
        </tr>
        
        

        @if (($employee->userInfo->retire_date != '') || ($employee->userInfo->retire_date != NULL))
            <tr style="text-align: left">
                <th class="ps-0" scope="row">{{__('pages.Retire Date')}} :</th>
                <td class="text-muted">{{ $employee->userInfo->retire_date ?? '-' }}</td>
            </tr>
        @endif
        
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__("pages.Father's Name")}}</th>
            <td>{{ $employee->userInfo->f_name_en ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__("pages.Mother's Name")}}</th>
            <td>{{ $employee->userInfo->m_name_en ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Date of Birth')}}</th>
            <td>{{ $employee->userInfo->dob ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Gender')}}</th>
            <td>{{ $employee->userInfo->gender ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Religion')}}</th>
            <td>{{ $employee->userInfo->religion ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Birth Certificate')}}</th>
            <td>{{ $employee->userInfo->birth_certificate_no ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.NID number')}}</th>
            <td>{{ $employee->userInfo->nid_no ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Passport Number')}}</th>
            <td>{{ $employee->userInfo->passport_no ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Marital Status')}}</th>
            <td>{{ $employee->userInfo->marital_status ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Mobile Number')}}</th>
            <td>{{ $employee->mobile }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Employee ID')}}</th>
            <td>{{ $employee->userInfo->employee_id ?? '' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Employee Role')}}</th>
            <td>{{ $employee->role->name_en ?? ''}}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Employee Department')}}</th>
            <td>{{ $employee->userInfo->department->name ?? ''}}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Employee Designation')}}</th>
            <td>{{ $employee->userInfo->designation->name ?? ''}}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Employee Office')}}</th>
            <td>{{ $employee->userInfo->office->name ?? ''}}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">Joining Date</th>
            <td>{{ $employee->userInfo->start ? date('d M, Y', strtotime($employee->userInfo->start ?? '')) : 'N/A' }}</td>
        </tr>
        <tr style="text-align: left">
            <th class="ps-0" scope="row" for="">{{__('pages.Signature')}}</th>
            <td class="ps-0" scope="row" for="">
                @if (($employee->userInfo->signature ?? '0') != 0)
                <img style="max-width: 200px;" src="{{public_path('storage\signature')}}\{{$employee->userInfo->signature ?? '-'}}" alt="">
                @endif
                
            </td>
        </tr>
    </tbody>

</table>

<table class="table table-borderless mb-0" style="width: 100%">

    <tbody>
        <tr style="text-align: left">
            <th colspan="2">
                {{__('pages.Present Address')}}
            </th>
            <th colspan="2">
                {{__('pages.Permanent Address')}}
            </th>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.Division')}}
            </th>
            <td>
                {{ $employee->userAddress->presentDivision->name ?? '' }}
            </td>
            <th>
                {{__('pages.Division')}}
            </th>
            <td>
                {{ $employee->userAddress->permanentDivision->name ?? '' }}
            </td>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.District')}}
            </th>
            <td>
                {{ $employee->userAddress->presentDistrictName->name ?? '' }}
            </td>
            <th>
                {{__('pages.District')}}
            </th>
            <td>
                {{ $employee->userAddress->permanentDistrict->name ?? '' }}
            </td>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.Thana/Upazila')}}
            </th>
            <td>
                {{ $employee->userAddress->presentUpazila->name ?? '' }}
            </td>
            <th>
                {{__('pages.Thana/Upazila')}}
            </th>
            <td>
                {{ $employee->userAddress->permanentUpazila->name ?? '' }}
            </td>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.Post Office')}}
            </th>
            <td>
                {{ $employee->userAddress->present_post_office ?? '' }}
            </td>
            <th>
                {{__('pages.Post Office')}}
            </th>
            <td>
                
                {{ $employee->userAddress->permanent_post_office ?? '' }}
            </td>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.Post Code')}}
            </th>
            <td>
                {{ $employee->userAddress->present_post_code ?? '' }}
            </td>
            <th>
                {{__('pages.Post Code')}}
            </th>
            <td>
                {{ $employee->userAddress->permanent_post_code ?? '' }}
            </td>
        </tr>
        <tr style="text-align: left">
            <th>
                {{__('pages.Village/Road')}}
            </th>
            <td>
                {{ $employee->userAddress->present_address ?? '' }}
            </td>
            <th>
                {{__('pages.Village/Road')}}
            </th>
            <td>
                {{ $employee->userAddress->permanent_address ?? '' }}
            </td>
        </tr>
    </tbody>
</table>