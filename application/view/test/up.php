<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="/upload/image" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="FFFX123456"/>
    <input type="file" name="upfile"/>
    <input type="submit" value="上传"/>
</form>
</body>
</html>