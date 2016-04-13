<?php
	$auth_required = true;
	include('../config.php');

	if (!$is_stv) {

		$total_votes = 0;
		$total_n_votes = 0;
		$htmlOutput = '<table class="result"><tr><th>Candidate Name</th><th>Votes</th></tr>';

		$query = mysqli_prepare($DB, "SELECT name, votes, n_votes FROM `candidates` ORDER BY votes DESC, name ASC");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $name, $votes, $n_votes);
		mysqli_stmt_store_result($query);
		while(mysqli_stmt_fetch($query)){
			$htmlOutput .= '<tr><td>'.$name.'</td><td>';
			if ( $negative_votes ) {
				$htmlOutput .= '<span class="pos">+'.$votes.'</span> <span class="neg">-'.$n_votes.'</span>';
			}
			else {
				$htmlOutput .= $votes;
			}
			$htmlOutput .= '</td></tr>';
			$total_votes += $votes;
			$total_n_votes += $n_votes;
		}

		$htmlOutput .= '<tr class="total"><td>Total</td><td>';
		if ( $negative_votes ) {
			$htmlOutput .= '<span class="pos">+'.$total_votes.'</span> <span class="neg">-'.$total_n_votes.'</span>';
		}
		else {
			$htmlOutput .= $total_votes;
		}
		$htmlOutput .= '</td></tr>';
		$htmlOutput .= '</table>';

		$htmlOutput .= "<br><br><br><br><a class='btn btn-green' href='".$base_url."admin/'>Go to Allow Voting</a>";

		include("../template.php");
	} else {
		if (isset($_GET['type']) && $_GET['type'] == 'text') {
			header('Content-Type: text');
			$query = mysqli_query($DB, "SELECT vote_string from votes WHERE vote_string IS NOT NULL");

			while ($result = $query->fetch_assoc()) {
				echo $result['vote_string'] . "\n";
			}
		} else {
			echo 'STV is being followed. To view the plaintext dump, visit <a href="?type=text">results.php?type=text</a>';
		}
	}
