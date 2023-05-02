<h1 class="text-center mt-4 m=t-md-5 mb-0 fw-bolder">Hello, <?php echo $_SESSION['user_first'] . " " . $_SESSION['user_last']; ?>!</h1>

<p class="text-center mt-2 mb-1">
	<i><?php echo $_SESSION['user_email']; ?></i>
</p>

<p class="text-center mb-4">
	<a class="primary-link" href="?view=wallet">My Wallet ›</a>
	<a class="primary-link" style="margin-left: 15px;" href="./?action=logout">Logout ›</a>
</p>

<div class="row" style="overflow: scroll; height: 80vh;">
	<div class="col-0 col-xl-1 d-none d-xl-block"></div>
	<div class="col-12 col-md-5 col-lg-4 col-xl-3">

		<div class="card card-light pt-2 p-3 mb-4">
			<div class="card-body">
				<h4 class="fw-bolder">Account Balence</h4>
				<h2 style="font-weight: 800; font-size: 28pt;">$100.00</h2>
				<a class="primary-link" href="./?view=wallet&go=deposit">Make a Deposit ›</a>
				<br />
				<a class="primary-link" href="./?view=wallet&go=withdraw">Withdraw to Bank ›</a>
			</div>
		</div>

		<div class="card card-light py-0 px-3 mb-4">
			<div class="card-body">

				<form id="pr-form" action="./?action=transfer" method="POST">

					<ul class="nav nav-underline pb-3" role="tablist">
						<li class="nav-item" role="presentation">
							<a id="pay-tab" class="nav-link active" data-bs-toggle="tab" data-bs-target="#pay-tab-pane" role="tab">Pay</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="request-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#request-tab-pane" role="tab">Request</a>
						</li>
					</ul>

					<script type="application/javascript">
						const $foo = $("#pay-tab").classChange((el, newClass) => {
							if (newClass.includes("active")) {
								$("#pr-form").attr('action', './?action=transfer');
							} else {
								$("#pr-form").attr('action', './?action=request');
							}
						});
					</script>

					<div class="input-group mb-2">
						<input id="user-input" type="text" name="user" class="form-control" list="user-list" placeholder="Name, @user, email">
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
						<input type="text" name="amount" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" placeholder="0.00">
					</div>

					<textarea name="desc" class="form-control" rows="2" placeholder="What is this for?"></textarea>

					<div class="tab-content">
						<div class="tab-pane show active" id="pay-tab-pane" role="tabpanel" tabindex="0">
							<button type="submit" class="btn btn-primary btn-block w-100 my-3 px-5 fw-bold">Pay Now</button>
						</div>
						<div class="tab-pane" id="request-tab-pane" role="tabpanel" tabindex="0">
							<button type="submit" class="btn btn-primary btn-block w-100 my-3 px-5 fw-bold">Send Invoice</button>
						</div>
					</div>

				</form>

			</div>
		</div>

	</div>
	<div class="col-12 col-md-7 col-lg-8 col-xl-7">
		
		<div class="card card-light pt-2 p-3 mb-4">
			<div class="card-body">
				<h4 class="fw-bolder">Pending Invoices</h4>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon pending"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon pending"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

				<h4 class="fw-bolder mt-4">Recent Transactions</h4>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon transfer"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon subtract"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon add"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-body transact-body row">
						<div class="col-2 col-lg-1 pe-0 ps-2 ps-lg-3 ps-xl-2">
							<div class="transact-icon transfer"></div>
						</div>
						<div class="col-10 col-xl-11 ps-2 ps-lg-3 ps-xl-2">
							<h6 class="transact-desc">Transaction Desc</h6>
							<p class="transact-txt">They Requested:  $20.00</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="col-0 col-xl-1 d-none d-xl-block"></div>
</div>