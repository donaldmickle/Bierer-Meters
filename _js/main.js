
    function activateMobileMenu() {
                $("#menu").mmenu();
            }

	/******* Document Ready *********/
	 
	 
	 $(document).ready(function($) {
        $("#menu").mmenu({
            "extensions": [
                "pagedim-black"
            ]
        });

            if ($("#menu").length > 0) {
                activateMobileMenu();
            }
		
		});
		$(document).foundation();
	
			
	//end document ready
	
