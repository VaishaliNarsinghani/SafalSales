<?php
// ✅ Database connection
$mysqli = new mysqli("localhost","safalapp","StrongPassword!","safalsales");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// ✅ Get invoice_id from URL (example: invoice_preview.php?id=16)
$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($invoice_id <= 0) {
    die("Invalid invoice ID");
}

// ✅ Fetch invoice
$invoice_sql = "SELECT * FROM invoices WHERE id = $invoice_id";
$inv = $mysqli->query($invoice_sql)->fetch_assoc();

if (!$inv) {
    die("Invoice not found");
}

// ✅ Fetch items
$items_sql = "
    SELECT ii.*, 
           COALESCE(p.description, 'No description') AS description,
           COALESCE(p.category, '') AS category,
           COALESCE(p.thumbnail, '') AS thumbnail
    FROM invoice_items ii
    LEFT JOIN products p ON TRIM(ii.product_name) = TRIM(p.name)
    WHERE ii.invoice_id = $invoice_id
";


$items_res = $mysqli->query($items_sql);

$items = [];
while ($row = $items_res->fetch_assoc()) {
    $items[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice #<?= $inv['id'] ?></title>
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; margin:40px; background:#fafafa; color:#333; }
  .invoice-box { max-width:900px; margin:auto; background:#fff; padding:30px; border:1px solid #eee; box-shadow:0 0 15px rgba(0,0,0,0.1); position:relative; }
  .header { display:flex; justify-content:space-between; align-items:center; border-bottom:3px solid #d6681aff; padding-bottom:15px; margin-bottom:20px; }
  .logo { font-size:26px; font-weight:bold; color:#d6681aff; }
  .shop-details { text-align:right; font-size:14px; line-height:1.4; }
  h1 { text-align:center; color:#d6681aff; margin:10px 0; text-transform:uppercase; letter-spacing:2px; }
  .info { margin-bottom:20px; }
  .info h3 { margin-bottom:5px; border-bottom:1px solid #ddd; padding-bottom:3px; color:#444; }
  table { width:100%; border-collapse:collapse; margin-top:20px; font-size:14px; }
  th, td { border:1px solid #ddd; padding:10px; text-align:center; }
  th { background:#ffebdf; text-transform:uppercase; }
  tr:nth-child(even) { background:#f9f9f9; }
  .total-row td { font-weight:bold; font-size:15px; background:#ffe5d0; }
  @media print {
  .no-print { display: none !important; }
}

  /* ✅ PAID Stamp */
  .paid-stamp {
    position:absolute;
    top:40%;
    left:50%;
    transform:translate(-50%, -50%) rotate(-20deg);
    border:5px solid green;
    border-radius:50%;
    padding:40px 70px;
    font-size:40px;
    font-weight:bold;
    color:green;
    opacity:0.2;
    text-align:center;
    text-transform:uppercase;
  }

  .btn-print { margin-top:30px; text-align:center; }
  button { padding:10px 25px; background:#d6681aff; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; }
  button:hover { background:#d6681aff; }
   .paid-stamp {
    position:absolute;
    top:45%;
    left:50%;
    transform:translate(-50%, -50%) rotate(-15deg);
    font-size:48px;
    font-weight:bold;
    text-transform:uppercase;
    color:transparent;
    padding:30px 60px;
    border:8px solid #006400;
    border-radius:50%;
    text-align:center;
    opacity:0.25;
    letter-spacing:5px;
    
    /* ✅ Text with gradient ink effect */
    background:repeating-linear-gradient(45deg, #006400 0%, #228B22 20%, #006400 40%);
    -webkit-background-clip:text;
    background-clip:text;
    
    /* ✅ Rough ink border effect */
    box-shadow: 0 0 3px #006400, 0 0 6px #004d00 inset;
    filter:url(#stampNoise);
  }
</style>
</head>
<body>

<div class="invoice-box">
  <div class="header">
    <div class="logo">
        <img src="safal sales logo.png" alt="Safal Sales" style="height:50px; vertical-align:middle; border-radius:70%;">
        Safal Sales
    </div>
    <div class="shop-details">
      <strong>Safal Sales Pvt Ltd</strong>
      <br>
      📞 +91 9876543210<br>
      ✉️ safalmarketing54@gmail.com
    </div>
</div>

  <h1>Invoice</h1>
  <div class="info">
    <h3>Invoice Details</h3>
    <p><strong>Invoice ID:</strong> <?= $inv['invoice_number'] ?></p>
    <p><strong>Date:</strong> <?= date("d M Y", strtotime($inv['created_at'])) ?></p>
    <p><strong>Status:</strong> <?= $inv['status'] ?></p>
  </div>

  <div class="info">
    <h3>Customer Details</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($inv['name']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($inv['phone']) ?></p>
<div class="info">
  <h3>Customer Details</h3>
  <p><strong>Name:</strong> <?= htmlspecialchars($inv['name']) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($inv['phone']) ?></p>
  <p><strong>Referred Person:</strong> <?= !empty($inv['referred_person']) ? htmlspecialchars($inv['referred_person']) : '—' ?></p>

     <p><strong>Address:</strong> <?= htmlspecialchars($inv['address']) ?></p>
  </div>

  <h3>Order Summary</h3>
<table>
  <tr>
    <th>S.No</th>
    <th>Image</th>
    <th>Product</th>
    <th>Description</th>
    <th>Qty</th>
    <th>Rate (₹)</th>
    <th>Total (₹)</th>
  </tr>
  
  <?php $i=1; foreach($items as $it): ?>
  <tr>
    <td><?= $i++ ?></td>
    <td><img src="<?= $it['thumbnail'] ?>" width="50"></td>
    <td><?= htmlspecialchars($it['product_name']) ?></td>
  <td><?= !empty($it['description']) ? htmlspecialchars($it['description']) : '—' ?></td>
  <td><?= (int)$it['qty'] ?></td>
  <td><?= number_format($it['price'],2) ?></td>
  <td><?= number_format($it['qty'] * $it['price'],2) ?></td>
  </tr>
  <?php endforeach; ?>

  <tr class="total-row">
    <td colspan="6">Grand Total</td>
    <td>₹<?= number_format($inv['total'],2) ?></td>
  </tr>
</table>


<!-- ✅ Noise filter for stamp -->
<svg width="0" height="0">
  <filter id="stampNoise">
    <feTurbulence type="fractalNoise" baseFrequency="0.8" numOctaves="3" result="noise"/>
    <feDisplacementMap in="SourceGraphic" in2="noise" scale="6"/>
  </filter>
</svg>

<!-- ✅ Only one PAID stamp -->
<?php if($inv['status']=="Paid"): ?>
  <div class="paid-stamp">PAID</div>
<?php endif; ?>
 <div class="footer">
      🎆 Thank you for shopping at <b>Safal Sales</b> 🎆
    </div>
<div class="btn-print no-print">
<button onclick="finalizeInvoice(<?= $inv['id'] ?>)">🖨️ Print Invoice</button>

</div>
<script>
function markPaidAndPrint(id) {
  // ✅ Call PHP to update status
  fetch("markPaid.php?id=" + id)
    .then(res => res.text())
    .then(resp => {
        console.log("Marked as Paid:", resp);
        window.print();
        // After printing, optionally hide button
        document.querySelector(".btn-print").style.display = "none";
    })
    .catch(err => console.error("Error:", err));
}
</script>
<script>
function finalizeInvoice(id){
  fetch("deduct_actual_stock.php?id=" + id)
    .then(res => res.text())
    .then(resp => {
      console.log(resp);
      if(resp.includes("success")){
         window.print();
         document.querySelector(".btn-print").style.display = "none";
      } else {
         alert("Error: " + resp);
      }
    })
    .catch(err => alert("Request failed: " + err));
}
</script>

</body>
</html>
