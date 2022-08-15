<html>

<body>

<form method="post" enctype="multipart/form-data">
    @csrf

    <input type="text" name="textInput">
    <input type="file" name="fileInput">

    <button type="submit">Submit</button>
</form>
</body>
</html>
