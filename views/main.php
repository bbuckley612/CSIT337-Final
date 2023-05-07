<h1 class="text-center mt-4 m=t-md-5 mb-0 fw-bolder">Hello, <?php echo $_SESSION['user_first'] . " " . $_SESSION['user_last']; ?>!</h1>

<p class="text-center mt-2 mb-1">
	<i><?php echo $_SESSION['user_email']; ?></i>
</p>

<p class="text-center mb-4">
	<a class="primary-link" href="?view=wallet">Wallet ›</a>
	<a class="primary-link" style="margin-left: 15px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#account">My Account ›</a>
	<a class="primary-link" style="margin-left: 15px;" href="./?action=logout">Logout ›</a>
</p>

<div class="row" style="overflow: scroll; height: 80vh;">
	<div class="col-0 col-xl-1 d-none d-xl-block"></div>
	<div class="col-12 col-md-5 col-lg-4 col-xl-3">

		<div class="card card-light pt-2 p-3 mb-4">
			<div class="card-body">
				<h4 class="fw-bolder">Account Balence</h4>
				<h2 style="font-weight: 800; font-size: 28pt;">$
					<?php

						$uid = $_SESSION['user_id'];
						$sql = "SELECT t.amount, a1.user_id AS uid1, a1.priority AS priority1, a2.user_id AS uid2, a2.priority AS priority2
								FROM transactions AS t
								LEFT JOIN accounts AS a1
									ON t.account_id = a1.id
								LEFT JOIN accounts AS a2
									ON t.recipient_id = a2.id
								WHERE a1.user_id = $uid OR a2.user_id = $uid";
						$result = $conn->query($sql);

						if ($result->num_rows > 0) {
						  
						  $balance = 0;

						  while($row = $result->fetch_assoc()) {
						    if ($row['uid1'] == $uid && $row['priority1'] == 1) {
						    	$balance -= $row['amount'];
						    } else if ($row['uid2'] == $uid && $row['priority2'] == 1) {
						    	$balance += $row['amount'];
						    }
						  }

						  echo number_format((float) $balance, 2, '.', '');
						} else {
						  echo "0.00";
						}

					?>
				</h2>
				<a class="primary-link" href="./?view=wallet&go=deposit">Make a Deposit ›</a>
				<br />
				<a class="primary-link" href="./?view=wallet&go=withdraw">Withdraw to Bank ›</a>
			</div>
		</div>

		<div class="card card-light py-0 px-3 mb-4">
			<div class="card-body">

				<form id="pr-form" action="<?php if (!empty($_GET['try']) && $_GET['try'] == "request") echo "./?action=request"; else echo "./?action=pay"; ?>" method="POST">

					<ul class="nav nav-underline pb-3" role="tablist">
						<li class="nav-item" role="presentation">
							<a id="pay-tab" class="nav-link <?php if (empty($_GET['try']) || $_GET['try'] == "pay") echo "active"; ?>" data-bs-toggle="tab" data-bs-target="#pay-tab-pane" role="tab">Pay</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="request-tab" class="nav-link <?php if (!empty($_GET['try']) && $_GET['try'] == "request") echo "active"; ?>" data-bs-toggle="tab" data-bs-target="#request-tab-pane" role="tab">Request</a>
						</li>
					</ul>

					<script type="application/javascript">
						const $foo = $("#pay-tab").classChange((el, newClass) => {
							if (newClass.includes("active")) {
								$("#pr-form").attr('action', './?action=pay');
							} else {
								$("#pr-form").attr('action', './?action=request');
							}
						});
					</script>

					<div class="input-group mb-2">
						<input id="user-input" type="text" name="user" class="form-control <?php if ($_GET['error'] == "format" || $_GET['error'] == "user") echo "is-invalid"; ?>" list="user-list" placeholder="Name, @user, email">
						<datalist id="user-list"></datalist>
					</div>

					<script type="application/javascript">
						let lookup;
						$("#user-input").on('input', function(e) {
							clearTimeout(lookup);
							lookup = setTimeout(function() {
								let val = $("#user-input").val();
								$.post("./?action=search", {
									query: val
								}, function(data, status) {
									if (status == "success") {
										let json = JSON.parse(data);
										$("#user-list").empty();
										for (let i = 0; i < json.length; i++) {
											$("#user-list").append("<option value='" + json[i].email + "'>" + json[i].first + " " + json[i].last + "</option>");
										}
									} else if (confirm("An unknown error occurred. A reload is required.")) {
										location.reload();
									}
								});
							}, 500);
						});
					</script>

					<div class="input-group mb-2">
						<span class="input-group-text">$</span>
						<input type="text" name="amount" class="form-control <?php if ($_GET['error'] == "amount") echo "is-invalid"; ?>" aria-label="Dollar amount (with dot and two decimal places)" placeholder="0.00">
					</div>

					<textarea name="desc" class="form-control" rows="2" placeholder="What is this for?"></textarea>

					<div class="tab-content">
						<div class="tab-pane <?php if (empty($_GET['try']) || $_GET['try'] == "pay") echo "show active"; ?>" id="pay-tab-pane" role="tabpanel" tabindex="0">
							<button type="submit" class="btn btn-primary btn-block w-100 my-3 px-5 fw-bold">Pay Now</button>
						</div>
						<div class="tab-pane <?php if (!empty($_GET['try']) && $_GET['try'] == "request") echo "show active"; ?>" id="request-tab-pane" role="tabpanel" tabindex="0">
							<button type="submit" class="btn btn-dark btn-block w-100 my-3 px-5 fw-bold">Send Invoice</button>
						</div>
					</div>

				</form>

				<?php if (!empty($_GET['try'])) { ?>

					<?php if ($_GET['error'] == "format") { ?>
						<div class="alert alert-danger" role="alert">
							Recipient's email isn't formatted correctly.
						</div>
					<?php } else if ($_GET['error'] == "amount") { ?>
						<div class="alert alert-danger" role="alert">
							Please enter a valid request amount in USD.
						</div>
					<?php } else if ($_GET['error'] == "user") { ?>
						<div class="alert alert-danger" role="alert">
							Couldn't find the recipient's email in our records.
						</div>
					<?php } else if ($_GET['error'] == "description") { ?>
						<div class="alert alert-danger" role="alert">
							Invalid description. Must be between 1 and 150 characters.
						</div>
					<?php } else if ($_GET['success'] && $_GET['try'] == "pay") { ?>
						<div class="alert alert-success" role="alert">
							Payment was successful!
						</div>
					<?php } else if ($_GET['success'] && $_GET['try'] == "request") { ?>
						<div class="alert alert-success" role="alert">
							Invoice was sent!
						</div>
					<?php } ?>

				<?php } ?>

			</div>
		</div>

	</div>
	<div class="col-12 col-md-7 col-lg-8 col-xl-7">
		
		<div class="card card-light pt-2 p-3 mb-4">
			<div class="card-body">
				
				<?php

					$sql = "SELECT i.amount, i.description, i.created, i.user_id AS uid1, u1.first AS ufirst1, u1.last AS ulast1, u1.email AS uemail1, i.recipient_id AS uid2, u2.first AS ufirst2, u2.last AS ulast2, u2.email AS uemail2
							FROM invoices AS i
							LEFT JOIN users AS u1
								ON i.user_id = u1.id
							LEFT JOIN users AS u2
								ON i.recipient_id = u2.id
							WHERE (i.user_id = $uid OR i.recipient_id = $uid) AND i.status = 1
							ORDER BY i.id DESC";
					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) > 0) {

					  echo '<h4 class="fw-bolder">Pending Invoices</h4>';

					  while ($row = mysqli_fetch_assoc($result)) {

						$desc = $row['description'];
					  	$datetime = date("n/j/y g:ia", strtotime($row['created']));
					  	$amount = abs($row['amount']);

					  	if ($row['uid1'] == $uid) {
					  		$title = "You requested $" . $row['amount'] . " from " . $row['ufirst2'] . " " . $row['ulast2'];
					  		$type = "pending";
					  	} else {
					  		$title = $row['ufirst1'] . " " . $row['ulast1'] . " requests $" . $row['amount'];
					  		$type = "request";
					  	}

					    echo <<<INVOICE
							<div class="card mt-2">
								<div class="card-body transact-body row">
									<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-4 ps-xl-4">
										<div class="transact-icon $type"></div>
									</div>
									<div class="col-10 col-lg-11 ps-2 ps-lg-4 ps-xl-4">
										<div class="row">
											<div class="col-12 pe-0 transact-desc" style="font-weight: 600;">
												$title
											</div>
											<div class="col-4 col-md-5 text-end transact-desc">
												<span class="position-absolute" style="font-size: 13pt; display: flex; right: 16px;">
													<b style="font-weight: 600;">$date</b>
													<span class="d-none d-md-inline ps-2" style="font-weight: 300;">
														$time
													</span>
												</span>
											</div>
										</div>
										<div class="row">
											<div class="col-12 col-lg-12 pe-0 transact-txt">
												$desc&nbsp;&nbsp;|&nbsp;&nbsp;$datetime
											</div>
										</div>
									</div>
								</div>
							</div>
						INVOICE;
					  }

					  echo '<br />';

					}

				?>

				<h4 class="fw-bolder">Recent Transactions</h4>

				<?php

					$sql = "SELECT t.amount, t.description, t.created, a1.user_id AS uid1, a1.name AS acct1, u1.first AS ufirst1, u1.last AS ulast1, u1.email AS uemail1, a2.user_id AS uid2, a2.name AS acct2, u2.first AS ufirst2, u2.last AS ulast2, u2.email AS uemail2
							FROM transactions AS t
							LEFT JOIN accounts AS a1
								ON t.account_id = a1.id
							LEFT JOIN accounts AS a2
								ON t.recipient_id = a2.id
							LEFT JOIN users AS u1
								ON a1.user_id = u1.id
							LEFT JOIN users AS u2
								ON a2.user_id = u2.id
							WHERE a1.user_id = $uid OR a2.user_id = $uid
							ORDER BY t.id DESC";

					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) > 0) {
					  while ($row = mysqli_fetch_assoc($result)) {

						$desc = $row['description'];
					  	$date = date("n/j/y", strtotime($row['created']));
					  	$time = date("g:ia", strtotime($row['created']));
					  	$amount = abs($row['amount']);
					  	$from = $row['ufirst1'] . " " . $row['ulast1'];
					  	$to = $row['ufirst2'] . " " . $row['ulast2'];

					  	if ($row['uid1'] == $row['uid2']) {
					  		$type = "transfer";
					  		$blurb = "You Transferred";
					  		$from = $row['acct1'];
					  		$to = $row['acct2'];
					  	} else if ($row['uid1'] == $uid) {
					  		$type = "subtract";
					  		$blurb = "You Sent";
					  	} else {
					  		$type = "add";
					  		$blurb = "You Received";
					  	}

					    echo <<<TRANSACTION
							<div class="card mt-2">
								<div class="card-body transact-body row">
									<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-4 ps-xl-4">
										<div class="transact-icon $type"></div>
									</div>
									<div class="col-10 col-lg-11 ps-2 ps-lg-4 ps-xl-4">
										<div class="row">
											<div class="col-8 col-md-7 col-lg-6 pe-0 transact-desc" style="font-weight: 600;">
												$desc
											</div>
											<div class="col-4 col-md-5 text-end transact-desc">
												<span class="position-absolute" style="font-size: 13pt; display: flex; right: 16px;">
													<b style="font-weight: 500;">$date</b>
													<span class="d-none d-md-inline ps-2" style="font-weight: 300;">
														$time
													</span>
												</span>
											</div>
										</div>
										<div class="row">
											<div class="col-12 col-lg-12 pe-0 transact-txt">
												$blurb:&nbsp;&nbsp;$$amount
											</div>
											<div class="col-12 col-lg-5 col-xl-5 pe-0 transact-txt">
												From:&nbsp;&nbsp;$from
											</div>
											<div class="col-12 col-lg-5 col-xl-5 pe-0 transact-txt">
												To:&nbsp;&nbsp;$to
											</div>
										</div>
									</div>
								</div>
							</div>
						TRANSACTION;
					  }
					} else {
						echo 'No transactions yet. Make <a href="./?view=wallet&amp;go=deposit">your first deposit</a> now!';
					}

				?>

			</div>
		</div>
	</div>
	<div class="col-0 col-xl-1 d-none d-xl-block"></div>
</div>

<?php include "includes/account.php"; ?>