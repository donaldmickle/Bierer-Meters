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
EE.file_manager=EE.file_manager||{},EE.file_manager.sync_files=EE.file_manager.sync_files||{},EE.file_manager.sync_db=0,EE.file_manager.sync_running=0,EE.file_manager.sync_errors=[],EE.file_manager.resize_ids=[],EE.file_manager.sync_timeout_id=0,$(document).ready(function(){EE.file_manager.sync_listen()}),EE.file_manager.sync_listen=function(){$("form.settings input.btn").click(function(e){e.preventDefault(),EE.file_manager.sync_files=_.toArray(EE.file_manager.sync_files);var n=EE.file_manager.sync_id;EE.file_manager.update_progress(0),$("input.btn",this).prop("disabled",!0),$(".alert.inline").remove(),EE.file_manager.sync_timeout_id=setTimeout(function(){EE.file_manager.sync(n)},15)})},EE.file_manager.resize_ids=function(){var e=[];return $('input[name="sizes[]"]:checked').each(function(){e.push($(this).val())}),e},EE.file_manager.sync=function(e){if(EE.file_manager.sync_files.length<=0){if("y"==EE.file_manager.db_sync)return void clearTimeout(EE.file_manager.sync_timeout_id);EE.file_manager.db_sync="y"}var n=EE.file_manager.sync_files.splice(0,5);$.ajax({url:EE.file_manager.sync_endpoint,type:"POST",dataType:"json",data:{XID:EE.XID,upload_directory_id:e,sizes:EE.file_manager.sync_sizes,files:n,resize_ids:EE.file_manager.resize_ids(),db_sync:EE.file_manager.db_sync,errors:EE.file_manager.sync_errors},beforeSend:function(e,n){EE.file_manager.sync_running+=1},complete:function(n,i){EE.file_manager.sync_running-=1;var r=EE.file_manager.sync_file_count,a=EE.file_manager.sync_files.length,s=r-a;EE.file_manager.update_progress(Math.round(s/r*100)),EE.file_manager.sync(e),EE.file_manager.finish_sync(e)},success:function(e,n,i){if("success"!=e.message_type)if("undefined"!=typeof e.errors)for(var r in e.errors)EE.file_manager.sync_errors.push("<b>"+r+"</b>: "+e.errors[r]);else EE.file_manager.sync_errors.push("<b>Undefined errors</b>"),d}})},EE.file_manager.finish_sync=function(e){if(0==EE.file_manager.sync_running)if(0==EE.file_manager.sync_errors.length)window.location=EE.file_manager.sync_baseurl;else{var n=$("<input>",{type:"hidden",name:"errors",value:JSON.stringify(EE.file_manager.sync_errors)});$(".w-12 form.settings").append(n).submit()}},EE.file_manager.update_progress=function(e){var n=$(".progress-bar"),i=$(".progress",n);n.is(":not(:visible)")&&n.show(),i.css("width",e+"%")};