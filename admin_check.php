<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// ---- ensure composer autoload exists ----
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("Composer autoload not found. Run in project root: composer require smalot/pdfparser phpoffice/phpspreadsheet");
}
require $autoload;

use Smalot\PdfParser\Parser;
use PhpOffice\PhpSpreadsheet\IOFactory;

// ---- DB connection ----
include("db.php"); // make sure $conn is a mysqli connection

// ---- helpers ----
function normalize_text($s) {
    $s = mb_strtolower((string)$s, 'UTF-8');
    $s = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $s); // remove punctuation (keep letters/numbers/spaces)
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}
function clean_number_token($t) {
    $t = str_replace([',','₹','$','Rs.','rs.'], '', $t);
    $t = preg_replace('/[^\d\.]/', '', $t);
    return $t;
}

// load products into memory (for fuzzy matching)
$products = [];
$prodRes = $conn->query("SELECT id, name, COALESCE(actual_stock,0) AS stock FROM products");
while ($r = $prodRes->fetch_assoc()) {
    $products[] = [
        'id' => (int)$r['id'],
        'name' => $r['name'],
        'norm' => normalize_text($r['name']),
        'stock' => (int)$r['stock']
    ];
}

// fast lookup map by normalized name
$prodMap = [];
foreach ($products as $p) $prodMap[$p['norm']] = $p;

// match function: exact -> substring DB -> fuzzy in-memory
function match_product($candidate, $products, $prodMap, $conn, &$scoreOut = 0) {
    $scoreOut = 0;
    $candNorm = normalize_text($candidate);
    if ($candNorm === '') return [null, 0, 'empty'];

    // 1) exact normalized
    if (isset($prodMap[$candNorm])) {
        $scoreOut = 100;
        return [$prodMap[$candNorm], $scoreOut, 'exact'];
    }

    // 2) try DB LIKE with up to 3 words fragment (fast)
    $words = array_filter(explode(' ', $candNorm));
    $fragWords = array_slice($words, 0, 3);
    if (!empty($fragWords)) {
        $frag = implode(' ', $fragWords);
        // use prepared statement with wildcard
        $stmt = $conn->prepare("SELECT id, name, COALESCE(actual_stock,0) AS stock FROM products WHERE LOWER(name) LIKE CONCAT('%', ?, '%') LIMIT 1");
        $lowerFrag = $frag;
        $stmt->bind_param('s', $lowerFrag);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        if ($r) {
            $matched = ['id'=>(int)$r['id'],'name'=>$r['name'],'norm'=>normalize_text($r['name']),'stock'=>(int)$r['stock']];
            similar_text($candNorm, $matched['norm'], $p);
            $scoreOut = round($p,2);
            return [$matched, $scoreOut, 'like-db'];
        }
    }

    // 3) fallback -> in-memory fuzzy (scan limited products)
    $best = null; $bestScore = 0;
    foreach ($products as $p) {
        // cheap early filter: require at least one token in common
        $pWords = explode(' ', $p['norm']);
        foreach ($words as $w) {
            if (strlen($w) < 2) continue;
            if (in_array($w, $pWords)) {
                similar_text($candNorm, $p['norm'], $perc);
                if ($perc > $bestScore) {
                    $bestScore = $perc;
                    $best = $p;
                }
                break;
            }
        }
    }
    if ($bestScore == 0) { // last resort: brute-force (if small catalog)
        foreach ($products as $p) {
            similar_text($candNorm, $p['norm'], $perc);
            if ($perc > $bestScore) {
                $bestScore = $perc;
                $best = $p;
            }
        }
    }
    $scoreOut = round($bestScore,2);
    return [$best, $scoreOut, 'fuzzy'];
}

