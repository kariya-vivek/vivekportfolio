<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<style>
		body {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
			margin: 0;
		}
		.c {
			width: 360px;
			height: 450px;
			background-color: #f5deb3;
			border-radius: 24px;
			flex-direction: column;
			text-align: center;
		}
		input {
			font-size: 22px;
			border-radius: 6px;
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
<body bgcolor="#581845">
	<div class="c">
		<br><br>
		<h1 style="color:#581845">Forgot Password</h1>
		<br>
		<form>
			Enter Email address
			<br><br>
			<input type="text" id="emailid" placeholder="enter your email address" style="font-size:25px;">
			<br><br>
			<input type="button" id="sendbtn" value="send otp" name="senotp" style="background-color:#b08d57;color:black; padding: 4px 114px;border-radius:6px;">
			<div style="display:none" id="cfbtn">
				<input type="text" id="otpt" placeholder="enter otp" style="font-size:25px;">
				<br><br>
				<input type="button" id="sotp" value="continue" name="fnbtn" style="background-color:#b08d57;color:white; padding: 4px 114px;border-radius:6px;">
				<br><br>
				<input type="button" value="cancel" name="Cancel" style="background-color:white;color:black; padding: 4px 122px;border-radius:6px;">
				<br><br>
			</div>
		</form>
	</div>
	<script>
  function showPopup(message) {
    const popup = document.createElement("div");
    popup.textContent = message;
    popup.style.position = "fixed";
    popup.style.top = "50%";
    popup.style.left = "50%";
    popup.style.transform = "translate(-50%, -50%)";
    popup.style.backgroundColor = "#fff";
    popup.style.color = "#581845";
    popup.style.padding = "15px 25px";
    popup.style.borderRadius = "10px";
    popup.style.boxShadow = "0 0 10px rgba(0,0,0,0.3)";
    popup.style.fontSize = "18px";
    popup.style.zIndex = "999";
    popup.style.textAlign = "center";

    document.body.appendChild(popup);

    setTimeout(() => {
      document.body.removeChild(popup);
    }, 1000); // 1 second
  }
</script>
	<script>
		$(document).ready(function () {
			$("#sendbtn").click(function() {
				$("#cfbtn").show();
				$("#sendbtn").hide();
				var keyword = $("#emailid").val();
				$.ajax({
					url: 'mail.php',
					type: 'POST',
					data: { keyword: keyword },
					success: function(response) {
						// Show OTP section if needed
					}
				});
			});

			$("#sotp").click(function() {
				var keyword = $("#emailid").val();
				var otp = $("#otpt").val();
				$.ajax({
					url: 'verifiy.php',
					type: 'POST',
					data: { keyword: keyword, otp: otp },
					success: function(response) {
						if (response.trim() == "1") {
							window.location.href = "reset.php?emailid=" + encodeURIComponent(keyword);
						} else {
							showPopup("Wrong OTP. Please try again.");
						}
					}
				});
			});
		});
	</script>
	
</body>
</html>
