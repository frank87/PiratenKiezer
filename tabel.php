<?php
	global $wpdb;

	function print_argument( $qid, $aid, $text, $yes, $no, $selected = -1 )
	{
		echo '<tr><td><a href="';
		echo add_query_arg( array(
						'pkw_action' => 'del',
						'pkw_qid' => $qid,
						'pkw_aid' => $aid 
					) );
		echo '">wis</a></td><td>', $text,'</td>';
		if ( $selected == 1 ) {
			echo '<td>XXX</td>';
		} else { 
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'next',
						   'pkw_aid' => $yes ) );
			echo '">...</a></td>';
		}
		if ( $selected == 0 ) {
			echo '<td>XXX</td>';
		} else { 
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'next',
						   'pkw_aid' => $no ) );
			echo '">...</a></td>';
		}
		echo '</tr>';
		
	}

	function chosen( $id, $register = false )
	{
		global $wpdb;
		$before = $wpdb->get_row( $wpdb->prepare (
					"SELECT 0 'selection'
					, id
					, text
					, on_yes
					, on_no
					, answer_id
	                                FROM wp_pkw_argument
					WHERE on_no = %d
					UNION
					SELECT 1
					, id
					, text
					, on_yes
					, on_no
					, answer_id
	                                FROM wp_pkw_argument
					WHERE on_yes = %d;", $id, $id ) );
		if ( null !== $before  ){
			chosen( $before->answer_id, $register );
			print_argument(
				$before->id,
				$before->answer_id,
				$before->text,
				$before->on_yes,
				$before->on_no,
				$before->selection
				      );
			if ( $register ) {
				if ( $before -> selection == 1 ) {
					$choice = 'yes';
				} else {
					$choice = 'no';
				}

				$sql = $wpdb->prepare ( 
					"update wp_pkw_argument
					 set  count_$choice = count_$choice + 1 
					 where id = %d;", $before->id );
				$wpdb->query( $sql );
			}
		}
	}

	$qtext = $wpdb->get_row( "SELECT text, start FROM wp_pkw_question where id = 1" );
	if ( null == $qtext ) {
		return false;
	}
	echo $qtext->text;
?>
<table border=1 >
<tr><th></th><th>Stelling</th><th>eens</th><th>oneens</th></tr>
<?php
	if ( isset( $_GET['pkw_action'] )  ) {
		$aid = $_GET['pkw_aid'];
		$action = $_GET['pkw_action'];
	} else {
		$aid = $qtext->start;
		$action = 'next';
	}

	chosen( $aid, $action == 'register' );

	$sql= $wpdb->prepare (
			"SELECT id
			, text
			, on_yes
			, on_no
			, answer_id
			FROM wp_pkw_argument
			WHERE answer_id = %d
			ORDER BY count_yes * count_no;", $aid );

	$i = 0;
	$next_question = $wpdb->get_row( $sql, OBJECT, $i );
	if ( $action == 'del' ) {
		$qid = $_GET['pkw_qid'];
		while (  $next_question !== null && $next_question->id !== $qid )
		{
			$i ++; # until last question
			$next_question = $wpdb->get_row( $sql, OBJECT, $i );
		}
		$next_question = $wpdb->get_row( $sql, OBJECT, $i + 1 );
	}

	if ( null !== $next_question  )
	{
		print_argument(
			$next_question->id,
			$next_question->answer_id,
			$next_question->text,
			$next_question->on_yes,
			$next_question->on_no );
	} else {
		$sql = $wpdb->prepare( "SELECT text
					FROM   wp_pkw_answer a
					,      wp_pkw_tree_node b
					where  b.id = %d
					and    a.id = b.answer_id;", $aid );
		$text = $wpdb->get_row( $sql );
		if ( $action == 'next' ) {
			echo '<tr><td>Kies:</td><td>'.$text->text.'</td>';
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'register',
						   'pkw_aid' => $aid ) );
			echo '">...</a></td>';
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'newarg',
						   'pkw_aid' => $aid ) );
			echo '">...</a></td>';
		} 
		if ( $action == 'register' ) {
			echo '<tr><td>Kies:</td><td>'.$text->text.'</td><td>XXX</td><td></td>';
		}
		if ( $action == 'newarg' ) {
			echo '<form action="newArg.php" method="post">';
			echo '<tr><td></td><td><input type="text" name="argument"</td>';
			echo '<td><input type="radio" name="yesno" value="yes"></td>';
			echo '<td><input type="radio" name="yesno" value="no"></td></tr>';
			echo '<tr><td></td><td>';
			echo '<select name="partij">';
			$sql = "select id, text from wp_pkw_answer";
			$i = 0;
			$party = $wpdb->get_row( $sql, OBJECT, $i );
			while ( null !== $party )
			{
				echo "<option value='$party->id'>$party->text</option>";
				$i++;
				$party = $wpdb->get_row( $sql, OBJECT, $i );
			}
			echo "</select>";
			echo "</td><td><input type='submit' value='voeg toe'></td><td></td>";
			echo '</form>';
		}
	
	}
	
?>
</table>
