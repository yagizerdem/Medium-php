<?php
session_start();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="./styles/index.css">
</head>
<body>
    <?php include('./partialViews/header.php')?>


    <?php
if (isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated']) {
    echo ('<form action="writepost.php" method="get">
            <button type="submit" class="writebtn">
                <i class="fa-solid fa-pen"></i>
            </button>
          </form>');
}
?>
<div class="container main-container">
    <div id="post" class="post">
    </div>  
    <button id="fetch-data" class="fetch-data">Load Posts</button>
</div>



<?php include('./partialViews/footer.php')?>
<script>
    var pageNumber = 0
$(document).ready(function() {
    getData()
    $('#fetch-data').click(function() {
        getData()
    });
});
function getData(){
    $.ajax({
            url: 'getPostData.php', // URL to the PHP file
            method: 'GET',   // HTTP method
            dataType: 'json', // Expected data type
            headers: {
        'pageNumber': pageNumber // Send page number in the headers
    },

            success: function(response) {
                // Handle the success response
                // console.log(response)
                for(var data of response.data){
                    $( "#post" ).append(createCardTemplate(data.title , data.small_description,data.header_image_path,data.Id ));
                }
                pageNumber++
            },
            error: function(xhr, status, error) {
                console.log(error)
            }
        });
}
function createCardTemplate($title  , $smallDescription , $headerImageFile , $postId){
    var str = `
                        <div class="card" style="width: 18rem;">
  <img class="card-img-top" src="./uploads/${$headerImageFile}" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">${$title}</h5>
    <p class="card-text">${$smallDescription}</p>
    <a href="./readMore.php?postId=${$postId}" class="btn btn-primary">Read More ...</a>
  </div>
</div>
    `
    return str
}
</script>


</body>
</html>