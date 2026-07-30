@php
    /** @var \PatODev\FacturX\Model\Invoice $invoice */
    $calculator = new \PatODev\FacturX\Calculation\InvoiceCalculator();
    $totals = $calculator->totals($invoice);
    $vatBreakdown = $calculator->vatBreakdown($invoice);

    $money = static fn (float $amount): string => number_format($amount, 2, ',', ' ').' '.$invoice->currencyCode->value;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1a1a1a; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        .parties { width: 100%; margin-bottom: 24px; }
        .parties td { vertical-align: top; width: 50%; }
        .meta { margin-bottom: 24px; }
        .meta td { padding: 2px 8px 2px 0; }
        .lines { margin-bottom: 16px; }
        .lines th, .lines td { border-bottom: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        .lines th { background: #f2f2f2; }
        .lines .num { text-align: right; }
        .totals { width: 40%; margin-left: auto; }
        .totals td { padding: 2px 6px; }
        .totals .num { text-align: right; }
        .totals .due { font-weight: bold; border-top: 1px solid #1a1a1a; }
    </style>
</head>
<body>
    <h1>Facture {{ $invoice->number }}</h1>
    <table class="meta">
        <tr><td>Date d'émission</td><td>{{ $invoice->issueDate->format('d/m/Y') }}</td></tr>
        @if ($invoice->dueDate)
            <tr><td>Date d'échéance</td><td>{{ $invoice->dueDate->format('d/m/Y') }}</td></tr>
        @endif
        @if ($invoice->buyerReference)
            <tr><td>Référence acheteur</td><td>{{ $invoice->buyerReference }}</td></tr>
        @endif
    </table>

    <table class="parties">
        <tr>
            <td>
                <strong>{{ $invoice->seller->name }}</strong><br>
                {{ $invoice->seller->address->line1 }}<br>
                @if ($invoice->seller->address->line2)
                    {{ $invoice->seller->address->line2 }}<br>
                @endif
                {{ $invoice->seller->address->postalCode }} {{ $invoice->seller->address->city }}, {{ $invoice->seller->address->countryCode }}<br>
                @if ($invoice->seller->vatNumber)
                    TVA : {{ $invoice->seller->vatNumber }}<br>
                @endif
                @if ($invoice->seller->legalRegistrationId)
                    SIRET : {{ $invoice->seller->legalRegistrationId }}
                @endif
            </td>
            <td>
                <strong>{{ $invoice->buyer->name }}</strong><br>
                {{ $invoice->buyer->address->line1 }}<br>
                @if ($invoice->buyer->address->line2)
                    {{ $invoice->buyer->address->line2 }}<br>
                @endif
                {{ $invoice->buyer->address->postalCode }} {{ $invoice->buyer->address->city }}, {{ $invoice->buyer->address->countryCode }}<br>
                @if ($invoice->buyer->vatNumber)
                    TVA : {{ $invoice->buyer->vatNumber }}
                @endif
            </td>
        </tr>
    </table>

    <table class="lines">
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="num">Qté</th>
                <th class="num">Prix unitaire</th>
                <th class="num">TVA</th>
                <th class="num">Montant HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->lines() as $line)
                <tr>
                    <td>{{ $line->itemName }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format($line->quantity, 2, ',', ' '), '0'), ',') }} {{ $line->unitCode->value }}</td>
                    <td class="num">{{ $money($line->netUnitPrice) }}</td>
                    <td class="num">{{ $line->vatRate !== null ? number_format($line->vatRate, 2, ',', ' ').' %' : $line->vatCategory->value }}</td>
                    <td class="num">{{ $money($line->netAmount()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Total HT</td><td class="num">{{ $money($totals->taxExclusiveAmount) }}</td></tr>
        @foreach ($vatBreakdown as $entry)
            <tr>
                <td>TVA {{ $entry->rate !== null ? number_format($entry->rate, 2, ',', ' ').' %' : $entry->category->value }}</td>
                <td class="num">{{ $money($entry->taxAmount) }}</td>
            </tr>
        @endforeach
        <tr><td>Total TVA</td><td class="num">{{ $money($totals->taxAmount) }}</td></tr>
        <tr class="due"><td>Total à payer</td><td class="num">{{ $money($totals->duePayableAmount) }}</td></tr>
    </table>

    @if ($invoice->paymentTermsText)
        <p>{{ $invoice->paymentTermsText }}</p>
    @endif

    @foreach ($invoice->notes() as $note)
        <p>{{ $note->content }}</p>
    @endforeach
</body>
</html>
