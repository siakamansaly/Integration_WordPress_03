jQuery(document).ready(function( $ ){

jQuery("span#es_sdb").text(function(index, text) { 
         return text.replace('de ', ''); 
});
jQuery("span#es_sdb").text(function(index, text) { 
        return text.replace('bain', 'salle de bain'); 
});
jQuery("span#es_lits").text(function(index, text) { 
        return text.replace('lit', 'chambre'); 
});
});