<?php
	$con = mysqli_connect("localhost","root","","Craftzon");
	$oid=$_POST['orderid'];
	$buid=$_POST['uid'];
	$selor="select * from craftorder where orderid=$oid";
	$resor=mysqli_query($con,$selor);
	$rowor=mysqli_fetch_array($resor);
	$invoice_id = 'CZ' . (10000 + $oid);
	$amount = $rowor['price'] * $rowor['quantity']; 
	$tax_amount = $amount * 0.12;
	$seller_query = "
    SELECT s.storenm,s.selleremailid,s.gstinno 
    FROM craftorder co
    JOIN product_table p ON co.productid = p.product_id
    JOIN seller s ON p.crafted_by = s.sellernm
    WHERE co.orderid = $oid
    LIMIT 1
";
$seller_result = mysqli_query($con, $seller_query);
$seller_row = mysqli_fetch_assoc($seller_result);
$seid=$seller_row['selleremailid'];
$gstn=$seller_row['gstinno'];
$store_name = $seller_row ? $seller_row['storenm'] : 'Craftzon';
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Craftzon Invoice</title>
<style>
  body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 20px; }
  .wrap { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
  h1 { margin: 0 0 10px; }
  .label { font-size: 14px; font-weight: bold; margin: 10px 0 4px; display: block; }
  .readonly-field { font-size: 14px; padding: 8px; background: #f9f9f9; border: 1px solid #ccc; border-radius: 4px; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th, td { padding: 8px; border: 1px solid #ccc; text-align: center; }
  th { background: #f0f4f9; }
  .totals { margin-top: 20px; text-align: right; font-size: 16px; }
  .totals div { margin: 4px 0; }
  .controls { margin-top: 20px; text-align: right; }
  button { padding: 8px 16px; border: none; background: #2c7be5; color: #fff; border-radius: 4px; cursor: pointer; }
  button:hover { background: #1a5fc4; }
  @media print {
    body { background: white; padding: 0; }
    .controls { display: none; }
    .wrap { box-shadow: none; border: none; }
  }
</style>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.tagName === 'FORM') {
            if (e.target.dataset.submitted) {
                e.preventDefault();
                return;
            }
            e.target.dataset.submitted = 'true';
            var btn = e.target.querySelector('button[type=\'submit\'], input[type=\'submit\']');
            if (btn) {
                setTimeout(function() {
                    btn.disabled = true;
                    if (btn.tagName === 'BUTTON') {
                        btn.innerHTML = 'Processing...';
                    } else if (btn.tagName === 'INPUT') {
                        btn.value = 'Processing...';
                    }
                }, 10);
            }
        }
    });
});
</script>
</head>
<body>

<div class="wrap">
  <h1><?php echo htmlspecialchars($store_name); ?> Invoice</h1>


  <span class="label">Invoice Number</span>
  <div class="readonly-field"><?php echo $invoice_id; ?></div>
  
  <span class="label">seller gstino Number</span>
  <div class="readonly-field"><?php echo $gstn; ?></div>
  
  <span class="label">seller emailid </span>
  <div class="readonly-field"><?php echo $seid; ?></div>

  <span class="label">Invoice Date</span>
  <div class="readonly-field"><?php echo $rowor['ordertime'];?></div>

  <span class="label">Customer Name</span>
  <div class="readonly-field"><?php echo $rowor['fullname'];?></div>

  <span class="label">Customer Address</span>
  <div class="readonly-field"><?php echo $rowor['address'];?></div>
	
  <span class="label">Order Status</span>
  <div class="readonly-field"><?php echo $rowor['order_status'];?></div>	
  
  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th>Unit Cost</th>
        <th>Qty</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $rowor['productnm'];?></td>
        <td><?php echo $rowor['price'];?></td>
        <td><?php echo $rowor['quantity'];?></td>
        <td><?php echo $amount; ?></td>
      </tr>
    </tbody>
  </table>

  
  <?php
  $prev_status = isset($rowor['previous_status']) ? $rowor['previous_status'] : '';

	if ($rowor['order_status'] === "cancel") {
		if ($prev_status === "shipped") 
		{
			$refund_amount = ($amount + $tax_amount) * 0.95;  // Deduct 5%
		}
		else
		{
			$refund_amount = $amount + $tax_amount;          // Full refund
		}
		echo "
			<div class='totals'>
				<div>Subtotal: $amount</div>
				<div>Tax (12%): $tax_amount</div>
				<div><strong>Refund Amount: " . number_format($refund_amount, 2) . "</strong></div>
				<div><em>Note: Refund after cancellation.</em></div>
			</div>";
	}

	elseif($rowor['order_status']==="return")
	{
      $refund_amount = ($amount + $tax_amount);
      echo "
          <div class='totals'>
              <div>Subtotal: $amount</div>
              <div>Tax (12%): $tax_amount</div>
              <div><strong>Refund Amount: $refund_amount</strong></div>
              <div>Note: Full refund issued for returned item.</div>
          </div>";
	}
	else
	{
		echo "
			<div class='totals'>
				<div>Subtotal: $amount</div>
				<div>Tax (12%): $tax_amount</div>
				<div><strong>Total: " . ($amount + $tax_amount) . "</strong></div>
			</div>";
	}
  ?>
  <div class="controls">
    <button onclick="window.print()">Print / Save as PDF</button>
  </div>
</div>

</body>
</html>
