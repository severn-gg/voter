<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Hello, world!</h1>
        <a href="index.php">back</a>
        <br>
        <hr>
        <div class="row">
            <div class="col-4">
                <form action="insert.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Nomor Urut</label>
                        <input type="hidden" name="candidate_id" class="form-control">
                        <input type="text" name="nourut" class="form-control" id="exampleFormControlInput1" placeholder="1,2,3,..." required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Nama Calon</label>
                        <input type="text" name="nama_can" class="form-control" id="exampleFormControlInput1" placeholder="Keling/Kumang" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">File</label>
                        <input type="file" name="picture" class="form-control" id="exampleFormControlTextarea1" required>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-success" id="exampleFormControlTextarea1"></input>
                    </div>
                </form>
            </div>
            <div class="col-8">
                <table class="table" id="table_calon">
                    <thead>
                        <th>Nomor Urut</th>
                        <th>Nama Calon</th>
                        <th>Pict</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            $.ajax({
                type: "GET",
                url: "system.php",
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    let data = response.data;
                    $.each(data, function(i, row) {
                        $('#tbody').append(`
                            <tr>
                                <td>${row.no_urut}</td>
                                <td>${row.candidate_name}</td>                                
                                <td><img src="${row.picture_url}" class="img-thumbnail" style="max-width:auto; height:100px;" alt="..."></td>
                                <td><a class="btn btn-sm btn-warning" id="btn-edit" onclick="edit('${row.candidate_id}')">Edit</a> <a class="btn btn-sm btn-danger" id="btn-delete" onclick="del_calon('${row.candidate_id}')"value="${row.candidate_id}">Delete</a></td>
                            </tr>
                        `);
                    })
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }

        function del_calon(id) {
            $.ajax({
                type: "POST",
                url: "delete_calon.php",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                    }
                    $('#tbody').html('empty');
                    get_data();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }

        function edit(id) {
            $.ajax({
                type: "POST",
                url: "get_data.php",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(response) {
                    let data = response['data'];
                    console.log(data[0]);
                    $('input[name="nourut"]').val(data[0].no_urut);
                    $('input[name="nama_can"]').val(data[0].candidate_name);
                    $('input[name="candidate_id"]').val(data[0].candidate_id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }
    </script>
</body>

</html>