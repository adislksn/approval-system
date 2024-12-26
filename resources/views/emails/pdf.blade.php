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
            border: red 1px solid;
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
    <div class="container">
        <section style="display: flex; justify-content: space-evenly;">
            <img src="{{ asset('assets/logo_marunda.png') }}" style="width: 150px" alt="header" />
            <div style="text-align: center;">
                <h1 class="text-formatter" style="margin: 0;">PT.Marunda Distribusindo Raya</h1>
                <p style="margin: 0;">Jl.Inspeksi Kirana, RT/RW.11/08, Kelurahan Cilincing, Kecamatan</p>
                <p style="margin: 0;">Cilincing, Jakarta Utara, 14120</p>
                <p style="margin: 0;">Telp. 021-22441879 Fax.021-22441875</p>
            </div>
        </section>
        <hr style="margin-top: 10px;border: 1px solid black;" />
        <hr style="margin: .1px;border: 1px solid black;" />

        <h2 class="text-formatter" style="text-align: center;margin-top: 30px;">
            Surat Perizinan Service Kendaraan
        </h2>

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
                        NAMA
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">No. Polisi</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        NOPOL
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Jenis Kendaraan</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        JENIS
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Tahun Kendaraan</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        TAHUN
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Shipping Type</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        TANGGAL
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Kilometer</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        KILOMETER
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Estimasi Biaya</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        ESTIMASI BIAYA
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Deskripsi Kerusakan</td>
                    <td style="width: 5%;">:</td>
                    <td></td>
                </tr>
            </table>
        </section>

        <section>
            <table style="width: 100%;margin-top: 20px;border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black;width: 10%">No</th>
                        <th style="border: 1px solid black;">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td style="border: 1px solid black;text-align: center">1</td>
                        <td style="border: 1px solid black;">DESKRIPSI</td>
                    </tr> loop with php but on empty td --}}
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <tr>
                            <td style="border: 1px solid black;text-align: center"><?= $i ?></td>
                            <td style="border: 1px solid black;">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit recusandae quod tempore quisquam porro, molestias, ex qui totam vitae minus laborum officia voluptates atque fuga ipsam dicta itaque sit corrupti?
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </section>

    </div>
    <div class="container">

        <section style="margin-top: 20px;">
            <p class="text-formatter" style="margin: 0;">
                Demikian surat perizinan ini disampaikan, kiranya service kendaraan dapat segera dilaksanakan agar kegiatan operasional dapat berlangsung dengan baik kembali.
            </p>
            <p class="text-formatter" style="margin-top: 20px;text-align: center;">
                Mengetahui,
            </p>
            <div style="margin-top: 100px;">
                <div style="width: 50%;float: left;text-align: center;">
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        Bayu Sudarmaji
                    </p>
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        Kepala Transport DC Marunda
                    </p>
                </div>
                <div style="width: 50%;float: right;text-align: center;font-weight: bold;">
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        Giri Fahmi
                    </p>
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        Kepala Logistik DC Marunda
                    </p>
                </div>
            </div>
        </section>

        <section style="padding-top: 50px;">
            <p class="text-formatter" style="text-align: center;">
                Menyetujui,
            </p>
            <div style="margin-top: 100px;">
                <div style="width: 100%;text-align: center;">
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        Eric Bastian
                    </p>
                    <p class="text-formatter" style="margin: 0;font-weight: bold;">
                        DC Marunda
                    </p>
                </div>
            </div>
        </section>
    </div>
</body>

</html>