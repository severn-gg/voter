<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .disabled-card {
            opacity: 0.5;
            /* Reduce opacity to make it appear disabled */
            pointer-events: none;
            /* Disable pointer events to prevent interaction */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <h1>Hello, world!</h1>
            <a href="admin.php">Admin</a>
        </div>

        <div class="row" id="cards">

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        let clickCount = 0;
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "system.php",
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    let data = response.data;
                    $.each(data, function(i, row) {
                        let card = $('<div class="card text-center mx-2 mb-2" style="width: 16rem;">');
                        let cardLink = $('<a href="javascript:void(0)" class="card-link">');
                        cardLink.click(function() {
                            click(row.candidate_id, card);
                        });
                        let cardImg = $('<img src="' + row.picture_url + '" class="card-img-top mt-2" alt="...">');
                        let cardBody = $('<div class="card-body">');
                        let cardText = $('<p class="card-text">' + row.candidate_name + '</p>');

                        cardBody.append(cardText);
                        cardLink.append(cardImg);
                        cardLink.append(cardBody);
                        card.append(cardLink);

                        $('#cards').append(card);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error occurred while fetching data.");
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            });
        });

        function click(id, card) {
            clickCount++; // Increment click count on each click
            alert(id);
            card.addClass('disabled-card');

            if (clickCount >= 3) { // Check if the click count reaches three
                $('.card').not('.disabled-card').addClass('disabled-card'); // Disable all cards except those already disabled
            }
        }
    </script>
</body>

</html>