// parse a single text line to product+qty using several heuristics
function parse_line_to_item($line) {
    $orig = trim($line);
    $line = preg_replace('/\s+/', ' ', $orig);

    if (preg_match('/^(invoice|bill|phone|mobile|date|gst|subtotal|total|grand total)/i', $line)) {
        return null;
    }

    // NEW rule for your invoice
    if (preg_match('/^(.+?)\s+—\s+(\d+)\s+[\d,.]+\s+[\d,.]+$/u', $line, $m)) {
        $product = trim($m[1]);
        $qty     = (int)$m[2];
        return ['product'=>$product, 'qty'=>$qty, 'raw'=>$orig];
    }

    // 1) Column-split if wide gaps (pdftotext -layout creates multiple spaces)
    $cols = preg_split('/\s{2,}/', $line);
    if (count($cols) >= 2) {
        // check last few columns for an integer qty
        for ($i = count($cols)-1; $i >= 0; $i--) {
            $tok = trim($cols[$i]);
            $num = clean_number_token($tok);
            if ($num !== '' && preg_match('/^\d+$/', $num) && (int)$num < 100000) {
                $qty = (int)$num;
                $product = trim(implode(' ', array_slice($cols, 0, $i)));
                if ($product !== '' && $qty > 0) return ['product'=>$product,'qty'=>$qty,'raw'=>$orig];
            }
        }
    }
    // Pattern: Product — Qty Rate Total
if (preg_match('/^(.+?)\s+—\s+(\d+)\s+[\d,.]+\s+[\d,.]+$/u', $line, $m)) {
    $product = trim($m[1]);
    $qty     = (int)$m[2];
    if ($product !== '' && $qty > 0) {
        return ['product'=>$product, 'qty'=>$qty, 'raw'=>$line];
    }
}


    // 2) pattern: Name ... number_at_end  => "Sparklers 10" or "Sparklers 10 pcs"
    if (preg_match('/(.+?)\s+([0-9]{1,6})\s*(pcs|nos|units|pack|pkt)?\s*$/i', $line, $m)) {
        $product = trim($m[1]);
        $qty = (int)$m[2];
        if ($product !== '' && $qty>0) return ['product'=>$product,'qty'=>$qty,'raw'=>$orig];
    }

    // 3) pattern: qty first "10 Sparklers"
    if (preg_match('/^\s*([0-9]{1,6})\s+(.+)$/', $line, $m2)) {
        $qty = (int)$m2[1];
        $product = trim($m2[2]);
        if ($product !== '' && $qty>0) return ['product'=>$product,'qty'=>$qty,'raw'=>$orig];
    }

    // 4) token-scan: find first small integer token and take text left as product
    if (preg_match_all('/\d+(?:[.,]\d+)?/', $line, $nums, PREG_OFFSET_CAPTURE)) {
        foreach ($nums[0] as $ninfo) {
            $num = $ninfo[0];
            $pos = $ninfo[1];
            $clean = clean_number_token($num);
            if ($clean === '') continue;
            $ival = (int)$clean;
            if ($ival > 0 && $ival < 100000) {
                // product is everything before this number
                $prod = trim(substr($line, 0, $pos));
                if ($prod !== '') return ['product'=>$prod,'qty'=>$ival,'raw'=>$orig];
            }
        }
    }

    // 5) nothing parsed
    return null;
}

