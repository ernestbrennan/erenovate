<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    @include('pdf.invoice.style')

</head>
<body>
<div class="contract-comp__body component-body_scroll scrollbar contract-comp_invoice">

    @include('pdf.invoice.parts.basic_info', ['invoice' => $invoice] )

    @include('pdf.invoice.parts.items')


    @include('pdf.invoice.parts.summary', ['invoice_summary' => $invoice_summary, 'invoice' => $invoice])

</div>

</body>
</html>
