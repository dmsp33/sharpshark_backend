<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        @font-face {
            font-family: 'SuisseIntl';
            src: url("{{ storage_path('fonts/SuisseIntl-Regular-WebS.woff2') }}") format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'SuisseIntlBold';
            src: url("{{ storage_path('fonts/SuisseIntl-Bold-WebM.woff2') }}") format("woff2");
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: 'SangBleuEmpireBold';
            src: url("{{ storage_path('fonts/SangBleuEmpire-Bold-WebXL.woff2') }}") format("woff2");
            font-weight: 700;
            font-style: normal;
        }

        body {
            font-family: 'SuisseIntl';
        }

        h3 {
            text-align: center;
        }

        table {
            font-family: 'SuisseIntl', arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            border: 0px;
        }

        td,
        th {
            border: 0px;
            text-align: left;
            padding: 8px;
        }

        .text-align {
            text-align: center;
            vertical-align: middle;
        }

        .text-align-left {
            text-align: left;
            vertical-align: middle;
        }

        .text-align-right {
            text-align: right;
            vertical-align: middle;
        }

        .text-align_ {
            text-align: justify;
        }

        .low {
            height: 5px;
            background: #000;
            border: none;
            left: 0;
            margin-top: 24px !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
            margin-right: -10%;
            width: 110%;
        }

        .low1 {
            height: 2px;
            background: #000;
            border: none;
            left: 0;
            margin-top: 3px !important;
            padding-top: 0 !important;
            margin-right: -10%;
            width: 110%;
        }

        .low2 {
            height: 1px;
            background: #000;
            border: none;
            margin-right: -10%;
            width: 110%;
        }

        .low3 {
            height: 2px;
            background: #000;
            border: none;
            margin-left: -10%;
            margin-right: -10%;
            width: 120%;
        }

        .low4 {
            height: 2px;
            background: #000;
            border: none;
            margin-left: -10%;
            width: 110%;
        }

        h1 {
            font-family: 'SuisseIntl', Muli, sans-serief;
            font-size: 24px !important;
            letter-spacing: .2px;
            margin-bottom: 12px !important;
        }

        .contenido {
            font-family: 'SuisseIntl' !important;
        }
    </style>
</head>

<body>
    <footer>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 35;
                $y = $pdf->get_height() - 35;
                $text = "p. {PAGE_NUM}/{PAGE_COUNT}";
                $font = $fontMetrics->getFont("SuisseIntl", "normal");
                $size = 12;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    </footer>
    <table>
        <tr>
            <td class="text-align-left">
                <img src="{{ public_path('img/logo_sharp_shark.png') }}" style="width: 40px">
            </td>
            <td class="text-align-right" style="font-family: 'SangBleuEmpireBold';">
                This content is protected by <b>SharpShark</b>
            </td>
        </tr>
    </table>
    <hr class="low">
    <hr class="low1">
    <br>

    <table>

        <tr>
            <td width="20%">Title</td>
            <td width="auto">
                <h2>{{ $certificado->titulo }}
                    <h2>
            </td>



        </tr>
        <tr>
            <td>Author</td>
            <td>{{ $certificado->autor }}</td>

        </tr>
        <tr>
            <td>Co-author(s)</td>
            <td>
                <a href=""></a>
            </td>

        </tr>
        <tr>
            <td>Author’s ID</td>
            <td>{{ $certificado->id }}</td>


        </tr>
        <tr>
            <td>Organization</td>
            <td>Laravel Sharp-Shark</td>
        </tr>



    </table>
    <br> <br>
    <div class="contenido" style="padding-left: 36px;">
        @foreach (explode("\n\n", $certificado->contenido) as $contenido)
            <p class="contenido">{{!! $contenido !!}}</p>
        @endforeach
    </div>
</body>
</html>