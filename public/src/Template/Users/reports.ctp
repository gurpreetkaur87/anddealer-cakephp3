<div class="row block dealer-report-block">
	<div class="divider"></div>
	<ul class="breadcrumb">
		<li><a href="#">Admin</a></li>
		<li><a href="#" class="current">Reports</a></li>
	</ul>
	<h2>Dealer Administration Reports</h2>
	<p>Please ensure that you have filled in the data field for your report before executing it.</p>

	<div class="dealer-reports">
		<div class="dealer-report">
			<div class="col-xs-12 col-md-3 dealer-report-title">
				<h2><!--Most Active Report-->Complete List of Dealers</h2>
			</div>
			<div class="col-xs-12 col-md-9 dealer-report-form">
				<form id="dealer-report-active-form" action="recentAccess" >
					<div class="dealer-report-content">
						<span>A complete list of Dealers showing those who most frequently access the site at the top.</span>
					</div>
					<button type="submit" class="excute-button btn">Execute</button>
				</form>
			</div>
		</div><!-- report 2 end -->
		<div class="dealer-report">
			<div class="col-xs-12 col-md-3 dealer-report-title">
				<h2>Recent Access Report</h2>
			</div>
			<div class="col-xs-12 col-md-9 dealer-report-form">
				<form id="dealer-report-access-form" action="recentAccess">
					<div class="dealer-report-content">
						<span>Dealers who have accessed the site in the last</span>
						<input type="number" name="access_number" id="access-number" required/>
						<span>days.</span>
					</div>
					<button type="submit" class="excute-button btn">Execute</button>
				</form>
			</div>
		</div><!-- report 1 end -->
		<div class="dealer-report">
			<div class="col-xs-12 col-md-3 dealer-report-title">
				<h2>Activity by Company Report</h2>
			</div>
			<div class="col-xs-12 col-md-9 dealer-report-form">
				<form id="dealer-report-activity-form" action="recentAccess" >
					<div class="dealer-report-content">
						<span>Please enter a dealer code:</span>
						<input type="text" name="code" id="activity-company"/>
					</div>
					<button type="submit" class="excute-button btn">Execute</button>
				</form>
			</div>
		</div><!-- report 3 end -->
		<div class="dealer-report">
			<div class="col-xs-12 col-md-3 dealer-report-title">
				<h2>Disabled Dealers</h2>
			</div>
			<div class="col-xs-12 col-md-9 dealer-report-form">
				<form id="dealer-report-disable-form" action="disabledDealers">
					<div class="dealer-report-content">
						<span>A List of dealers whose access to the system has been disabled.</span>

					</div>
					<button type="submit" class="excute-button btn">Execute</button>
				</form>
			</div>
		</div><!-- report 4 end -->
	</div>
</div>
