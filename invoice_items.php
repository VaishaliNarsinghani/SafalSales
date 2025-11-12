<?php
include("db.php");
if (!isset($_GET['id'])) { die("Invoice ID missing."); }
$invoice_id = (int)$_GET['id'];

// Fetch invoice
$inv = $conn->query("SELECT * FROM invoices WHERE id = $invoice_id")->fetch_assoc();

// Fetch items
$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = $invoice_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #<?= $inv['id'] ?> - Items</title>
  <style>
  /* ... your existing CSS ... */

  .btn-download {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: #d6681aff;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
    cursor: pointer;
  }
  .btn-download:hover {
    background: #d6681aff;
  }

  /* ✅ Hide download button when printing */
  @media print {
    .btn-download {
      display: none !important;
    }
  }
</style>

  <style>
    @media print {
  .btn-download {
    display: none !important;
  }
}

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      padding: 40px;
      color: #333;
    }
    .invoice-box {
      background: #fff;
      max-width: 900px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    h2 {
      margin: 0 0 10px;
      color: #d6681aff;
    }
    .invoice-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .customer-info p {
      margin: 5px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
    }
    table th, table td {
      padding: 14px;
      border-bottom: 1px solid #eee;
      text-align: left;
      font-size: 14px;
    }
    table th {
      background: #d6681aff;
      color: #fff;
      font-weight: 600;
    }
    table tr:hover {
      background: #f9f9f9;
    }
    .total-row td {
      font-weight: bold;
      font-size: 16px;
      background: #fafafa;
    }
    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 13px;
      color: #666;
    }
    .btn-download {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 18px;
      background: #d6681aff;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
    }
    .btn-download:hover {
      background: #d6681aff;
    }
  </style>
</head>
<body>
  <div class="invoice-box" id="invoice-content">
    <div class="invoice-header">
      <h2>Invoice #<?= $inv['invoice_number'] ?></h2>
      <div class="customer-info">
        <p><b><?= htmlspecialchars($inv['name']) ?></b></p>
        <p><b>Phone: </b><?= htmlspecialchars($inv['phone']) ?></p>
         <p>
  <strong>Referred Person:</strong>
  <?= ($inv['referred_person'] !== null && $inv['referred_person'] !== '')
        ? nl2br(htmlspecialchars($inv['referred_person']))
        : '—' ?>
</p>

        <p><b>Date: </b><?= $inv['created_at'] ?></p>
      </div>
    </div>

    <h3 style="margin-bottom:10px;">Order Items</h3>
    <table>
      <tr>
        <th>S.No</th>
        <th>Product</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Rate (₹)</th>
        <th>Total (₹)</th>
      </tr>
      <?php $i=1; while($it=$items->fetch_assoc()): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($it['product_name']) ?></td>
        <td><?= !empty($it['description']) ? htmlspecialchars($it['description']) : '—' ?></td>
        <td><?= (int)$it['qty'] ?></td>
        <td><?= number_format($it['price'], 2) ?></td>
        <td><?= number_format($it['qty'] * $it['price'], 2) ?></td>
      </tr>
      <?php endwhile; ?>
      <tr class="total-row">
        <td colspan="4" style="text-align:right;">Grand Total</td>
        <td>₹<?= number_format($inv['total'], 2) ?></td>
      </tr>
    </table>

    <a href="download_invoice.php?id=<?= $inv['id'] ?>" class="btn-download">⬇ Download PDF</a>


    <div class="footer">
      🎆 Thank you for shopping at <b>Safal Sales</b> 🎆
    </div>
  </div>

  <!-- ✅ html2pdf.js library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
  function downloadPDF() {
  const button = document.querySelector(".btn-download");
  button.style.display = "none";   // hide before generating PDF

  const element = document.getElementById("invoice-content");

  const opt = {
    margin: 0.5,
    filename: 'Invoice-<?= $inv['invoice_number'] ?>.pdf',
    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };

  // ✅ Use html2pdf but force it to render text
  html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
    // Ensure embedded fonts are real text
    pdf.save('Invoice-<?= $inv['invoice_number'] ?>.pdf');
    button.style.display = "inline-block";
  });
}

</script>

</body>
</html>
