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
				hideFirst=true;
			}
		}
		var optionsListe = {
			valueNames: ['titre-item'],
			page: page, 
			pagination: true
		};
	
		var listePosts = new List('archive-avec-pagination', optionsListe);

		//cacher le 1er article de loop (c'est le même que top-article) mais uniquement sur la première page du blog
		maybeHideFirst();
		bindScroll(); // lier les écouteurs au premier affichage

		//lier les écouteurs à chaque fois que la liste est mise à jour + attendre un peu pour que les liens de navigation soient reconstruits
		listePosts.on('updated',function(e) {
			setTimeout(bindScroll,1000);
		})

		//Au clic sur un élément de pagination, smooth scroll en haut de la liste 
		function bindScroll() {
			$('.pagination li').click(function(e) {
				console.log('on a demandé la page ',$(this).find('a').html());
				if($(this).find('a').html()==1) { //on a redemandé la 1ere page
					maybeHideFirst();
				}
				$("html, body").animate({
					scrollTop: $('#archive-avec-pagination').offset().top - 300
					}, 500);
			});
		}

		function maybeHideFirst() {
			if(hideFirst) {
				$(archive).find('.loop li:first-of-type').hide();
			}
		}

		

	}); //fin document ready
})( jQuery );

