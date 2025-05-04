<!DOCTYPE html>
<html>
<head>
    <?php 
        use Carbon\Carbon; 

        $pertainsToList = $inspection->deficiencies->pluck('pertainsTo')->unique('id')->map(function ($pertainsTo) {
            return $pertainsTo->activeDesignation->address_asc;
        });

    ?>
    <meta charset="utf-8">
    <title>Ambush Check Inspection Note</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.5;
        }
        h2, h3, h4 {
            page-break-after: avoid;
        }
        .section {
            page-break-inside: avoid;
        }
        li {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <h2 align="center"><u>Inspection Note #{{ $inspection->id }}</u></h2>
    <p style="text-align: center"><strong>Ambush Check By {{ $inspection->attendedBy->name }}, {{ $inspection->attendedBy->activeDesignation->address_asc }} </strong></p>

    <p><strong>1. Location:</strong> {{ $inspection->location }}</p>
    <p><strong>2. Date of Inspection:</strong> {{  Carbon::parse($inspection->datetime)->format('d M Y') }}</p>
    <p><strong>3. Time of Inspection:</strong> {{  Carbon::parse($inspection->datetime)->format('H:i A') }}</p>
    <p><strong>3. Type of Inspection:</strong> Ambush Check</p>
    <p><strong>4. Name and Designation of Inspecting Officer:</strong> {{ $inspection->attendedBy->name }}, {{ $inspection->attendedBy->activeDesignation->address_asc }}</p>

    <div>
        <div><b>6. (Deficiencies / Observations / Abnormalities) Noted During Inspection</b></div>
        <ul>
            @foreach ($inspection->deficiencies as $deficiency)
                <li style="margin-bottom: 15px;">
                    <b>Pertains to:</b> {{ $deficiency->pertainsTo->name }}, {{ $deficiency->pertainsTo->activeDesignation->address_asc }}<br>
                    <b>Note:</b>{{ $deficiency->note }} <br>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="section footer" style="margin-top: 40px;">

        <div align="right" style="margin-top: 120px;">
            <b>{{  $inspection->attendedBy->activeDesignation->address_asc }}</b>
        </div>

        <br><br>

        <br><br>
        <p><strong>CC:</strong></p>
        <ul>
            @foreach ($pertainsToList as $pertainsTo)
            <li style="margin-bottom: 15px;">
                <b>{{ $pertainsTo }}</b> :</b>For kind information and necessary action please.<br>
            </li>
        @endforeach
        </ul>
    </div>

</body>
</html>
