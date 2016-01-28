<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';



$request_method = '_'.$_SERVER['REQUEST_METHOD'];
$request_method = $$request_method;

if(isset($request_method['module']) && $request_method['module'] != ''){	
	$module = $request_method['module'];
}


/**
 * GET ALL POSSIBLE PIDS
 */
$macros_pids         = get_pids('fabui/python/gmacro.py');
$create_pids         = get_pids('fabui/python/gpusher_fast.py');

kill_process(array_merge($macros_pids,$create_pids));

$selftest_pids       = get_pids('python/self_test.py');
$bed_cal_pids        = get_pids('python/manual_bed_lev.py');
$rscan_pids          = get_pids('/fabui/python/r_scan.py');
$sscan_pids          = get_pids('/fabui/python/s_scan.py');
$pscan_pids          = get_pids('/fabui/python/p_scan.py');
$triangulation_pids  = get_pids('/fabui/python/triangulation.py');
$join_pids           = get_pids('/fabui/python/join.py');
$slic3rwrapper_pids  = get_pids('/fabui/python/slic3r_wrapper.py');
$meshlabwrapper_pids = get_pids('/fabui/python/meshlab_wrapper.py');
$meshlabserver_pids  = get_pids('meshlabserver');
$xvfb_pids           = get_pids('xvfb-run');
$slic3r_pids         = get_pids('/fabui/slic3r/slic3r');

$all_pids = array_merge($selftest_pids, $bed_cal_pids, $rscan_pids, $sscan_pids, $pscan_pids, $triangulation_pids, $join_pids);
$all_pids = array_merge($all_pids, $slic3rwrapper_pids, $meshlabwrapper_pids, $meshlabserver_pids, $xvfb_pids,$slic3r_pids  );

//kill all pids
kill_process($all_pids);
$end = time();

//clean up memory
shell_exec('sudo sh -c "echo 1 >/proc/sys/vm/drop_caches"'); 
shell_exec('sudo sh -c "echo 2 >/proc/sys/vm/drop_caches"'); 
shell_exec('sudo sh -c "echo 3 >/proc/sys/vm/drop_caches"');



$_command = 'sudo python '.PYTHON_PATH.'force_reset.py';
shell_exec($_command);
sleep(1);

include '/var/www/fabui/script/boot.php';
echo json_encode($all_pids);



?>