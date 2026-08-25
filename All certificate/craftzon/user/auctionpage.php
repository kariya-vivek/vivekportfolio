<?php
// --- DB Connection ---
$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
// --- Auto-update auction status if ended ---
$currentTime = date('Y-m-d H:i:s');
$updateStatusQuery = "UPDATE auction_table 
                      SET status='ended' 
                      WHERE status='active' AND end_time <= '$currentTime'";
mysqli_query($con, $updateStatusQuery);


// --- Handle bid (AJAX same file) ---
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) && $_POST['action'] === "place_bid") {
    header("Content-Type: application/json");

    $auction_id = intval($_POST['auction_id']);
    $uid        = intval($_POST['uid']);
    $bid        = floatval($_POST['bid']);

    if ($auction_id <= 0 || $uid <= 0 || $bid <= 0) {
        echo json_encode(["success"=>false, "message"=>"Invalid request"]);
        exit;
    }

    $res = mysqli_query($con, "SELECT * FROM auction_table WHERE auction_id=$auction_id LIMIT 1");
    $auction = mysqli_fetch_assoc($res);

    if (!$auction) {
        echo json_encode(["success"=>false, "message"=>"Auction not found"]);
        exit;
    }
    if ($auction['status'] === "ended") {
        echo json_encode(["success"=>false, "message"=>"Auction ended"]);
        exit;
    }

    $current_price = floatval($auction['current_price']);
    if ($bid <= $current_price) {
        echo json_encode(["success"=>false, "message"=>"Bid must be greater than current price"]);
        exit;
    }

    // Update auction table
    $update = "UPDATE auction_table 
               SET current_price=$bid, highest_bidder=$uid 
               WHERE auction_id=$auction_id";
    mysqli_query($con, $update);

    // Get bidder name
    $uname = "Unknown";
    $uRes = mysqli_query($con, "SELECT uname FROM craftus_reg WHERE u_id=$uid");
    if ($uRow = mysqli_fetch_assoc($uRes)) {
        $uname = $uRow['uname'];
    }

    echo json_encode([
        "success"=>true,
        "message"=>"✅ Bid placed successfully",
        "new_price"=>$bid,
        "bidder"=>$uname
    ]);
    exit;
}

// --- Normal page load ---
$auction_id = isset($_POST['auction_id']) ? intval($_POST['auction_id']) : 0;
$uid        = isset($_POST['uid']) ? intval($_POST['uid']) : 0;

if ($auction_id <= 0) {
    die("Invalid auction.");
}

// Auction + product
$sql = "SELECT a.*, p.product_name, p.product_description, p.image 
        FROM auction_table a
        JOIN product_table p ON a.product_id=p.product_id
        WHERE a.auction_id=$auction_id LIMIT 1";
$res = mysqli_query($con, $sql);
$auction = mysqli_fetch_assoc($res);
if (!$auction) die("Auction not found.");

$currentTime = time();
$endTime     = strtotime($auction['end_time']);
$timeLeft    = max(0, $endTime - $currentTime);

$highestName = "No bids yet";
if ($auction['highest_bidder']) {
    $uRes = mysqli_query($con, "SELECT uname FROM craftus_reg WHERE u_id=".(int)$auction['highest_bidder']);
    if ($uRow = mysqli_fetch_assoc($uRes)) $highestName = $uRow['uname'];
}

