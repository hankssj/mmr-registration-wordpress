<?php
/**
 * Display the entry loop when using a table template
 *
 * @package GravityView
 * @subpackage GravityView/templates
 *
 * @global GravityView_View $this
 */
?>
	<tbody>
		<?php

		do_action('gravityview_table_body_before', $this );

		if( 0 === $this->getTotalEntries() ) {

			$directory_table_columns = $this->getFields('directory_table-columns');
			?>
			<tr>
				<?php do_action('gravityview_table_tr_before', $this ); ?>
				<td colspan="<?php echo $directory_table_columns ? sizeof( $directory_table_columns ) : ''; ?>" class="gv-no-results">
					<?php echo gv_no_results(); ?>
				</td>
				<?php do_action('gravityview_table_tr_after', $this ); ?>
			</tr>
		<?php
		} else {
			
			$i=count($this->getEntries());
			//echo $i;
			foreach( $this->getEntries() as $entry ) :
				
				$uid=get_current_user_id();
				$this->setCurrentEntry( $entry );

				// Add `alt` class to alternate rows
				$alt = empty( $alt ) ? 'alt' : '';

				/**
				 * @filter `gravityview_entry_class` Modify the class applied to the entry row
				 * @param string $alt Existing class. Default: if odd row, `alt`, otherwise empty string.
				 * @param array $entry Current entry being displayed
				 * @param GravityView_View $this Current GravityView_View object
				 */
				$class = apply_filters( 'gravityview_entry_class', $alt, $entry, $this );
				if($uid==$entry['created_by']){
		?>
				<tr<?php echo ' class="'.esc_attr( $class ).'"'; ?>>
		<?php
					do_action('gravityview_table_cells_before', $this );
					
					$this->renderZone( 'columns', array(
						'markup' => '<td id="{{ field_id }}" class="{{class}}">{{value}}</td>',
						'hide_empty' => false, // Always show <td>
					));
				

					do_action('gravityview_table_cells_after', $this );
		?>
				</tr>
			<?php
				} else{ 
				$directory_table_columns = $this->getFields('directory_table-columns');
				if($i==1){
				?>
				<tr>
				<?php do_action('gravityview_table_tr_before', $this ); ?>
				<td colspan="<?php echo $directory_table_columns ? sizeof( $directory_table_columns ) : ''; ?>" class="gv-no-results">
					<?php echo gv_no_results(); ?>
				</td>
				<?php do_action('gravityview_table_tr_after', $this ); ?>
			</tr>
			<?php
				}
				}
				$i++;
			endforeach;

		}

		do_action('gravityview_table_body_after', $this );
	?>
	</tbody>