// parse uploaded file (pdf/csv/xls)
function parse_uploaded_file($fileTmp, $ext) {
    $lines = [];
    try {
        if ($ext === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($fileTmp);
            $text = $pdf->getText();
            $lines = preg_split("/\r\n|\n|\r/", $text);
        } elseif ($ext === 'csv') {
            if (($h = fopen($fileTmp, 'r')) !== false) {
                // detect header row
                $first = fgetcsv($h, 0, ",");
                if ($first !== false) {
                    $headLower = array_map('mb_strtolower', $first);
                    $hasProductCol = false; $hasQtyCol = false;
                    foreach ($headLower as $i=>$v) {
                        if (stripos($v, 'product') !== false || stripos($v, 'item') !== false || stripos($v,'name')!==false) $hasProductCol = true;
                        if (stripos($v, 'qty') !== false || stripos($v,'quantity')!==false) $hasQtyCol = true;
                    }
                    if ($hasProductCol && $hasQtyCol) {
                        // header exists -> parse rows into "Product Qty" strings
                        $prodIdx = $qtyIdx = null;
                        foreach ($headLower as $i=>$v) {
                            if ($prodIdx === null && (stripos($v,'product')!==false || stripos($v,'item')!==false || stripos($v,'name')!==false)) $prodIdx = $i;
                            if ($qtyIdx === null && (stripos($v,'qty')!==false || stripos($v,'quantity')!==false)) $qtyIdx = $i;
                        }
                        while (($row = fgetcsv($h, 0, ",")) !== false) {
                            $p = isset($row[$prodIdx]) ? trim($row[$prodIdx]) : '';
                            $q = isset($row[$qtyIdx]) ? preg_replace('/[^\d]/','',$row[$qtyIdx]) : '';
                            if ($p !== '' && $q !== '') $lines[] = $p . ' ' . (int)$q;
                        }
                        fclose($h);
                        return $lines;
                    } else {
                        // no clear header -> rewind and treat each row as joined string
                        rewind($h);
                        while (($row = fgetcsv($h, 0, ",")) !== false) {
                            $lines[] = implode(' ', $row);
                        }
                        fclose($h);
                    }
                }
            }
        } elseif ($ext === 'xls' || $ext === 'xlsx') {
            $spreadsheet = IOFactory::load($fileTmp);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // check header row
            if (!empty($sheetData)) {
                $head = array_map(function($c){ return mb_strtolower((string)$c); }, $sheetData[0]);
                $hasProductCol = $hasQtyCol = false;
                foreach ($head as $i=>$v) {
                    if (stripos($v, 'product') !== false || stripos($v, 'item') !== false || stripos($v,'name')!==false) $hasProductCol = true;
                    if (stripos($v, 'qty') !== false || stripos($v,'quantity')!==false) $hasQtyCol = true;
                }
                if ($hasProductCol && $hasQtyCol) {
                    // find indices
                    $prodIdx = $qtyIdx = null;
                    foreach ($head as $i=>$v) {
                        if ($prodIdx === null && (stripos($v,'product')!==false || stripos($v,'item')!==false || stripos($v,'name')!==false)) $prodIdx = $i;
                        if ($qtyIdx === null && (stripos($v,'qty')!==false || stripos($v,'quantity')!==false)) $qtyIdx = $i;
                    }
                    for ($r = 1; $r < count($sheetData); $r++) {
                        $row = $sheetData[$r];
                        $p = isset($row[$prodIdx]) ? trim($row[$prodIdx]) : '';
                        $q = isset($row[$qtyIdx]) ? preg_replace('/[^\d]/','',$row[$qtyIdx]) : '';
                        if ($p !== '' && $q !== '') $lines[] = $p . ' ' . (int)$q;
                    }
                    return $lines;
                } else {
                    // no header -> each row to string
                    foreach ($sheetData as $row) $lines[] = implode(' ', $row);
                }
            }
        } else {
            return ['error' => 'unsupported'];
        }
    } catch (Exception $ex) {
        return ['error' => 'parse_failed: '.$ex->getMessage()];
    }
    return $lines;
}

