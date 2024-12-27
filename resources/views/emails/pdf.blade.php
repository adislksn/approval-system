<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Perizinan Service Kendaraan</title>
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
            width: 210mm;
            max-width: 210mm;
            min-height: 330mm;
            padding: 10mm;
            box-sizing: border-box;
            overflow: hidden;
            margin: auto;
        }

        .text-formatter {
            text-size-adjust: 12px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <section>
        <h3 class="text-formatter" style="margin: 20px 0 0 0;font-weight: normal">
            Yth.
        </h3>
        <p class="text-formatter" style="margin: 0;">
            Vendor Bersangkutan
        </p>
        <p class="text-formatter" style="margin-top: 20px;">
            Saya memberikan approval kepada pihak bersangkutan untuk melakukan service kendaraan dengan deskripsi sebagai berikut:
        </p>
        <table style="width: 100%;margin-top: 20px;">
            <tr>
                <td style="width: 30%;">Nama Vendor</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $name }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">No. Polisi</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $data['nopol']}}
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">Jenis Kendaraan</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    ?
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">Tahun Kendaraan</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $data['tahun'] }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">Shipping Type</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $data['shipping_type'] }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">Kilometer</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $data['kilometer'] }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">Estimasi Biaya</td>
                <td style="width: 5%;">:</td>
                <td style="width: 65%;">
                    {{ $data['estimation_cost'] }}
                </td>
            </tr>
        </table>
    </section>
</body>

</html>