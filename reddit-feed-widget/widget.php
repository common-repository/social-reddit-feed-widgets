<?php
class reddit_feed_widget extends WP_widget{
    //constructor (sets basic elements for the widget)
    public function __construct(){
        parent::__construct(
            'srfw_widget', // Base ID
            'Reddit Feed Widgets', // Name
            array( 'description' => 'Reddit Feed Widgets') // Args
        );
        require_once(SRFW_PLUGIN_DIR . 'include/srfw-layout.php');
     }
    //handles front end display
    public function widget($args,$instance ){
        echo $args['before_widget'];
        $title =  $instance['title'];
        $srfw_transient_val =3600; 
        if(isset($instance['srfw_transient_value'])){
            $srfw_transient_val =  $instance['srfw_transient_value'];
        }
        $limit = 25;
        if(!empty($instance['limit'])){
                $limit = $instance['limit'];
        }
        srfw_fetch_data($title,$limit,$srfw_transient_val);
	   echo $args['after_widget'];
	}
    //handles back end widget display
    public function form($instance){
        $title ='';
        $limit = 10;
        	if(isset($instance['title']) || !empty($instance['title'])){
            $title = $instance['title'];
        	}
        	if(isset($instance['limit']) || !empty($instance['limit'])){
            $limit = $instance['limit'];
        	}
        	if ( isset( $instance[ 'srfw_transient_value' ] ) ) {
            $srfw_transient_val = $instance['srfw_transient_value'];
          }
		else {
          $srfw_transient_val = '24 * HOUR_IN_SECONDS';
          }
        	echo '<div><label>Enter Reddit Username</label>&nbsp;&nbsp;&nbsp;</div>';
        	echo '<div><input type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title  . '" placeholder="Mandatory Field"/></div>';
        	echo '<div><label>Show Number of feeds</label></div>';
        	echo '<div><input  type="text" id="' . $this->get_field_id('limit') . '" name="' . $this->get_field_name('limit') . '" value="' .$limit  . '"/></div>';
     		echo  '<div><label>Update In</label></div>';
    		echo	'<div><select  id="'. $this->get_field_id('srfw_transient_value') . '" name="' . $this->get_field_name('srfw_transient_value') . '" value="' . $srfw_transient_val  . '">';
    		echo  '<option '.selected($srfw_transient_val,'3600').' value="3600">1 hour</option>';
    		echo '<option   '.selected($srfw_transient_val, '43200').' value="43200">12 HOUR</option>';
    		echo '<option  '.selected($srfw_transient_val, '86400').' value="86400">1 Day</option>';	
    		echo  '<option  '.selected($srfw_transient_val, '604800').' value="604800">7 Days</option>';
		echo  '<option '.selected($srfw_transient_val, '2592000').' value="2592000">4 weeks</option>';
    		echo '</select></div>';
    }
    //handles saving
    public function update($new_instance, $old_instance){
        // $instance = array();
		$instance = $old_instance;
     	$instance[ 'title' ] = (!empty($new_instance[ 'title' ])) ? strip_tags( $new_instance['title'] ) : '';
     	$instance['limit'] = (!empty($new_instance['limit'])) ? strip_tags($new_instance['limit']) : '';
     	$instance['srfw_transient_value'] = (!empty($new_instance['srfw_transient_value']))?strip_tags($new_instance['srfw_transient_value']):'';
     	$title = (!empty($old_instance['title'])) ? $old_instance['title']: '';
         	$pre = 'srfw_feed_';
        	$validity = (!empty($old_instance['srfw_transient_value'])) ? $old_instance['srfw_transient_value']:'';
        	$limit = (!empty($old_instance['limit'])) ? $old_instance['limit']:'';
         	$cache_name = $pre.$title.'_'.$limit.'_'.$validity;
        	$transient_val = (!empty($old_instance['srfw_transient_value'])) ? $old_instance['srfw_transient_value']:'';
         	if ( $new_instance['srfw_transient_value'] != $validity ) {
            	delete_transient($cache_name);
        	}
        	elseif($new_instance['limit'] != $old_instance['limit']){
            	delete_transient($cache_name);
        	}
        	elseif($new_instance['title'] != $old_instance['title']){
            	delete_transient($cache_name);
        	}
    		return $instance;
	}
   
}
$my_widget = new reddit_feed_widget;
