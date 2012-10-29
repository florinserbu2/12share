<?php 
class Fd_Twitter_Form_Decorator_NumberSlider extends Zend_Form_Decorator_Abstract{
    public function render($content)
    {
        $element = $this->getElement();
       
        if (null === ($view = $element->getView())) {
            return $content;
        }
     
        $base_name = $element->getName();
        
		$rand = rand(1,199);
		
        $rand_name = $base_name.'_'.$rand;
		$input1 = @$_POST[$base_name][1] ? @$_POST[$base_name][1] : @$_GET[$base_name][1];
		$input0 = @$_POST[$base_name][0] ? @$_POST[$base_name][0] : @$_GET[$base_name][0];
		
        if(@$element->is_range){
        	$max_val = $input1 ? $input1 : $element->max_val; 
        	$min_val = $input0 ? $input0 : $element->min_val;
 
			$html =  ' <input placeholder="'.Fd_Language_Labels::get("Min").'" value="'.$min_val.'" type="text" style="width:50px" name="'.$base_name.'[]" id="'.$base_name.'-min" title="'.Fd_Language_Labels::get("Minimum value").'"/> <span style="font-size:15px">'.(string)@$element->measure_unit.' </span>';
			$html .=  ' <input placeholder="'.Fd_Language_Labels::get("Max").'" value="'.$max_val.'" type="text" style="width:50px" name="'.$base_name.'[]" id="'.$base_name.'-max" title="'.Fd_Language_Labels::get("Maximum value").'"/> <span style="font-size:15px">'.(string)@$element->measure_unit.' </span>';
		}else{
			$html =  ' <input value="'.$element->getValue().'"  type="text" style="width:50px" name="'.$base_name.'" id="'.$base_name.'"    /> <span style="font-size:15px">'.(string)@$element->measure_unit.' </span>';
		}
		//description
		$desc = $element->getDesc()->desc;
		$desc_title = $element->getDesc()->title;
		if($desc){
			$html .= "<i id='{$base_name}-desc-icon' class='icon icon-question-sign' style='margin: -2px 0 0 10px'></i>";
			$html .= "<script type='text/javascript'>";
			$html .= "$(window).ready(function(){ ";
			$html .= "$('#{$base_name}-desc-icon').popover({trigger: 'hover', content: '{$desc}', title: '{$desc_title}'});";
			$html .= "});</script>";
		}
		$html .= ' <div id="'.$rand_name.'-slider" style="width:220px;margin-bottom:5px"></div>';
		
		$html .= " <script type='text/javascript'>";
		$html .= " $('#{$rand_name}-slider').slider({";
		if(@$element->is_range){
	        $html .= ' range: true,';
		}
		if($default = $element->getValue()){
			$html .= ' value:'.$default.',';
		}
		if(@$max = $element->max_val){
	        $html .= ' max:'.$max.',';
		}
		if(@$min = $element->min_val){
	        $html .= ' min:'.$min.',';
		}
		if(@$element->is_range){
			$val = new Fd_Validate_Range();
			if($val->isValid(array($min_val, $max_val))){
	        	$html .= ' values:['.$min_val.', '.$max_val.'],';
			}else{
				$html .= ' values:['.$element->min_val.', '.$element->max_val.'],';
			}
	   		$html .= ' slide: function( event, ui ) {$( "#'.$base_name.'-min" ).val(ui.values[0] );$( "#'.$base_name.'-max" ).val(ui.values[1] );},';
		}else{
	   		$html .= ' slide: function( event, ui ) {$( "#'.$base_name.'" ).val(ui.value );},';	
		}
		
   		$html .= ' });';
   		$html .= ' $( "#'.$base_name.'" ).val('.$default.');';
		
   		$html .= ' </script>';
	
        
        $placement = $this->getPlacement();
        $separator = $this->getSeparator();
        switch ($placement) {
            case 'APPEND':
                return $content . $separator . $html;
            case 'PREPEND':
                return $html . $separator . $content;
            case null:
            default:
                return $html;
        }
    }
}
?>