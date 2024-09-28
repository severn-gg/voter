<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="assets/styles/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Input Data</h1>
        <a href="index.php">back</a>
        <br>
        <hr>
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Data Pengurus</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Data Pemilih</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="suara-tab" data-bs-toggle="tab" data-bs-target="#suara-tab-pane" type="button" role="tab" aria-controls="suara-tab-pane" aria-selected="false">Data Suara Calon</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="row mt-5 mb-3">
                        <div class="col-4">
                            <form id="formCan" action="insert.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="candidate">
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
                                    <label for="exampleFormControlTextarea1" class="form-label">Visi Misi</label>
                                    <input type="text" name="visi_misi" class="form-control" id="exampleFormControlInput1" placeholder="lorem ipsum">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Sambutan</label>
                                    <textarea name="sambutan" class="form-control" id="exampleFormControlInput1" placeholder="lorem ipsum"></textarea>
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
                                <tbody id="tbodyCan">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="1">
                    <div class="row mt-5 mb-3">
                        <div class="col-4">
                            <form id="formNas" action="insert.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="nasabah">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nomor BA</label>
                                    <input type="hidden" name="user_id" class="form-control">
                                    <input type="text" name="nasabah_id" class="form-control" id="exampleFormControlInput1" placeholder="01-01-056-0000XXXX" required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Nama Anggota</label>
                                    <input type="text" name="nama_nasabah" class="form-control" id="exampleFormControlInput1" placeholder="Keling/Kumang" required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Asal BO</label>
                                    <input type="text" name="asal_bo" class="form-control" id="exampleFormControlTextarea1" placeholder="Branch Office ..." required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Nomor HP</label>
                                    <input type="text" name="no_hp" class="form-control" id="exampleFormControlTextarea1" placeholder="08XXXXXXXXXXX" required>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-success" id="exampleFormControlTextarea1"></input>
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table" id="table_nasabah">
                                <thead>
                                    <th>#</th>
                                    <th>Nama Nasabah</th>
                                    <th>BO</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody id="tbodyNas">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="suara-tab-pane" role="tabpanel" aria-labelledby="suara-tab" tabindex="1">
                    <div class="row mt-5 mb-3">
                        <div class="col-8">
                            <table class="table" id="candidate_votes">
                                <thead>
                                    <th>Nomor Urut</th>
                                    <th>Pict</th>
                                    <th>Nama Calon</th>
                                    <th>Hasil</th>
                                </thead>
                                <tbody id="tbodyCanVotes">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="assets/scripts/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#formCan').trigger('reset');
            $('#formNas').trigger('reset');
            get_data();
        });

        function get_data() {
            $.ajax({
                type: "GET",
                url: "system.php",
                dataType: "JSON",
                success: function(response) {
                    // console.log(response);
                    let data = response.candidate.data;
                    let dataN = response.nasabah.data;
                    let dataV = response.candidate_votes.data;
                    $.each(data, function(i, row) {
                        $('#tbodyCan').append(`
                            <tr>
                                <td>${row.no_urut}</td>
                                <td>${row.candidate_name}</td>                                
                                <td><img src="${row.picture_url}" class="img-thumbnail" style="max-width:auto; height:100px;" alt="..."></td>
                                <td><a class="btn btn-sm btn-warning" id="btn-edit" onclick="edit('candidates','candidate_id','${row.candidate_id}')">Edit</a> <a class="btn btn-sm btn-danger" id="btn-delete" onclick="hapus('candidates','candidate_id','${row.candidate_id}')" value="${row.candidate_id}">Delete</a></td>
                            </tr>
                        `);
                    });

                    $.each(dataN, function(i, row) {
                        $('#tbodyNas').append(`
                            <tr>
                                <td>${row.user_id}</td>
                                <td>${row.nama_nasabah}</td>                                
                                <td>${row.asal_BO}</td>
                                <td><a class="btn btn-sm btn-warning" id="btn-edit" onclick="edit('users','user_id','${row.user_id}')">Edit</a> <a class="btn btn-sm btn-danger" id="btn-delete" onclick="hapus('users','user_id','${row.user_id}')" value="${row.user_id}">Delete</a></td>
                            </tr>
                        `);
                    });

                    $.each(dataV, function(i, row) {
                        $('#tbodyCanVotes').append(`
                            <tr>
                                <td>${row.no_urut}</td>                               
                                <td><img src="${row.picture_url}" class="img-thumbnail" style="max-width:auto; height:100px;" alt="..."></td>                                
                                <td>${row.candidate_name}</td>
                                <td>${row.vote_count}</td>                                
                            </tr>
                        `);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }

        function hapus(table, field, id) {
            $.ajax({
                type: "POST",
                url: "delete_data.php",
                data: {
                    table: table,
                    field: field,
                    id: id,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                    }
                    $('#tbodyCan').empty();
                    $('#tbodyNas').empty();
                    get_data();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }

        function edit(table, field, id) {
            $.ajax({
                type: "POST",
                url: "get_data.php",
                data: {
                    table: table,
                    field: field,
                    id: id,
                },
                dataType: "JSON",
                success: function(response) {
                    let data = response.data;
                    console.log(data[0]);
                    if (table === 'candidates') {
                        $('input[name="nourut"]').val(data[0].no_urut);
                        $('input[name="nama_can"]').val(data[0].candidate_name);
                        $('input[name="visi_misi"]').val(data[0].visi_misi);
                        $('textarea[name="sambutan"]').val(data[0].sambutan);
                        $('input[name="candidate_id"]').val(data[0].candidate_id);
                    } else {
                        $('input[name="nasabah_id"]').val(data[0].nasabah_id);
                        $('input[name="nama_nasabah"]').val(data[0].nama_nasabah);
                        $('input[name="asal_bo"]').val(data[0].asal_BO);
                        $('input[name="no_hp"]').val(data[0].no_hp);
                        $('input[name="user_id"]').val(data[0].user_id);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            })
        }

        tinymce.init({
            selector: 'textarea#default'
        });
    </script>
</body>

</html>