<?php
	//CR Election portal
	require_once("config.php");
	session_start();
	function get_candidates() {
		global $DB;
		$query = mysqli_prepare($DB, "SELECT id, name FROM `candidates` ORDER BY name");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $id, $name);
		mysqli_stmt_store_result($query);
		$results = array();
		while(mysqli_stmt_fetch($query)){
			$results[$id] = $name;
		}
		return $results;
	}

	$htmlOutput = '';

	if ( allowed() ) {
		if (isset($is_stv) && $is_stv) {
			$htmlOutput .= '';
			$candidates = get_candidates();
			//$candidates = array_merge($candidates);
			ob_start();
			?>
			<script src="<?php echo $base_url; ?>bower_components/jquery/dist/jquery.min.js"></script>
			<script src="<?php echo $base_url; ?>bower_components/Sortable/Sortable.min.js"></script>
			<div class="clearfix text-center">
				<div class="stv-half">
					<h3>Select your <span class="vote-count-ordinal">1st</span> preference</h3>
					<ul class="stv-list">
						<?php foreach ($candidates as $candidate_id => $candidate_name): ?>
							<li class="clearfix listed-candidate candidate<?php echo $candidate_id; ?>">
								<?php echo $candidate_name; ?>
								<button
									class="select-candidate-btn"
									data-id="<?php echo $candidate_id; ?>"
									data-name="<?php echo $candidate_name; ?>">➡</button>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="stv-half">
					<h3>Your preference</h3>
					<ul class="stv-list preference-order">
					</ul>
					<form action='vote.php' method='post' class='vote' onsubmit='return checkVotes(this)'>
						<input type="hidden" name="candidates_string" class="final-preference-order">
						<input type="submit" class="btn" value="Submit">
					</form>
				</div>
			</div>
			<script>
				function ordinalize(num) {
			        if ( [11,12,13].indexOf(num % 100) === -1 ){
			            switch (num % 10) {
			            // Handle 1st, 2nd, 3rd
			                case 1:  return num + 'st';
			                case 2:  return num + 'nd';
			                case 3:  return num + 'rd';
			            }
			        }
			        return num + 'th';
			    }
				var voteUp = function () {
					var $voteCounter = $('.vote-count-ordinal');
					var count = parseInt($voteCounter.text());
					$voteCounter.text(ordinalize(count + 1));
				};
				var voteDown = function () {
					var $voteCounter = $('.vote-count-ordinal');
					var count = parseInt($voteCounter.text());
					$voteCounter.text(ordinalize(count - 1));
				};
				var checkVotes = function (form) {
					form.candidates_string.value = Array.from(
						$('.preference-order')
							.children()
							.map(function (a, b) {
								return $(this).data('id');
							})
					).join(' ');
				};
				$(function () {
					var remove = function () {
						var $this = $(this);
						var id = $this.data('id');
						$('.listed-candidate.candidate'+id).show();
						$this.closest('.selected-candidate').remove();
						voteDown();
					};
					var getSelectedCandidateLI = function (name, id) {
						return $('<li class="selected-candidate clearfix"></li>')
							.data('id', id)
							.html('⋮ ⋮ &nbsp;&nbsp;&nbsp;' + name)
							.append( $('<button>').text('✖').click(remove).data('id', id ) );
					};
					var $preferenceOrderList = $('.preference-order');
					Sortable.create($preferenceOrderList[0], {
						animation: 200
					});
					$('.select-candidate-btn').on('click', function () {
						var $this = $(this);
						$this.closest('.listed-candidate').hide();
						$preferenceOrderList.append(
							getSelectedCandidateLI($this.data('name'), $this.data('id'))
						);
						voteUp();
					});
				});
			</script>
			<?php
			$htmlOutput .= ob_get_contents();
			ob_end_clean();
		}
		else {
			if ( (empty($multiple_votes) || $max_votes <= 1) ) {
				// Check and account for multiple votes
				$input_type = 'radio';
				$input_req = 'required';
			}
			else {
				$htmlOutput .= "
				<script>
					function checkVotes(form) {
						var allowed = ".$max_votes.";
						var n_allowed = ".$max_n_votes.";
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
			if ( $negative_votes ) {
				// Check and account for negative votes
				if ( $max_n_votes <= 1) {
					$n_input_type = 'radio';
				}
				else {
					$n_input_type = 'checkbox';
				}
			}
			$htmlOutput .= "<form action='vote.php' method='post' class='vote' onsubmit='return checkVotes(this)'>";
			$candidates = get_candidates();
			if ( count($candidates) ) {
				if ( $negative_votes ) {
					$htmlOutput .= '<table><!--tr><th>positive</th><th>negative</th><th>candidate</th></tr-->';
				}
				foreach ( $candidates as $id => $name ){
					if ( $negative_votes ) {
						$htmlOutput .= "<tr><td class='pos'><input type='$input_type' name='candidate_id[]' value='$id' $input_req></td>
							<td class='neg'><input type='$n_input_type' name='n_candidate_id[]' value='$id'></td>
							<td class='name'>$name</td></tr>";
					}
					else {
						$htmlOutput .= "<label><input type='$input_type' name='candidate_id[]' value='$id' $input_req>$name</label><br>";
					}
				}
				$htmlOutput .= '</table>';
				$htmlOutput .= "<input class='btn' type='submit' value='Vote'></form>";
			}
			else {
				$htmlOutput .= "No Candidates in the list.";
			}
		}
	}
	else {
		if ( isset( $_SESSION["done_voting"] ) && $_SESSION["done_voting"] ) {
			$htmlOutput .= "Your response has been recorded.<br><a class='btn' href=''>Refresh</a>";
			$htmlOutput .= "<script>document.body.onload=function(){setTimeout(function(){window.location=''}, 3000)}</script>";
			unset($_SESSION["done_voting"]);
		}
		else {
			$htmlOutput .= "<strong>Access denied.</strong> Please ask the administrator to allow you to vote. <br><a class='btn' href=''>Reload</a>";
			$htmlOutput .= "<script>document.body.onload=function(){setTimeout(function(){window.location=''}, 1000)}</script>";
		}
	}

	include('template.php');
