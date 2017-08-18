/**
 * Created by patrice on 21/07/2017.
 */
jQuery(document).ready(function($) {

  tinymce.create('tinymce.plugins.wpfftt_plugin', {
    init : function(ed, url) {
      ed.addButton('wpfftt_button', {
        type: 'splitbutton',
        text: '',
        image: url + '/ping-pong.png',
        icon: false,
        menu: [
          {text: 'Club', value: '[fftt_club club_id="'+ wpfftt_plugin.club_id +'" show_map="0" show_link="1"]', onclick: function() {ed.insertContent(this.value());} },
          {text: 'Club Teams', value: '[fftt_teams club_id="'+ wpfftt_plugin.club_id +'" show_map_department="0"]',  onclick: function() {ed.insertContent(this.value());}},
          {text: 'Club Players', value: '[fftt_players_club club_id="'+ wpfftt_plugin.club_id +'"]',  onclick: function() {ed.insertContent(this.value());}},
          {text: 'Clubs of the department', value: '[fftt_clubs_departement department="'+ wpfftt_plugin.department +'"]',  onclick: function() {ed.insertContent(this.value());}},
          {text: 'Player', value: '[fftt_player licence="588400"]',  onclick: function() {ed.insertContent(this.value());}},
          {text: 'Rank', value: '[fftt_rank fftt_division="3797" fftt_poule=""]',  onclick: function() {ed.insertContent(this.value());}}
        ],

      });


    },
  });

  // Register our TinyMCE plugin
  // first parameter is the button ID1
  // second parameter must match the first parameter of the tinymce.create() function above
  tinymce.PluginManager.add('wpfftt_button', tinymce.plugins.wpfftt_plugin);
});