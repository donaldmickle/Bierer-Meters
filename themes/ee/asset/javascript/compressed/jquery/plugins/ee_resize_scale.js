/*!
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2016, EllisLab, Inc.
 * @license		https://expressionengine.com/license
 * @link		https://ellislab.com
 * @since		Version 2.0
 * @filesource
 */
!function(e){var i={resize_width:"#resize_width",resize_height:"#resize_height",submit_resize:"",cancel_resize:"",oversized_class:"oversized",default_height:0,default_width:0,resize_confirm:"",callback_resize:"",callback_submit:"",callback_cancel:""};e.fn.resize_scale=function(a){return this.each(function(){var t=e.extend({},i,a),l=e(this),s=e(t.resize_width,l),c=e(t.resize_height,l),h=e(t.submit_resize,l),r=e(t.cancel_resize,l);t.default_height=parseInt(t.default_height,10),t.default_width=parseInt(t.default_width,10),s.add(c).keyup(function(i){r.show();var a,l=e(this),h=l.attr("id"),d="resize_height"===h?s:c;a="resize_width"===h?t.default_height/t.default_width:t.default_width/t.default_height,d.val(Math.round(a*l.val())),s.val()>t.default_width||c.val()>t.default_height?(s.addClass(t.oversized_class),c.addClass(t.oversized_class)):(c.removeClass(t.oversized_class),s.removeClass(t.oversized_class)),"function"==typeof t.callback_resize&&t.callback_resize.call(this,{width:s.val(),height:c.val()})}),h.off("click","**").on("click",function(i){if(e("."+t.oversized_class).size()){var a=confirm(t.resize_confirm);0==a?i.preventDefault():"function"==typeof t.callback_submit?t.callback_submit.call(this):l.trigger("submit")}}),r.size()&&r.click(function(e){e.preventDefault(),s.val(t.default_width).removeClass(t.oversized_class),c.val(t.default_height).removeClass(t.oversized_class),"function"==typeof t.callback_cancel&&t.callback_cancel.call(this,{width:s.val(),height:c.val()}),r.hide()})})}}(jQuery);