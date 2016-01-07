<?php

$nb_test = 5000;

$jets = array(
	array('proba' => 37, 'name' => 'Flanc rose'),
	array('proba' => 28, 'name' => 'Flanc noir'),
	array('proba' => 25, 'name' => 'Tournedos'),
	array('proba' => 7, 'name' => 'Trotteur'),
	array('proba' => 2, 'name' => 'Groin-groin'),
	array('proba' => 1, 'name' => 'Bajoue')
);

$combinaisions = array(
	'Flanc rose Flanc rose' => 1,
	'Flanc noir Flanc rose' => 0,
	'Tournedos Flanc rose' => 5,
	'Trotteur Flanc rose' => 5,
	'Groin-groin Flanc rose' => 10,
	'Bajoue Flanc rose' => 15,
	'Flanc rose Flanc noir' => 0,
	'Flanc noir Flanc noir' => 1,
	'Tournedos Flanc noir' => 5,
	'Trotteur Flanc noir' => 5,
	'Groin-groin Flanc noir' => 10,
	'Bajoue Flanc noir' => 15,
	'Flanc rose Tournedos' => 5,
	'Flanc noir Tournedos' => 5,
	'Tournedos Tournedos' => 20,
	'Trotteur Tournedos' => 10,
	'Groin-groin Tournedos' => 15,
	'Bajoue Tournedos' => 20,
	'Flanc rose Trotteur' => 5,
	'Flanc noir Trotteur' => 5,
	'Tournedos Trotteur' => 10,
	'Trotteur Trotteur' => 20,
	'Groin-groin Trotteur' => 15,
	'Bajoue Trotteur' => 20,
	'Flanc rose Groin-groin' => 10,
	'Flanc noir Groin-groin' => 10,
	'Tournedos Groin-groin' => 15,
	'Trotteur Groin-groin' => 15,
	'Groin-groin Groin-groin' => 40,
	'Bajoue Groin-groin' => 25,
	'Flanc rose Bajoue' => 15,
	'Flanc noir Bajoue' => 15,
	'Tournedos Bajoue' => 20,
	'Trotteur Bajoue' => 20,
	'Groin-groin Bajoue' => 25,
	'Bajoue Bajoue' => 60
);



function double_jet() {
	global $combinaisions;
	$jet1 = jet();
	$jet2 = jet();
	$combinaison_key = $jet1['name'].' '.$jet2['name'];
	log_cochon($combinaison_key."<br/>"); 
	return $combinaisions[$combinaison_key];
}

function jet() {
	global $jets;
	$rand = rand(0,100);
	$somme_proba = 0;
	foreach($jets as &$jet) {
		$somme_proba += $jet['proba'];
		if($rand <= $somme_proba) return $jet;
	}
}

function tour($seuil=20, $total=0) {
	$point = double_jet();
	if($point==0) {
		log_cochon('<strong>0</strong><hr/>');
		return 0;
	} else {
		$total_current = $point + $total;
		if($total_current >= $seuil) {
			log_cochon('<strong>'.$total_current.'</strong><hr/>');
			return $total_current; 
		} else {
			return tour($seuil, $total_current);
		}
	}
}

function game($seuil) {
	$needed_point = 100;
	$total_point = 0;
	$nb_tour = 0;
	while($total_point < $needed_point) {
		if($needed_point - $total_point < $seuil) {
			$seuil = $needed_point - $total_point;
		}
		$total_point += tour($seuil);
		$nb_tour++;
	}
	return $nb_tour;
}

function log_cochon($log) {
	// echo $log;
}



?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Seuil');
        data.addColumn('number', 'Nombre de tour');
        data.addRows([
		<?php
		for ($i = 1; $i <= 80; $i++) {
			$total_test = 0;
			for ($y = 1; $y <= $nb_test; $y++) {
				$total_test += game($i);
			}
			$moyenne = $total_test/$nb_test;
			echo '['.$i.', '.$moyenne.'],';
		}
		?>
        ]);

        var options = {
          hAxis: {title: 'Seuil', gridlines: {count:40}, },
          vAxis: {title: 'Nombre de tour'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
	<h1>Nombre de tour nécessaire par seuil</h1>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
	<h1>Détails des parties</h1>
  </body>
</html>

