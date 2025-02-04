<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <style>
        @media print {
    body * {
        visibility: hidden;
    }
    #ticketPrint, #ticketPrint * {
        visibility: visible;
    }
    #ticketPrint {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;
        font-size: 14px;
        background: white;
        padding: 10px;
        border: 2px dashed black;
    }
    button {
        display: none;
    }
}

    </style>
</head>
<body>
    <h1>Ticket Details</h1>
    
    <div id="ticketPrint">
        <h3>Pharmacy Ticket</h3>
        <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
        <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
        <p>----------------------</p>
        <p>Thank you for your patience!</p>
    </div>

    <button onclick="window.print()">Print Ticket</button>
    <a href="/">Back to Home</a>
</body>
</html>
