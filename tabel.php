<?php
	global $wpdb;

	function print_argument( $aid, $tid, $text, $yes, $no, $selected = -1 )
	{
		echo '<tr><td><a href="';
		echo add_query_arg( array(
						'pkw_action' => 'del',
						'pkw_aid' => $aid,
						'pkw_tid' => $tid 
					) );
		echo '">wis</a></td><td>', $text,'</td>';
		if ( $selected == 1 ) {
			echo '<td>XXX</td>';
		} else { 
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'next',
						   'pkw_tid' => $yes ) );
			echo '">...</a></td>';
		}
		if ( $selected == 0 ) {
			echo '<td>XXX</td>';
		} else { 
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'next',
						   'pkw_tid' => $no ) );
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
		$tid = $_GET['pkw_tid'];
		$action = $_GET['pkw_action'];
	} else {
		$tid = $qtext->start;
		$action = 'next';
	}

	chosen( $tid, $action == 'register' );

	if ( $action == 'next' || $action == 'del' ) {
		$sql= $wpdb->prepare (
				"SELECT id
				, text
				, on_yes
				, on_no
				, answer_id
				FROM wp_pkw_argument
				WHERE answer_id = %d
				ORDER BY count_yes * count_no desc;", $tid );

		$next_argument = $wpdb->get_results( $sql, OBJECT );
		$i = 0;
		if ( $action == 'del' ) {
			$aid = $_GET['pkw_aid'];
			while (  ( array_key_exists( $i, $next_argument ) ) && ( $next_argument[$i]->id !== $aid ) )
			{
				$i = $i + 1; # until last question
			}
			# next question
			$i++;
			$action = 'next';
		}
	} else {
		$i =10;
		$next_argument = array( 1 => 1 );
	}


	if ( array_key_exists( $i, $next_argument )  )
	{
		print_argument(
			$next_argument[$i]->id,
			$next_argument[$i]->answer_id,
			$next_argument[$i]->text,
			$next_argument[$i]->on_yes,
			$next_argument[$i]->on_no );
	} else {
		$sql = $wpdb->prepare( "SELECT a.id, text
					FROM   wp_pkw_answer a
					,      wp_pkw_tree_node b
					where  b.id = %d
					and    a.id = b.answer_id;", $tid );
		$text = $wpdb->get_row( $sql );
		if ( $action == 'next' ) {
			echo '<tr><td>Kies:</td><td>'.$text->text.'</td>';
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'register',
						   'pkw_tid' => $tid ) );
			echo '">...</a></td>';
			echo '<td><a href="';
			echo add_query_arg( array( 'pkw_action' => 'newarg',
						   'pkw_tid' => $tid ) );
			echo '">...</a></td>';
		} 
		if ( $action == 'register' ) {
			echo '<tr><td>Kies:</td><td>'.$text->text.'</td><td>XXX</td><td></td>';
		}
		if ( $action == 'newarg' ) {
			echo '<form method="post" action="';
			echo add_query_arg( array( 'pkw_action' => 'add_arg',
						   'pkw_tid' => $tid ) );
			echo '"><tr><td></td><td><input type="text" name="argument" required></td>';
			echo "<td><input type='submit' name='yes' value='...'></td>";
			echo "<td><input type='submit' name='no' value='...'></td></tr>";
			echo '<tr><td></td><td>';
			echo '<select name="partij" required><option name=""></option>';
			$sql = "select id, text from wp_pkw_answer";
			$i = 0;
			$party = $wpdb->get_row( $sql, OBJECT, $i );
			while ( null !== $party )
			{
				if ( $party->id !== $text->id ) { # don't repeat result
					echo "<option value='$party->id'>$party->text</option>";
				}
				$i++;
				$party = $wpdb->get_row( $sql, OBJECT, $i );
			}
			echo "</select>";
			echo "</td><td></td><td></td>";
			echo '</form>';
		}
		if ( $action == 'add_arg' ) {
			$new_ans = $_POST['partij'];
			$old_ans = $text->id;
			if ( array_key_exists( 'yes', $_POST ) ) {
				$yes = $new_ans;
				$no = $old_ans;
				$select = 1;
			}
			if ( array_key_exists( 'no', $_POST ) ) {
				$no = $new_ans;
				$yes = $old_ans;
				$select = 0;
			}
			$new_arg = $_POST['argument'];
			$wpdb->insert( 'wp_pkw_tree_node', array( 'answer_id' => $yes ));
			$on_yes = $wpdb->insert_id;
			$wpdb->insert( 'wp_pkw_tree_node', array( 'answer_id' => $no ));
			$on_no = $wpdb->insert_id;

			$wpdb->insert( 'wp_pkw_argument', array( 'answer_id' => $tid,
								 'on_yes' => $on_yes,
								 'on_no' => $on_no,
								 'text' => $new_arg ));

			print_argument( $wpdb->insert_id, $tid, $new_arg, $on_yes, $on_no, $select  );
			$text = $wpdb->get_var($wpdb->prepare('select text from wp_pkw_answer where id = %d',$new_ans));
			echo "<tr><td>Gekozen:</td><td>$text</td><td>XXX</td><td></td>";
		}
	
	}
	
?>
</table>
