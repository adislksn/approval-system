<!DOCTYPE html>
<html>
<head>
    <title>Images PDF</title>
    <style>
        img {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Uploaded Images</h1>
    @foreach($images as $image)
        <img src="{{ $image }}" alt="Image">
    @endforeach
</body>
</html>
