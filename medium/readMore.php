<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/readMore.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- for editor js  -->
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
<!-- end region -->

</head>
<body>
<?php include('./partialViews/header.php')?>


<div class="container">
    <img src="" id="headerImg"></img>
    <p id="title">...</p>
    <p id="author">...</p>
    <p id="email">...</p>
        <!-- editor here  -->
        <div id="editorjs" class="editorjs"></div>

</div>

<script>
const urlParams = new URLSearchParams(window.location.search);
const postId = urlParams.get('postId');
var editor = null // from editor js 

function getData(){
    $.ajax({
            url: 'getSinglePost.php', // URL to the PHP file
            method: 'GET',   // HTTP method
            dataType: 'json', // Expected data type
            headers: {
            'postId': postId // Send page number in the headers
        },

            success:async function(response) {
                const postData = response.data[0]
                // header img
                $('#headerImg').attr("src", `http://localhost/medium/uploads/${postData.header_image_path}`);
                // load title
                $('#title').html(`<span>${postData.title}</span>`) 
                $('#author').html(`<span>Author : ${postData.first_name} ${postData.last_name}</span>`) 
                $('#email').html(`<span>mail : ${postData.email}</span>`) 
                try {
  await editor.isReady;
  console.log('Editor.js is ready to work!')
  /** Do anything you need after editor initialization */
                    // set block of data
                    const editorData = JSON.parse(postData.body)
                    for(var block of editorData.blocks ){
                        const blockToAdd = {
                            type: block.type, 
                            data: block.data
                        };
                        editor.blocks.insert(blockToAdd.type, blockToAdd.data);
                    }
                  
} catch (reason) {
  console.log(`Editor.js initialization failed because of ${reason}`)
}

            },
            error: function(xhr, status, error) {
                console.log(error)
            }
        });
}


$(document).ready(function() {



    // edito js config
     editor = new EditorJS( {
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
    // end reigion
} );
// fetching blog data
getData()
});

</script>

</body>
</html>