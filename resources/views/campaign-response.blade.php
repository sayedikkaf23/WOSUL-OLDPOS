<!doctype html>
<html lang="en">

<head>
    <meta name="_token" content="{{ csrf_token() }}">
    <!-- Required meta tags -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('website/fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('website/fonts/arabic-font.css') }}">

</head>

<body>


<div class="container pb-5 table-responsive">

    <hr>
    <h4 class="text-center text-uppercase">Campaign Respondents</h4>
    <hr>

    <table id="example" class="table table-striped table-bordered table-condensed display nowrap" style="width:100%">
        <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Created At</th>
            <th>PT</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data['campaign_results'] as $result)
            <tr id="data-{{ $result->id }}">
                <td>{{ $result->full_name }}</td>
                <td>{{ $result->email }}</td>
                <td>{{ $result->phone_number }}</td>
                <td>{{ Carbon\Carbon::parse($result->created_at)->format('M d, Y h:i A') }}</td>
                <td>{{ ($result->pt != '') ? $result->pt : '-'  }}</td>
                <td>
                    <select name="status" id="status">
                        <option value="0" @php echo $result->status==0?'selected':''; @endphp>Open</option>
                        <option value="1" @php echo $result->status==1?'selected':''; @endphp>In Progress</option>
                        <option value="2" @php echo $result->status==2?'selected':''; @endphp>Closed</option>
                        <option value="3" @php echo $result->status==3?'selected':''; @endphp>Not interested</option>
                    </select>
                </td>
            </tr>
        @empty
            <div class="alert alert-info">No Data Found</div>
        @endforelse

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>PT</th>
            <th>Hits</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @forelse($data['campaign_visits'] as $result)
            <tr>
                <td align="left" class="text-left">{{ $result->pt }}</td>
                <td align="left" class="text-left">{{ $result->hits }}</td>
                <td align="left" class="text-left"></td>
                <td align="left" class="text-left"></td>
                <td align="left" class="text-left"></td>
                <td align="left" class="text-left"></td>
            </tr>
        @empty
            <div class="alert alert-info">No Data Found</div>
        @endforelse

        </tbody>

    </table>
    <input type="hidden" value="@php echo app()->getLocale(); @endphp" id="lang" name="lang">
    {{-- <hr>
    <h4 class="text-center text-uppercase mt-5">Campaign Hits</h4>
    <hr>
     --}}
    {{-- <table id="example" class="table table-striped table-bordered table-condensed display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>PT</th>
                <th>Hits</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['campaign_visits'] as $result)
                <tr>
                    <td>{{ $result->pt }}</td>
                    <td>{{ $result->hits }}</td>
                </tr>
            @empty
                <div class="alert alert-info">No Data Found</div>
            @endforelse
        </tbody>
    </table> --}}


</div>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            ordering: false,
            pageLength: 20,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
        $(document).on('change','#status',function (){
            /*var lang = $('#lang').val();
            var status = $(this).val();
            var campaign_id = $(this).closest('tr').attr('id').replace('data-','');
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);

            $.ajax({
                url: '/' + lang + '/campaign/change_status',
                type: "POST",
                data: formData,
                success: function (response) {
                    console.log('response', response);
                },
                error: function (error) {
                    console.log('Error', error);
                }
            });*/
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var status = $(this).val();
            var campaign_id = $(this).closest('tr').attr('id').replace('data-','');
            var lang = $('#lang').val();

            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('status', status);
            formData.append('campaign_id', campaign_id);

            $.ajax({
                url: "/"+lang+"/campaign/change_status",
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function(resp) {
                    if(resp.status){
                        alert('Status changed successfully!');
                    }
                }
            });
        });
    });
</script>

</body>

</html>
