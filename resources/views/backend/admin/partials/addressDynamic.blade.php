<script>
// dynamic address AJAX for present district
    $("#present_division_id").on('change',function(e){
        e.preventDefault();

        let present_district_id = $("#present_district_id");
        let present_upazila_id = $("#present_upazila_id");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{route('admin.districts')}}",
            data: {_token:$('input[name=_token]').val(),
            division_id: $(this).val()},

            success:function(response){
                $('option', present_district_id).remove();
                $('option', present_upazila_id).remove();

                $('#present_district_id').append('<option value="">--Select District--</option>');
                $('#present_upazila_id').append('<option value="">--Select District First--</option>');

                $.each(response, function(){
                    $('<option/>', {
                        'value': this.id,
                        'text': this.name_en
                    }).appendTo('#present_district_id');
                });
            }

        });
    });

    // dynamic address AJAX for present upazila/thana
    $("#present_district_id").on('change',function(e){
        e.preventDefault();

        let present_upazila_id = $("#present_upazila_id");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{route('admin.upazilas')}}",
            data: {_token:$('input[name=_token]').val(),
            district_id: $(this).val()},

            success:function(response){
                $('option', present_upazila_id).remove();

                $('#present_upazila_id').append('<option value="">--Select Upazila/Thana--</option>');

                $.each(response, function(){
                    $('<option/>', {
                        'value': this.id,
                        'text': this.name_en
                    }).appendTo('#present_upazila_id');
                });
            }

        });
    });

    // dynamic address AJAX for permanent district
    $("#permanent_division_id").on('change',function(e){
        e.preventDefault();

        let permanent_district_id = $("#permanent_district_id");
        let permanent_upazila_id = $("#permanent_upazila_id");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{route('admin.districts')}}",
            data: {_token:$('input[name=_token]').val(),
            division_id: $(this).val()},

            success:function(response){
                $('option', permanent_district_id).remove();
                $('option', permanent_upazila_id).remove();

                $('#permanent_district_id').append('<option value="">--Select District--</option>');
                $('#permanent_upazila_id').append('<option value="">--Select District First--</option>');

                $.each(response, function(){
                    $('<option/>', {
                        'value': this.id,
                        'text': this.name_en
                    }).appendTo('#permanent_district_id');
                });
            }

        });
    });

    // dynamic address AJAX for permanent upazila/thana
    $("#permanent_district_id").on('change',function(e){
        e.preventDefault();

        let permanent_upazila_id = $("#permanent_upazila_id");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{route('admin.upazilas')}}",
            data: {_token:$('input[name=_token]').val(),
            district_id: $(this).val()},

            success:function(response){
                $('option', permanent_upazila_id).remove();
                $('#permanent_upazila_id').append('<option value="">--Select Upazila/Thana--</option>');
                $.each(response, function(){
                    $('<option/>', {
                        'value': this.id,
                        'text': this.name_en
                    }).appendTo('#permanent_upazila_id');
                });
            }

        });
    });
</script>