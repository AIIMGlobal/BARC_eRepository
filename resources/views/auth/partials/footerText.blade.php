<div style="margin-top: 20px; font-size: 14px;" id="footerText">
    @if($global_setting->logo && Storage::exists('public/logo/' . $global_setting->logo))
        <p class="mb-0">Design & Developed By: <a href="https://sebpo.com/" style="color: #000; text-decoration: none;"><img src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt="" style="padding: 5px; background: #fff; border-radius: 5px; max-height: 35px;"> ServicEngine Ltd.</a></p>
    @else
        <p class="mb-0">Design & Developed By: <a href="https://sebpo.com/" style="color: #000; text-decoration: none;"><img src="{{ 'https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg' }}" alt="" style="padding: 5px; background: #fff; border-radius: 5px; max-height: 35px;"> ServicEngine Ltd.</a></p>
    @endif
</div>