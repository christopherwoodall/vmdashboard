<?php
// If the SESSION has not started, start it now
if (!isset($_SESSION)) {
    session_start();
}

// If there is no username, then we need to send them to the login
if (!isset($_SESSION['username'])){
  header('Location: ../login.php');
}


require('../header.php');
require('../navbar.php');
?>

<div class="content">
  <div class="card">
    <form action="" method="POST">
      <div class="card-header">
        <h4 class="card-title"> Test Page</h4>
      </div>
      <div class="card-body">
      <?php
      $info = $lv->host_get_node_info();
      $cpu = $lv->host_get_node_cpu_stats();
      $multi_cpu = $lv->host_get_node_cpu_stats_for_each_cpu();
      $mem = $lv->host_get_node_mem_stats();

      $processor_speed = $info['mhz'] * 1000000;
      $multiplier = $info['nodes'] * $info['cores'];
      $usage0 = $cpu['0']['kernel'] + $cpu['0']['user'];
      $usage1 = $cpu['1']['kernel'] + $cpu['1']['user'];
      $percentage = ($usage1 - $usage0) / ($processor_speed * $multiplier) * 100;
      echo "CPU Percentage = " . $percentage . "<br><br>";
      ?>

      <div class="row">
        <div class="col-md-3">
          <div class="card ">
            <div class="card-header ">
              <h5 class="card-title">CPU Statistics</h5>
              <p class="card-category">Lastest Usage</p>
            </div>
            <div class="card-body ">
              <canvas id="chartDonut1" class="ct-chart ct-perfect-fourth" width="456" height="300"></canvas>
            </div>
            <div class="card-footer ">
              <div class="legend">
                <i class="fa fa-circle text-info"></i> Open
              </div>
              <hr>
              <div class="stats">
                <i class="fa fa-calendar"></i> CPU Percentage
              </div>
            </div>
          </div>
        </div>
      </div>


      </div> <!-- end card body -->
      <div class="card-footer text-right">
        <input type="submit" class="btn btn-danger" name="action" value="Change" >
      </div>
    </form>
  </div> <!-- end card -->
</div> <!-- end content -->

<?php
require('../footer.php');
?>
