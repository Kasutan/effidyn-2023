(function($) {

	$( document ).ready(function() {
		/*--------------------------------------------------------------
		# Pagination pour les archives 
		--------------------------------------------------------------*/
		var width=$(window).width();
		var archive=$('#archive-avec-pagination');
		var page=6;
		var hideFirst=false;
		if(width < 1024 ) {
			page=$(archive).attr('data-pag-mobile');
		} else {
			page=$(archive).attr('data-pag-desktop');
			if($('body').hasClass('blog')) {
				//On supprime tout simplement le premier article de la loop du blog avant d'initialiser list.js
				$(archive).find('.loop li:first-of-type').remove();
			}
		}
		var optionsListe = {
			valueNames: ['titre-item'],
			page: page, 
			pagination: true
		};
	
		var listePosts = new List('archive-avec-pagination', optionsListe);

	
		bindScroll(); // lier les écouteurs au premier affichage

		//lier les écouteurs à chaque fois que la liste est mise à jour + attendre un peu pour que les liens de navigation soient reconstruits
		listePosts.on('updated',function(e) {
			setTimeout(bindScroll,1000);
		})

		//Au clic sur un élément de pagination, smooth scroll en haut de la liste 
		function bindScroll() {
			$('.pagination li').click(function(e) {
				
				$("html, body").animate({
					scrollTop: $('#archive-avec-pagination').offset().top - 300
					}, 500);
			});
		}


		

	}); //fin document ready
})( jQuery );

