<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<a id="fb">facebook</a>
</br>
<a id="g">google +</a>

<form action="http://localhost.extrim.com/api/v1/upload/" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(function(){

        // jQuery methods go here...
        $.getJSON('http://localhost.extrim.com/api/v1/facebook/link', function(res) {
            $('#fb').attr('href', res);
        })

        $.getJSON('http://localhost.extrim.com/api/v1/google/link', function(res) {
            $('#g').attr('href', res);
        })

        
    });
</script>
</html>