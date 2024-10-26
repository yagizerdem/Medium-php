<?php
session_start();
if(!$_SESSION["isAuthenticated"]){
    header("location: index.php");
};
?>

<?php
    require("./db.php");
    $errors = [];
    $db = new Database(); // database object
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $errors = []; // clear errors 
        if (isset($_FILES['headerImage'])) {
            $file = $_FILES['headerImage'];
            
            // Check for errors
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/'; // Directory to save the file
                $uploadFile = $uploadDir . basename($file['name']);
                
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    echo "File is valid, and was successfully uploaded.\n";
                } else {
                    echo "File upload failed.";
                }
            } else {
                echo "File upload error: " . $file['error'];
            }
        }


    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/writepost.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="
https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.30.6/dist/editorjs.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/header@2.8.8/dist/header.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/list@1.10.0/dist/list.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/image@2.9.3/dist/image.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/checklist@1.6.0/dist/checklist.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/quote@2.7.2/dist/quote.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/delimiter@1.4.2/dist/delimiter.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/embed@2.7.6/dist/embed.umd.min.js
"></script>
<script src="
https://cdn.jsdelivr.net/npm/@editorjs/marker@1.4.0/dist/marker.umd.min.js
"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.7/axios.min.js" integrity="sha512-DdX/YwF5e41Ok+AI81HI8f5/5UsoxCVT9GKYZRIzpLxb8Twz4ZwPPX+jQMwMhNQ9b5+zDEefc+dcvQoPWGNZ3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<body>
<?php include('./partialViews/header.php')?>

<div class="container">
<form id="form" enctype='multipart/form-data'>
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="titleInput" aria-describedby="titleInput" name="title">
  </div>
  <div class="mb-3">
    <label for="smallDescription" class="form-label">Small Description</label>
    <input type="text" class="form-control" id="smallDescription" name="smallDescription">
  </div>
  <div class="mb-3">
    <!-- editor here  -->
    <div id="editorjs" class="editorjs"  ></div>
</div>
<div class="mb-3">
    <input type="file" name="headerImage" accept="image/*" id="headerImage"/>
</div>
  <button type="button" class="btn btn-primary" id="submitButton">Submit</button>
</form>
</div>


    <script>

const editor = new EditorJS( {
    /**
        * Id of Element that should contain Editor instance
        */
    holder: 'editorjs',
    /**
        * Tools list
        */
    tools: {
        header: {
            class: Header,
            inlineToolbar:true,
            shortcut: 'CMD+SHIFT+H'
        },
        list: {
            class: List,
            inlineToolbar: true,
            shortcut: 'CMD+SHIFT+L'
        },
        checklist: {
            class: Checklist,
            inlineToolbar: true,
        },
        quote: {
            class: Quote,
            inlineToolbar: true,
            config: {
                quotePlaceholder: 'Enter a quote',
                captionPlaceholder: 'Quote\'s author',
            },
            shortcut: 'CMD+SHIFT+O'
        },
        delimiter: Delimiter,
        embed: Embed,
        Marker: {
            class: Marker,
            shortcut: 'CMD+SHIFT+M',
        }
    },
} );

(async ()=>{
    try {
  await editor.isReady;
  console.log('Editor.js is ready to work!')
  /** Do anything you need after editor initialization */
} catch (reason) {
  console.log(`Editor.js initialization failed because of ${reason}`)
}   
})()
$(document).ready(()=>{
    $('#submitButton').on('click', async (e) => {
        e.preventDefault()
        var formData = $('#form').serializeArray().reduce(function(obj, item) {
    obj[item.name] = item.value;
    return obj;
}, {});

const fileInput = $('#headerImage').prop('files')[0]; // Get the first file
    if (fileInput) {
        console.log('Selected file:', fileInput);
        // You can also append the file to FormData if needed
        formData['headerImage']= fileInput;
    } else {
        console.log('No file selected');
    }

    var body = await editor.save()
    formData['body'] = body

    //  title: formData['title'],
    // smallDescription: formData['smallDescription'],
    // body:formData['body'],
    // headerImage:  formData['headerImage']

    try{
        const {data} = await  axios.post('/medium/writePostDb.php', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })

  if(data?.['status'] == "success"){
    Toastify({
  text: data['data'],
  duration: 3000,
  newWindow: true,
  close: true,
  gravity: "top", // `top` or `bottom`
  position: "right", // `left`, `center` or `right`
  stopOnFocus: true, // Prevents dismissing of toast on hover
  style: {
    background: "linear-gradient(to right, #00b09b, #96c93d)",
  },
  onClick: function(){} // Callback after click
}).showToast();

    setTimeout(() => {
        window.location.href = 'index.php';
    }, 3000);
}

    }catch(err){
        var errMessage = err.response.data.message
        Toastify({
  text: errMessage,
  duration: 3000,
  newWindow: true,
  close: true,
  gravity: "top", // `top` or `bottom`
  position: "right", // `left`, `center` or `right`
  stopOnFocus: true, // Prevents dismissing of toast on hover
  style: {
    background: "linear-gradient(to right, #8B0000, #FF6347)",
  },
  onClick: function(){} // Callback after click
}).showToast();
        console.log(errMessage)
    }  
    });
})

    </script>

</body>
</html>