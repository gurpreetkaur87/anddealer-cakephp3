<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>
        <?php //$cakeDescription ?>
        <?php //$this->fetch('title') ?>
        <?= $title ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('localstyle.css') ?>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-k2/8zcNbxVIh5mnQ52A0r3a6jAgMGxFJFE2707UxGCk= sha512-ZV9KawG2Legkwp3nAlxLIVFudTauWuBpC10uEafMHYL0Sarrz5A7G79kXh5+5+woxQ5HM559XX2UZjMJ36Wplg==" crossorigin="anonymous">
	<?php //$this->Html->css('font-awesome.min.css') ?>
	
	<!-- jQuery library 2.1.3 -->
	<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script-->
	<?= $this->Html->script('jquery.min.js') ?>
	<!-- Bootstrap compiled JavaScript -->
	<!--script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script-->
	<?= $this->Html->script('bootstrap.min.js') ?>
	<!-- Additional Javascript/jQuery -->
	<?= $this->Html->script('general.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>	
	<!-- header start -->
		<header>
			<div class="container">
				<div class="row">
				<!-- logo -->
					<a href="/users/home" id="logo">
						<!--img src="img/logo.jpg" alt="A&D Weighing"-->
						<?php echo $this->Html->image('logo.jpg', ['alt' => 'A&D Weighing']); ?>
					</a>
					<div class="header-user">
						<span><?php echo $_SESSION['Auth']['User'][0]->first_name.' '.$_SESSION['Auth']['User'][0]->last_name; ?></span>
						<a href="/users/logout">Logout</a>
					</div>
					<div class="burger_btn mobile"><?php echo $this->Html->image('hamb1.png', ['alt' => 'A&D Weighing']); ?></div>
					<nav id="header-nav" class="col-md-11 ">
						<div class="close_btn mobile"><?php echo $this->Html->image('hamb2.png', ['alt' => 'A&D Weighing']); ?></div>

						<ul class="level0">
							<li class="level1 parent stock-level1">
								<a href="#" class="nav-title"><span>Stock Enquiry</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
								<ul class="level2 child">
									<li><a href="/users/stock_enquiry">General Stock</a></li>
									<li><a href="/users/part_enquiry">Spare Parts</a></li>
									
								</ul>
							</li>
							<li class="level1 parent technical-level1">
								<a href="/technicals" class="nav-title"><span>Technical Documents</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
								<ul class="level2 child">
									<?php foreach($technicalMenus as $technicalMenu){?>
										<li><a href="/technicals/view/<?php echo $technicalMenu->id; ?>"><?php echo $technicalMenu->name; ?></a></li>
									<?php } ?>
								</ul>
							</li>
							<li class="level1 parent product-diagram-level1">
								<a href="/product-diagrams" class="nav-title"><span>Product Diagram</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
								<ul class="level2 child">
									<?php foreach($productDiagramsMenus as $productDiagramsMenu){?>
										<li><a href="/product-diagrams/view/<?php echo $productDiagramsMenu->id; ?>"><?php echo $productDiagramsMenu->name; ?></a></li>
									<?php } ?>
									<!--li><a href="#">Instruction Manuals</a></li>
									<li><a href="#">Maintenance Manuals</a></li-->
								</ul>
							</li>
							<li class="level1 parent manuals-level1">
								<a href="/users/manuals" class="nav-title"><span>Manuals</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
								<ul class="level2 child">
									<li><a href="/instructions">Instruction Manuals</a></li>
									<li><a href="/maintains">Maintenance Manuals</a></li>
								</ul>
							</li>
							<li class="level1 install-level1">
								<a href="/installations" class="nav-title"><span>Installation Diagrams</span> </a>
							</li>
							
							<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
							<li class="level1 parent admin-level1">
								<a href="/users" class="nav-title"><span>Admin</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
								<ul class="level2 child">
									<li><a href="/users/add">Add Dealer</a></li>
									<li><a href="/users">View All Dealers</a></li>
									<li><a href="/users/search">Search</a></li>
									<li><a href="/users/reports">Reports</a></li>
									<li><a href="/banners">Banner</a></li>
								</ul>
							</li>
							<?php } else { ?>
							<li class="level1 parent non-admin">
								<a href="tel:0883018100">(08) 8301 8100</a>
							</li>
							<?php } ?>
						</ul>
					</nav>
				</div>
			</div>
		</header>
		<!-- header end -->
		<div class="main home-main">
			<div class="container">
				<?= $this->fetch('content') ?>
			</div><!-- container end -->
		</div>
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 copyright">All Material and Images Copyright &copy; 2016 A&D Australasia Pty Ltd</div>
				</div>
				<div class="row">
					<div class="col-xs-12 text-center blue">AND Weighing Dealer Support Portal</div>
				</div>
			</div> 

		</footer>
	
</body>
</html>
