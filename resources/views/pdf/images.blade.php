<!DOCTYPE html>
<html>
<head>
    <title>Images PDF</title>
    <style>
        @page {
            size: 190mm 230mm;
            /* Ukuran kertas A4 */
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
            border: 1px solid black;
            max-width: 190mm;
            min-height: 230mm;
            padding: 10mm;
            box-sizing: border-box;
            margin: auto;
        }

        img {
            display: block;
            object-fit: contain;
            height: auto;
        }

        .text-formatter {
            text-size-adjust: 12px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <table style="width: 100%;margin-top: 20px;">
            <tr>
                <td style="width: 30%;place-items: center;">
                    <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(asset('assets/logo_marunda.png')))}}" width="150px" height="150px" alt="header" />
                </td>
                <td style="width: 70%;">
                    <h1 class="text-formatter" style="margin: 0;text-align: center;">PT.Marunda Distribusindo Raya</h1>
                    <p class="text-formatter" style="margin: 0;text-align: center;">Jl.Inspeksi Kirana, RT/RW.11/08, Kelurahan Cilincing, Kecamatan</p>
                    <p class="text-formatter" style="margin: 0;text-align: center;">Cilincing, Jakarta Utara, 14120</p>
                    <p class="text-formatter" style="margin: 0;text-align: center;">Telp. 021-22441879 Fax.021-22441875</p>
                </td>
            </tr>
        </table>
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
                Vendor {{ $datas['name'] ?? '' }}
            </p>
            <p class="text-formatter" style="margin-top: 20px;">
                Saya memberikan approval kepada pihak bersangkutan untuk melakukan service kendaraan dengan deskripsi sebagai berikut:
            </p>
            <table style="width: 100%;margin-top: 20px;">
                <tr>
                    <td style="width: 30%;">Nama Vendor</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['name'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">No. Polisi</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['data']['nopol']?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Merk Kendaraan</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['data']['merk'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Tahun Kendaraan</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['data']['tahun'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Shipping Type</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['data']['shipping_type'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">Kilometer</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 65%;">
                        {{ $datas['data']['kilometer'] ?? '' }}
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
                    @isset($datas['data']['description_service'])
                        @foreach($datas['data']['description_service'] as $index => $description)
                            <tr>
                                <td style="border: 1px solid black;text-align: center">{{ $index + 1 }}</td>
                                <td style="border: 1px solid black;">
                                    {{ $description['description'] ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="container">
        <section style="margin-top: 50px;">
            <p class="text-formatter" style="margin: 0;">
                Demikian surat perizinan ini disampaikan, kiranya service kendaraan dapat segera dilaksanakan agar kegiatan operasional dapat berlangsung dengan baik kembali.
            </p>
            <p class="text-formatter" style="margin-top: 20px;text-align: center;">
                Mengetahui,
            </p>
            <table style="width: 100%; margin-top: 20px;">
                <tr>
                    <td style="place-items: center;" align="center">
                        <img src="{{ $datas['approval'][0]['ttd_path'] ?? '' }}" alt="image" width="100px" height="100px" style="object-fit: contain;display: block; margin-left: auto; margin-right: auto;"/>
                    </td>
                    <td style="place-items: center;" align="center">
                        <img src="{{ $datas['approval'][1]['ttd_path'] ?? '' }}" alt="image" width="100px" height="100px" style="object-fit: contain;display: block; margin-left: auto; margin-right: auto;"/>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            {{ $datas['approval'][0]['user']['name'] ?? '' }}
                        </p>
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            Kepala Transport DC Marunda
                        </p>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            {{ $datas['approval'][1]['user']['name'] ?? '' }}
                        </p>
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            Kepala Logistik DC Marunda
                        </p>
                    </td>
                </tr>
            </table>
        </section>
        
        <section style="padding-top: 50px;">
            <p class="text-formatter" style="text-align: center;">
            Menyetujui,
            </p>
            <table style="width: 100%; margin-top: 20px;">
                <tr>
                    <td style="place-items: center;" align="center">
                        <img src="{{ $datas['approval'][2]['ttd_path'] ?? '' }}" alt="image" width="100px" height="100px" style="object-fit: contain"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            {{ $datas['approval'][2]['user']['name'] ?? '' }}
                        </p>
                        <p class="text-formatter" style="margin: 0;font-weight: bold;">
                            DC Marunda
                        </p>
                    </td>
                </tr>
            </table>
        </section>
    </div>

    {{-- <div class="container"> --}}
    {{-- </div> --}}

    <div class="container">
        <h1 class="text-formatter" style="text-align: center">DOKUMENTASI KERUSAKAN KENDARAAN</h1>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>
                @isset($images)
                    @foreach($images as $index => $image)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="place-items: center;padding: 10px;" align="center">
                            <img src="{{ $image }}" alt="image" width="250px" height="250px" style="object-fit: contain;display: block; margin-left: auto; margin-right: auto;"/>
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</body>
</html>
