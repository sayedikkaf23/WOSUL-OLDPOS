<!doctype html>
<html lang="en">

<head>
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
        <h4 class="text-center text-uppercase">Dulani Respondents</h4>
        <hr>

        <table id="example" class="table table-striped table-bordered table-condensed display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Created At</th>
                    <th>Discount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['dulani_results'] as $result)
                    <tr>
                        <td>{{ $result->full_name }}</td>
                        <td>{{ $result->email }}</td>
                        <td>{{ $result->phone_number }}</td>
                        <td>{{ Carbon\Carbon::parse($result->created_at)->format('M d, Y h:i A') }}</td>
                        <td>{{ $result->discount }}</td>
                    </tr>
                @empty
                    <div class="alert alert-info">No Data Found</div>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Created At</th>
                    <th>Discount</th>
                </tr>
            </tfoot>
        </table>


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
        });
    </script>

</body>

</html>
