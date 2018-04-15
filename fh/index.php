<?php 
	$page_title = "Dashboard";
    include_once('../includes/header_fh.php');
    
?>
<div class="col-lg-12">
     <div class="col-lg-3 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo countPendingContributions();?></div>
                        <div>Pending Contributions</div>
                    </div>
                </div>
            </div>
            <a href="contribution/pending.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
	include_once('../includes/footer.php');
?>