<?php
//CR Election portal
require_once("config.php");
session_start();
function get_candidates()
{
    global $DB;
    $query = mysqli_prepare($DB, "SELECT id, name FROM `candidates` ORDER BY name");
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $id, $name);
    mysqli_stmt_store_result($query);
    $results = array();
    while (mysqli_stmt_fetch($query)) {
        $results[$id] = $name;
    }
    return $results;
}

$htmlOutput = '';

if (allowed()) {
    if ((empty($multiple_votes) || $max_votes <= 1)) {
        // Check and account for multiple votes
        $input_type = 'radio';
        $input_req = 'required';
    } else {
        $htmlOutput .= "
			<script>
				function checkVotes(form) {
					var allowed = " . $max_votes . ";
					var n_allowed = " . $max_n_votes . ";
					var inputs = form.getElementsByTagName('input');
					var voted = 0, n_voted = 0;
					for ( i=0; i<inputs.length; i++ ) {
						if ( inputs[i].name == 'candidate_id[]' && inputs[i].checked )
							++voted;
						else if ( inputs[i].name == 'n_candidate_id[]' && inputs[i].checked )
							++n_voted;
					}
					if ( voted != allowed || n_voted > n_allowed) {
						if ( n_allowed == 0 ) {
							alert('Vote for exactly '+allowed+' candidates.');
						}
						else {
							alert('Please cast exactly '+allowed+' positive and maximum '+n_allowed+' negative votes.');
						}
						return false;
					}
					return true;
				}
			</script>
			";
        $input_type = 'checkbox';
        $input_req = '';
    }
    if ($negative_votes) {
        // Check and account for negative votes
        if ($max_n_votes <= 1) {
            $n_input_type = 'radio';
        } else {
            $n_input_type = 'checkbox';
        }
    }
    $htmlOutput .= "<form action='vote.php' method='post' class='vote' onsubmit='return checkVotes(this)'>";
    $candidates = get_candidates();
    if (count($candidates)) {
        if ($negative_votes) {
            $htmlOutput .= '<table><!--tr><th>positive</th><th>negative</th><th>candidate</th></tr-->';
        }
        foreach ($candidates as $id => $name) {
            if ($negative_votes) {
                $htmlOutput .= "<tr><td class='pos'><input type='$input_type' name='candidate_id[]' value='$id' $input_req></td>
						<td class='neg'><input type='$n_input_type' name='n_candidate_id[]' value='$id'></td>
						<td class='name'>$name</td></tr>";
            } else {
                $htmlOutput .= "<label><input type='$input_type' name='candidate_id[]' value='$id' $input_req>$name</label><br>";
            }
        }
        $htmlOutput .= '</table>';
        $htmlOutput .= "<input class='btn' type='submit' value='Vote'></form>";
    } else {
        $htmlOutput .= "No Candidates in the list.";
    }
} else {
    $htmlOutput .= "
<script>
document.body.onload=function(){
setInterval(function(){
$.get(\"check.php\", function(data, status){
        console.log(data);
        if(data==\"true\"){
            console.log(\"reload\");
            window.location.reload();
        }
    });
}, 1000);
}</script>";
    if (isset($_SESSION["done_voting"]) && $_SESSION["done_voting"]) {
        $htmlOutput .= "Your response has been recorded.<br><a class='btn' href=''>Refresh</a>";
        unset($_SESSION["done_voting"]);
    } else {
        $htmlOutput .= "<strong>Access denied.</strong> Please ask the administrator to allow you to vote. <br><a class='btn' href=''>Reload</a>";
    }
}
include('template.php');
