<!DOCTYPE html>
<html>
<head>
    <title>Images PDF</title>
    <style>
        @page {
            size: 210mm 330mm;
            /* Ukuran kertas F4 */
            margin: 0;
            /* Hilangkan margin untuk mengurangi efek header/footer */
        }

        /* * {

            font-size: 12px
        } */

        body {
            font-family: "Times New Roman", Times, serif;
            text-size-adjust: 12px;
            margin: 0;
            padding: 0;
        }
        
        .container {
            border: red 1px solid;
            width: 210mm;
            max-width: 210mm;
            min-height: 330mm;
            padding: 10mm;
            box-sizing: border-box;
            overflow: hidden;
            margin: auto;
        }

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
    <?php for($i = 0; $i < 10; $i++): ?>
        <img src="/storage/01JG1H085C06PK8Z556M73KYSQ.png" alt="Image">
    <?php endfor; ?>
</body>
</html>
