(function ($) {
  'use strict';

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
	 *
	 * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
	 *
	 * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  $(window).load(function () {
    var franceMap = $('#canvas_france');
    var francePath = franceMap.children('svg').children('#complete_map').children('path');
    var deptUrl = franceMap.data('wpfftt-dept-url');
    francePath.each(function () {
      var dept_id = $(this).attr('id');
      //var coords = document.getElementById(dept_id).getBBox();
      //$('#canvas_france #texxt_anchor_map').append('<text fill="white" x="' + coords.x +'" y="' + coords.y +'">' + dept_id +'</text>');
      $(this).click(function () {
        if(dept_id === '2a' || dept_id == '2b'){
          dept_id = 99;
        }
        window.location = deptUrl + this.id;
        console.log(dept_id);
      });
    });
    $('#wpfftt-list-dept a').mouseenter(
      function() {
        var curId = $(this).data('dept');
        franceMap.children('svg').children('#complete_map').children('path#'+ curId).css({ fill: "rgba(0,0,0, .6)" });
      }).mouseleave( function() {
      var curId = $(this).data('dept');
      franceMap.children('svg').children('#complete_map').children('path#'+ curId).attr('style','');
      }
    );




    var field_wpfftt_licence = $('#licence_number');
    var field_wpfftt_club = $('#club_number');
    var field_wpfftt_licence_btn = $('#submit_licence_number');
    var field_wpfftt_club_btn = $('#submit_club_number');


    field_wpfftt_licence_btn.on('click', function(e){
      e.preventDefault();
      if(field_wpfftt_licence.val() !== '') {
        document.location.href = $(this).data('wpfftt-redir') + '/' + field_wpfftt_licence.val();
      }
    });
    field_wpfftt_club_btn.on('click', function(e){
      e.preventDefault();
      if(field_wpfftt_club.val() !== '') {
        document.location.href = $(this).data('wpfftt-redir') + '/' + field_wpfftt_club.val();
      }
    });

    $(document).on('click', '.wpfftt-close', function(e){
      e.preventDefault();
      $('.wpfftt-modal-backdrop').remove();
      $('body').removeClass('wpfftt-modal-open');
      $(this).closest( ".wpfftt-modal" ).removeClass('wpfftt-show').css('display', 'none');
      //$('#load_' + rencontre_id).addClass('wpfftt-show').css('display', 'block');
    });


  });

  //$('.joueur_stats .ct-horizontal').attr('style','height:auto');

  if( 'wpfftt-' === wpffttajaxmatch.wp_fftt_prefix) {
  $(document).on( 'click', '.wpfftt-loader', function( event ) {
    var rencontre_id = $(this).data('wpfftt-collapse');
    var rencontre_loader = $('#load_' + rencontre_id);
    $('#loading_wpfftt_' + rencontre_id).show();
    $.ajax({
      url: wpffttajaxmatch.ajaxurl,
      type: 'get',
      data: {
        action: 'wpfftt_ajax_detail_party',
        wpajx_fftt_nonce : wpffttajaxmatch.wp_fftt_nonce,
        wpajx_fftt_division: wpffttajaxmatch.wp_fftt_division,
        wpajx_fftt_poule: wpffttajaxmatch.wp_fftt_poule,
        wpajx_fftt_rencontre: rencontre_id,
        wpajx_fftt_lien: $(this).data('wpfftt-lien')
      },
      success: function( html ) {
        $('body').append('<div class="wpfftt-modal-backdrop wpfftt-fade wpfftt-show"></div>');
        $('body').addClass('wpfftt-modal-open');
        $('#load_' + rencontre_id).addClass('wpfftt-show').css('display', 'block');
        rencontre_loader.html( html );
        $('#loading_wpfftt_' + rencontre_id).hide();
      },
      error: function(){
        console.log(errorThrown); // error
        $('#loading_wpfftt_' + rencontre_id).hide();
      }
    })
  });
}


})(jQuery);
