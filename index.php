<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="assets/styles/bootstrap.min.css" rel="stylesheet">
    <style>
        .disabled-card {
            opacity: 0.5;
            /* Reduce opacity to make it appear disabled */
            pointer-events: none;
            /* Disable pointer events to prevent interaction */
        }

        .card-img-top {
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .card {
            padding: 1.5em 0.5em 0.5em;
            text-align: center;
            /* border-radius: 0.5em; */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-weight: bold;
            font-size: 1.5em;
        }

        /* .btn{
            border-radius: 2em;
            padding: 0.5em 0.5em;
        } */
    </style>
</head>

<body>
    <div class="container text-center">
        <div class="row mt-5 mb-4">
            <div class="col-lg">
                <h1>Calon Pengurus</h1>
            </div>
        </div>
        <div class="row" id="cards" style="display: flex; flex-wrap: wrap; justify-content: center;">

        </div>

        <div class="row" id="button">

        </div>
    </div>

    <script src="assets/scripts/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        let clickCount = 0;
        let selectedCards = []; // Store selected cards
        let userId; // Store selected cards

        $(document).ready(function() {
            $('#loginNas').trigger('reset');
            get_candidate();
        });

        function get_candidate() {
            $.ajax({
                type: "GET",
                url: "/system.php",
                dataType: "JSON",
                success: function(response) {
                    // console.log(response);
                    let data = response.candidate.data;
                    if (data.length === 0) {
                        $('#cards').append('Candidate Belum di Input');
                    }
                    $.each(data, function(i, row) {
                        let card = $('<div class="card text-center mx-2 mb-3" style="width: 18rem; position: relative;">'); // Added relative positioning
                        let cardLink = $('<a href="javascript:void(0)" class="mt-auto btn btn-danger btn-block">Pilih</a>');

                        // Create a flex container for the 'Riwayat' and 'Sambutan' buttons
                        let cardRiw = $('<div class="d-flex mt-2">');
                        let btnSambutan = $('<a href="#" class="btn btn-primary flex-fill">Sambutan</a>'); // Added flex-fill

                        cardRiw.append(btnSambutan); // Add both buttons in the same flex row                        

                        let img = row.picture_url;
                        let name = row.candidate_name;
                        let cardImg = $('<img src="' + img + '" class="card-img-top mt-2 mb-3" alt="...">');
                        let cardBody = $('<div class="card-body d-flex flex-column">');
                        let cardTitle = $('<h5 class="card-title">' + name + '</h5>');
                        let cardText = $('<p class="card-text">' + row.visi_misi + '</p>');

                        // Append elements to card body
                        cardBody.append(cardImg);
                        cardBody.append(cardTitle);
                        cardBody.append(cardText);
                        cardBody.append(cardLink); // Append the "Pilih" button
                        cardBody.append(cardRiw); // Append the row of 'Riwayat' and 'Sambutan' buttons
                        card.append(cardBody);

                        $('#cards').append(card);

                        cardLink.click(function() {
                            click(row.candidate_id, row.candidate_name, card, img, name);
                        });

                        btnSambutan.click(function() {
                            samShow(img, name, row.sambutan);
                        });

                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            });
        }

        function samShow(img, name, sambutan) {
            // Create overlay and blur effect
            let overlay = $(`
                        <div class="overlay">
                        </div>
                    `);

            // CSS for the overlay
            overlay.css({
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%',
                'background-color': 'rgba(0, 0, 0, 0.7)', // Semi-transparent background
                'z-index': '1024', // Ensure it's above everything
                'display': 'flex',
                'justify-content': 'center',
                'align-items': 'center',
                'backdrop-filter': 'blur(1px)' // Blur effect
            });

            $('body').append(overlay);
            $('.modal-body').html('');
            $('.modal-header').html('');
            $('.modal-header').append(`<h5 class="modal-title" id="staticBackdropLabel"><small>Sambutan: </small>${name}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>`);
            $('.modal-body').append(`<p>${sambutan}</p>`);
            $('.modal-dialog').addClass('modal-lg');
            $('.modal-header').show();
            $('#staticBackdrop').modal('show');

            // Handle 'Pilih Ulang' button inside the modal (reset selections)
            $('.btn-close').click(function() {
                overlay.remove(); // Remove overlay
                $('#staticBackdrop').modal('hide'); // Hide the modal
            });
        }

        function click(candidateId, candidateName, card, img, name) {
            if (!card.hasClass('selected')) {
                clickCount++; // Increment click count on each click

                // Add checkmark to the card
                let checkMark = $('<div class="checkmark">&#10003;</div>'); // Unicode checkmark or use an icon like FontAwesome
                checkMark.css({
                    'position': 'absolute',
                    'top': '20px',
                    'right': '10px',
                    'font-size': '114px',
                    'color': 'green',
                    'font-weight': 'bold'
                });

                card.append(checkMark);
                card.addClass('selected'); // Mark the card as selected
                selectedCards.push(candidateId); // Add selected candidate to the list    

                if (clickCount >= 1) {
                    $('.card').not('.selected').addClass('disabled-card'); // Disable remaining unselected cards

                    // Create overlay and blur effect
                    let overlay = $(`
                        <div class="overlay">
                        </div>
                    `);

                    // CSS for the overlay
                    overlay.css({
                        'position': 'fixed',
                        'top': '0',
                        'left': '0',
                        'width': '100%',
                        'height': '100%',
                        'background-color': 'rgba(0, 0, 0, 0.7)', // Semi-transparent background
                        'z-index': '1024', // Ensure it's above everything
                        'display': 'flex',
                        'justify-content': 'center',
                        'align-items': 'center',
                        'backdrop-filter': 'blur(1px)' // Blur effect
                    });

                    $('body').append(overlay);

                    // Show the modal directly when clickCount reaches 3
                    $('.modal-header').hide();
                    $('.modal-body').html('');
                    $('.modal-dialog').removeClass('modal-lg');
                    $('#staticBackdrop').modal('show');
                    $('.modal-body').append(`
                        <img src="${img}" alt="avatar" class="rounded-circle position-absolute top-0 start-50 translate-middle h-50" />
                        <br/>
                        <div class="row">
                            <form id="loginNas" action="get_data.php" method="post" enctype="multipart/form-data">
                                <h3 class="pt-5 my-3" id="nameSelected">Anda Memilih : ${name}</h3>
                                <br>                            
                                <input type="hidden" name="action" value="loginNas">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nasabah_id" class="form-control" placeholder="01-01-056-0000XXXX" required>
                                    <label for="floatingInput">Nomor BA</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="no_hp" class="form-control" id="exampleFormControlInput1" placeholder="08XXXXXXXXXXX" required>
                                    <label for="floatingInput">Nomor HP</label>
                                </div>
                                <div class="mb-3" id="btn-vote">
                                    <button type="submit" id="selesaiButton" class="btn btn-primary">Kirim</button>
                                    <button type="button" id="pilihUlangButton" class="btn btn-secondary">Batal</button>
                                </div>
                            </form>                    
                        </div>                        
                    `);

                    // Handle 'Pilih Ulang' button inside the modal (reset selections)
                    $('#pilihUlangButton').click(function() {
                        overlay.remove(); // Remove overlay
                        clickCount = 0; // Reset the count
                        $('.card').removeClass('selected disabled-card'); // Reset the cards
                        selectedCards = []; // Clear selected candidates array
                        $('.checkmark').remove(); // Remove checkmarks
                        $('#staticBackdrop').modal('hide'); // Hide the modal
                    });

                    // Remove any previously attached submit event handlers to avoid duplicates
                    $('#loginNas').off('submit').on('submit', function(e) {
                        e.preventDefault();

                        $.ajax({
                            type: "POST",
                            url: "system.php",
                            data: $('#loginNas').serialize(),
                            dataType: "JSON",
                            success: function(response) {
                                if (response.nas.error) {
                                    // Handle the error response
                                    alert(response.nas.error);
                                    $('#loginNas').trigger('reset');
                                } else {
                                    let dataNas = response.nas.data;
                                    if (dataNas.length !== 0) {
                                        $('#verify').empty();
                                        userId = dataNas.user_id;

                                        $.ajax({
                                            type: "POST",
                                            url: "vote.php",
                                            data: {
                                                user_id: userId,
                                                candidates: selectedCards
                                            },
                                            dataType: "JSON",
                                            success: function(response) {
                                                alert("Data has been submitted!");
                                                $('#loginNas').trigger('reset');
                                                $('#btnSam').click(function() {
                                                    let sambutan = $(this).data('sambutan');
                                                    $('#staticBackdrop').modal('show');
                                                    $('#imgSelected').attr('src', img);
                                                    $('#nameSelected').text('Anda Memilih : ' + name);
                                                    $('#bodyModal').append(`<p>${sambutan}</p>`);
                                                })
                                                overlay.remove();
                                                clickCount = 0;
                                                $('.card').removeClass('selected disabled-card');
                                                selectedCards = [];
                                                $('.checkmark').remove();
                                                $('#staticBackdrop').modal('hide');
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                alert("Error submitting data!");
                                                $('#loginNas').trigger('reset');
                                                overlay.remove();
                                                clickCount = 0;
                                                $('.card').removeClass('selected disabled-card');
                                                selectedCards = [];
                                                $('.checkmark').remove();
                                                $('#staticBackdrop').modal('hide');
                                            }
                                        });
                                    } else {
                                        alert("Data Anggota tidak ditemukan!");
                                        $('#loginNas').trigger('reset');
                                    }
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log("Error occurred while fetching data.");
                            }
                        });
                    });
                }
            } else {
                alert(candidateName + " is already selected.");
            }
        }
    </script>
</body>

<!-- Modal -->
<div class="modal top fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered text-center d-flex justify-content-center">
        <div class="modal-content w-75">
            <div class="modal-header">

            </div>
            <div class="modal-body p-4">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->


</html>