// Winner check
$isWinnerNow = ($timeLeft <= 0 && $uid > 0 && $auction['highest_bidder'] == $uid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($auction['product_name']); ?> - Auction</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; }
.auction-container { display:flex; background:#fff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1); max-width:900px; width:100%; overflow:hidden; }
.product-image { flex:1; }
.product-image img { width:100%; height:auto; display:block; }
.product-details { flex:1; padding:30px; display:flex; flex-direction:column; justify-content:space-between; }
.product-details h1 { margin:0; font-size:2em; color:#333; }
.description { color:#666; margin-top:10px; }
.auction-info { display:flex; justify-content:space-between; margin:20px 0; font-weight:bold; }
.current-bid { color:#007bff; }
.time-left { color:#dc3545; }
.bid-form { display:flex; gap:10px; margin-top:20px; }
.bid-form input { flex:1; padding:10px; border:1px solid #ccc; border-radius:4px; }
.bid-form button { padding:10px 20px; background:#28a745; color:#fff; border:none; border-radius:4px; cursor:pointer; }
.bid-form button:hover { background:#218838; }
.message { margin-top:15px; font-weight:bold; display:none; }
.last-bidder { margin-top:10px; font-weight:bold; }
.checkout { margin-top:20px; }
.checkout { margin-top:20px; text-align:center; }

.checkout a button {
  background:#007bff;
  color:#fff;
  border:none;
  padding:12px 24px;
  font-size:16px;
  border-radius:6px;
  cursor:pointer;
  transition:all 0.3s ease;
}

.checkout a button:hover {
  background:#0056b3;
  transform:scale(1.05);
}

.checkout div {
  font-size:18px;
  margin-bottom:10px;
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
<div class="auction-container">
  <div class="product-image">
    <img src="../<?php echo htmlspecialchars($auction['image']); ?>" alt="">
  </div>
  <div class="product-details">
    <h1><?php echo htmlspecialchars($auction['product_name']); ?></h1>
    <p class="description"><?php echo htmlspecialchars($auction['product_description']); ?></p>

    <div class="auction-info">
      <div class="current-bid">Current Bid: <span id="current-bid">₹<?php echo $auction['current_price']; ?></span></div>
      <div class="time-left">Time Remaining: <span id="time-left"></span></div>
    </div>

    <?php if ($timeLeft > 0): ?>
      <form id="bid-form" class="bid-form">
        <input type="number" id="bid-input" min="<?php echo $auction['current_price']+1; ?>" required>
        <button type="submit">Place Bid</button>
      </form>
      <div id="message" class="message"></div>
    <?php endif; ?>

    <div class="last-bidder">Last Bidder: <span id="last-bidder"><?php echo $highestName; ?></span></div>

    <div class="checkout" id="checkout-area">
      <?php if ($timeLeft <= 0): ?>
        <?php if ($isWinnerNow): ?>
  <div style="color:green;">🎉 You are the winning bidder!</div>

  <form id="orderForm" action="orderform.php" method="POST">
      <input type="hidden" name="uid" value="<?php echo htmlspecialchars($uid); ?>">
      <input type="hidden" name="pid" value="<?php echo htmlspecialchars($auction['product_id']); ?>">
      <input type="hidden" name="pnm" value="<?php echo htmlspecialchars($auction['product_name']); ?>">
      <input type="hidden" name="prc" value="<?php echo htmlspecialchars($auction['current_price']); ?>">
    <button type="submit" style="background:#007bff; color:white; border:none; padding:12px 24px; font-size:16px; border-radius:6px; cursor:pointer;">
  Go to Order Form
</button>

  </form>
<?php else: ?>
  

          <div>⏳ Auction ended. Winner: <?php echo $highestName; ?></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="section text-center">
        <a href="crafthome.php?category=home" class="btn-home">Back to Home</a>
    </div>
<script>
// Countdown
let timeLeft = <?php echo $timeLeft; ?>;
const timeLeftDisplay=document.getElementById("time-left");
function updateTimer(){
  if(timeLeft<=0){ timeLeftDisplay.textContent="00:00:00"; return; }
  let h=Math.floor(timeLeft/3600), m=Math.floor((timeLeft%3600)/60), s=timeLeft%60;
  timeLeftDisplay.textContent=h.toString().padStart(2,"0")+":"+m.toString().padStart(2,"0")+":"+s.toString().padStart(2,"0");
  timeLeft--;
}
updateTimer(); setInterval(updateTimer,1000);

// Handle Bid (AJAX same file)
const bidForm=document.getElementById("bid-form");
if(bidForm){
  bidForm.addEventListener("submit",function(e){
    e.preventDefault();
    const bidInput=document.getElementById("bid-input");
    const newBid=parseFloat(bidInput.value);
    if(isNaN(newBid)) return;
    fetch(window.location.href,{
      method:"POST",
      headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body:"action=place_bid&auction_id=<?php echo $auction_id; ?>&uid=<?php echo $uid; ?>&bid="+newBid
    })
    .then(r=>r.json())
    .then(res=>{
      const msg=document.getElementById("message");
      msg.style.display="block";
      msg.style.color=res.success?"green":"red";
      msg.textContent=res.message;
      if(res.success){
        document.getElementById("current-bid").textContent="₹"+res.new_price;
        document.getElementById("last-bidder").textContent=res.bidder;
        bidInput.value="";
        bidInput.min=parseFloat(res.new_price)+1;
      }
    });
  });
}
</script>
</body>
</html>
