'use strict'
$("#gtuFormID").ready(function(){

	$("#gtuFormID :input").change(function(e){
		var appName = "gtu";
		var s = e.target;
		var jqTarget = $("#gtuFormID :input[name='"+s.name+"']").parent();

		var writeConfig = function(key, value) {
            //jqTarget.removeClass("gtu_saved");
            jqTarget.addClass("gtu_changed");
            OC.AppConfig.postCall('setValue',{app:appName,key:key,value:value}, function() {
            	jqTarget.removeClass("gtu_changed");
            	jqTarget.addClass("gtu_saved");
            	jqTarget.removeClass("gtu_saved",2000);
            });
        }
        writeConfig(s.name, s.value);
    });
});