// ---- handle form submit ----
$results = [];
$debug = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['order_file']) && $_FILES['order_file']['error'] === 0) {
    $fname = $_FILES['order_file']['name'];
    $tmp = $_FILES['order_file']['tmp_name'];
    $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));

    $parsed = parse_uploaded_file($tmp, $ext);
    if (is_array($parsed) && isset($parsed['error'])) {
        $debug[] = "Parse error: " . $parsed['error'];
    } else {
        $lines = (array)$parsed;
        foreach ($lines as $line) {
            $item = parse_line_to_item($line);
            if ($item === null) {
                $debug[] = "Skipped: " . $line;
                continue;
            }
            $pname = trim($item['product']);
            $qty = (int)$item['qty'];
            // remove trailing units like pcs/nos
            $pname = preg_replace('/\b(pcs|nos|units|pack|pkt|piece|pieces)\b/i','',$pname);
            $pname = trim($pname);

            // match
            $score = 0;
            list($matched, $score, $method) = match_product($pname, $products, $prodMap, $conn, $score);

            if ($matched && $score >= 65) {
                $matchedName = $matched['name'];
                $stock = (int)$matched['stock'];
                $matchedId = $matched['id'];
                $matchedBy = $method;
            } else {
                // if fuzzy low, try DB LIKE again with 3 words fragment
                $frag = implode(' ', array_slice(array_filter(explode(' ', normalize_text($pname))), 0, 3));
                $stmt = $conn->prepare("SELECT id, name, COALESCE(actual_stock,0) AS stock FROM products WHERE LOWER(name) LIKE CONCAT('%', ?, '%') LIMIT 1");
                $stmt->bind_param('s', $frag);
                $stmt->execute();
                $r = $stmt->get_result()->fetch_assoc();
                if ($r) {
                    $matchedId = (int)$r['id'];
                    $matchedName = $r['name'];
                    $stock = (int)$r['stock'];
                    $matchedBy = 'like-db-fallback';
                    similar_text(normalize_text($pname), normalize_text($matchedName), $pscore);
                    $score = round($pscore,2);
                } else {
                    $matchedId = null;
                    $matchedName = null;
                    $stock = 0;
                    $matchedBy = 'no-match';
                }
            }

            $status = '❓ Not found';
            if ($matchedName) {
                if ($stock >= $qty) $status = '✅ Available';
                elseif ($stock > 0) $status = "⚠️ Only $stock";
                else $status = '❌ Out of stock';
            }

            $results[] = [
                'raw' => $item['raw'],
                'parsed_product' => $pname,
                'ordered' => $qty,
                'matched_id' => $matchedId,
                'matched_name' => $matchedName,
                'stock' => $stock,
                'score' => $score,
                'matched_by' => $matchedBy,
                'status' => $status
            ];
        }
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // helpful debug if file not uploaded
        $err = $_FILES['order_file']['error'] ?? 'no_file';
        $debug[] = "No valid upload detected. \$_FILES['order_file']['error'] = " . (string)$err;
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin — Order Check</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;background:#f7f7f7;}
        form{background:#fff;padding:16px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.04)}
        table{width:100%;border-collapse:collapse;margin-top:14px}
        th,td{padding:10px;border:1px solid #eee;text-align:left;font-size:14px}
        th{background:#fafafa}
        .small{font-size:13px;color:#666}
        .debug{background:#fff;padding:10px;border-radius:8px;margin-top:12px;font-size:13px;color:#444}
        .ok{color:green}
        .warn{color:orange}
        .bad{color:red}
            .recent-activities {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  margin-top: 20px;
}
.recent-activities h3 {
  margin-bottom: 15px;
  color: #d6681aff;
}
.recent-activities ul {
  list-style: none;
  padding: 0;
}
.recent-activities li {
  padding: 8px 0;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}
.recent-activities li:last-child { border-bottom: none; }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
    }

    /* ===== NAVBAR (top) ===== */
    .navbar {
      display: flex; justify-content: space-between; align-items: center;
      background: #fff; padding: 15px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      position: sticky; top:0; z-index:100;
    }
    .navbar .logo { font-size: 20px; font-weight: bold; color: #d6681aff; }
    .nav-links { list-style:none; display:flex; margin:0; padding:0; }
    .nav-links li { margin-left:20px; }
    .nav-links a {
      text-decoration:none; color:#333; font-weight:500; transition:0.3s;
    }
    .nav-links a:hover { color:#d6681aff; }

    /* ===== Layout ===== */
    .wrapper { display:flex; min-height:100vh; }

    /* Sidebar */
    .sidebar {
      width:250px; background:#fff; border-right:1px solid #eee;
      padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.05);
    }
    .sidebar h2 {
      color:#d6681aff; margin-bottom:20px; font-size:22px;
    }
    .sidebar a {
      display:block; padding:12px 15px; margin-bottom:10px;
      color:#333; text-decoration:none; border-radius:8px;
      font-weight:500; transition:0.2s;
    }
    .sidebar a:hover, .sidebar a.active {
      background:#d6681aff; color:#fff;
    }

    /* Main Content */
    .main {
      flex:1; padding:30px;
    }
    h2 { margin-top:0; margin-bottom:20px; color:#333; }

    /* Table */
    table {
      width:100%; border-collapse:collapse;
      background:#fff; border-radius:12px;
      overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.08);
    }
    table th, table td {
      padding:14px; border-bottom:1px solid #eee;
      text-align:left; font-size:14px;
    }
    table th {
      background:#f9f9f9; font-weight:bold;
    }
    table tr:hover { background:#fafafa; }
    a.download {
      color:#d6681aff; text-decoration:none; font-weight:bold;
    }
    a.download:hover { text-decoration:underline; }
    /* MAIN CONTENT */
.main {
  padding: 20px 30px;
  background: #f8f9fa;
  min-height: 100vh;
}

.main h1 {
  margin-bottom: 25px;
  color: #333;
  font-size: 28px;
}

/* Stats Section */
.main .stats {
  display: flex;
  gap: 20px;
  margin-bottom: 40px;
}

.main .card {
  flex: 1;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.2s, box-shadow 0.2s;
}

.main .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.main .card h2 {
  font-size: 18px;
  margin-bottom: 10px;
  color: #555;
}

.main .card span {
  font-size: 32px;
  font-weight: bold;
  color: #d6681aff;
}

/* Activities Section */
.main .activities {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.main .activities h3 {
  margin-bottom: 15px;
  font-size: 20px;
  color: #333;
}

.main .activity {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
  font-size: 15px;
  color: #444;
}

.main .activity:last-child {
  border-bottom: none;
}

.main .activity small {
  display: block;
  color: #888;
  font-size: 13px;
  margin-top: 5px;
}
.card-link {
  text-decoration: none;
  color: inherit;
  flex: 1; /* same as card */
}

.card-link .card {
  cursor: pointer;
}
.details-box {
  position: fixed;
  top: 50px;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  max-height: 70%;
  overflow-y: auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  z-index: 1000;
}

.details-box .close-btn {
  float: right;
  background: #d6681aff;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 5px 10px;
  cursor: pointer;
}

.details-box h3 {
  margin-top: 0;
  color: #d6681aff;
}

    </style>
    <style>
 .navbar {
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 5px;
      background: #fff; /* you can change */
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .navbar .logo span {
      font-size: 30px;
      font-weight: 900;
      color: #d6681aff; /* matches your logo */
      letter-spacing: -1px;
    }

    .navbar .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .navbar .nav-links li a {
      text-decoration: none;
      font-weight: 700;
      font-size: 20px;
      color: #636161ff;
      transition: color 0.3s ease, transform 0.2s ease;
    }

    .navbar .nav-links li a:hover,
    .navbar .nav-links li a.active {
      color: #d6681aff;
      transform: scale(1.05);
    }

    .menu-toggle {
      font-size: 26px;
      background: none;
      border: none;
      cursor: pointer;
    }
    /* Navbar Layout */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 18px;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  position: relative;
  z-index: 10;
}

/* Right Controls (Translate + Admin) */
.right-controls {
  display: flex;
  align-items: center;
  gap: 18px;
}

.translate-btn {
  background: linear-gradient(45deg, #ff6a00, #ff4500);
  border-radius: 20px;
  padding: 4px 10px;
  color: #fff;
  font-size: 13px;
  box-shadow: 0 4px 10px rgba(255, 102, 0, 0.4);
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}
.translate-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 15px rgba(255, 102, 0, 0.6);
}

/* Hide default Google Translate UI */
.goog-te-banner-frame.skiptranslate,
.goog-te-gadget-icon,
.goog-te-gadget-simple {
  display: none !important;
}
/* Stealthy Admin Link */
.admin-link {
  text-decoration: none;
  color: transparent;          /* make invisible */
  font-weight: 600;
  font-size: 15px;
  background-image: linear-gradient(90deg, #d6681a 0%, #fff 100%);
  background-size: 0% 2px;
  background-repeat: no-repeat;
  background-position: bottom left;
  transition: all 0.3s ease;
  user-select: none;           /* prevents accidental selection */
  opacity: 0.05;               /* barely visible */
}

/* When hovered, show it */
.admin-link:hover {
  opacity: 1;
  color: #d6681a;
  background-size: 100% 2px;
}

/* Hide Admin on Mobile */
@media (max-width: 768px) {
  .admin-link {
    display: none;
  }
}

/* Orange Splash */
/* Orange Splash */
.triangle-splash {
  position: absolute;
  top: 0;
  left: -100%;
  width: 55%;
  height: 60%;
  background: #d6681a;  /* solid orange */
  clip-path: polygon(0 0, 100% 0, 0 100%);
  z-index: 5;
  box-shadow: 5px 0 25px rgba(0,0,0,0.25);
  animation: slideInTriangle 1.5s cubic-bezier(0.77, 0, 0.175, 1) forwards;
  /* decorative crackers image */
  background-image: url("https://png.pngtree.com/png-clipart/20231004/original/pngtree-diwali-firecracker-clipart-cartoon-colorful-png-image_13230975.png");
  background-repeat: no-repeat;
  background-size: 220px;
  background-position: bottom right;
}

/* Fireworks Overlay */
.fireworks-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 90%;
  height: 70%;
  object-fit: cover;
  opacity: 0.85;
  animation: fireworksFade 3s ease-in-out infinite alternate;
  pointer-events: none; /* prevents blocking clicks */
}

/* Logo & text inside splash */
.triangle-content {
  position: absolute;
  top: 30%;
  transform: translateY(-50%);
  text-align: left;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  opacity: 0;
  animation: fadeInText 2s ease forwards;
  animation-delay: 1.2s;
  z-index: 2;
}

.triangle-content img {
  width: 120px;
  border-radius: 50%;
  margin-bottom: 1px;
  padding: 0px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.4);
}

.triangle-content h1 {
  font-size: 40px;
  font-weight: 400;
  color: #ffffff;
  text-shadow: 
    -2px -2px 0 #66310bff,  
     2px -2px 0 #d6681a,  
    -2px  2px 0 #572908ff,  
     2px  2px 0 #d6681a,  
     0px  0px 15px rgba(0,0,0,0.4);
  letter-spacing: -2px;
}


/* Animations */
@keyframes slideInTriangle {
  from { left: -100%; opacity: 0; }
  to { left: 0; opacity: 1; }
}

@keyframes fadeInText {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fireworksFade {
  from { opacity: 0.7; transform: scale(1); }
  to { opacity: 1; transform: scale(1.05); }
}

/* Hide on small screens */
@media (max-width: 768px) {
  .triangle-splash {
    display: none;
  }
}
.translate-switch {
  position: relative;
  display: inline-block;
  width: 70px;
  height: 30px;
}

/* Hide checkbox */
.translate-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* Slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background: #ff6a00;
  border-radius: 30px;
  transition: 0.4s;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 5px;
  font-weight: bold;
  color: white;
  font-family: 'Poppins', sans-serif;
}

/* EN/HI inside slider */
.lang {
  z-index: 2;
  font-size: 12px;
}

/* Slider circle */
.slider:before {
  content: "";
  position: absolute;
  height: 24px;
  width: 24px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.4s;
  z-index: 1;
}

/* Checked state */
input:checked + .slider {
  background: #4caf50;
}

input:checked + .slider:before {
  transform: translateX(40px);
}

.site-footer {
  background-color: #222;
  color: #fff;
  text-align: center;
  padding: 20px 15px;
  font-family: 'Poppins', sans-serif;
  margin-top: 50px;
}

.site-footer a {
  color: #ff6a00;
  text-decoration: none;
}

.site-footer a:hover {
  text-decoration: underline;
}

.footer-content p {
  margin: 5px 0;
  font-size: 14px;
}

</style>
</head>
<body>
<nav class="navbar">
    <div class="logo">🎆 Safal Sales</div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li>
  <a href="cart.php" class="active">
    Cart 🛒 <span id="cartCount" class="cart-count">0</span>
  </a>
</li>

      <li><a href="admin_dashboard.php">Admin</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
    <div id="google_translate_element"></div>
  </nav>

<div class="wrapper">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="admin_products.php">🛍 Products</a>
    <a href="admin_orders.php">📦 Orders</a>
    <a href="admin_check.php" class="active">✅ Order Check</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <h2>Upload Order File (PDF / CSV / Excel)</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="order_file" accept=".pdf,.csv,.xls,.xlsx" required>
      <button type="submit">Check Inventory</button>
    </form>

    <?php if (!empty($results)): ?>
      <h3>Results (<?= count($results) ?>)</h3>
      <table>
        <thead>
          <tr>
            <th>Raw Line</th>
            <th>Parsed Product</th>
            <th>Ordered</th>
            <th>Matched Product</th>
            <th>Stock</th>
            <th>Match %</th>
            <th>Method</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['raw']) ?></td>
            <td><?= htmlspecialchars($r['parsed_product']) ?></td>
            <td><?= intval($r['ordered']) ?></td>
            <td><?= htmlspecialchars($r['matched_name'] ?? '-') ?></td>
            <td><?= intval($r['stock']) ?></td>
            <td><?= $r['score'] ?>%</td>
            <td><?= htmlspecialchars($r['matched_by']) ?></td>
            <td>
              <?php
                if (strpos($r['status'],'✅')!==false) echo "<span class='ok'>{$r['status']}</span>";
                elseif (strpos($r['status'],'⚠')!==false) echo "<span class='warn'>{$r['status']}</span>";
                else echo "<span class='bad'>{$r['status']}</span>";
              ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <?php if (!empty($debug)): ?>
      <div class="debug">
        <strong>Debug:</strong>
        <ul><?php foreach ($debug as $d) echo "<li>".htmlspecialchars($d)."</li>"; ?></ul>
      </div>
    <?php endif; ?>
  </div>
</div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'en,hi'}, 'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
