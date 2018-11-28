
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

function exists(el) {
  return (el == null) || (el == undefined) ? false : true;
}

function get_all_btns_of_input(input_id) {
  return $(`[data-input='${input_id}']`);
}

function toggle_select_btn_class(id, btn) {
  let btns = get_all_btns_of_input(id);
  $.each(btns, function (index, value) {
    if($(value).is(btn)) {
      $(value).addClass('selected');
    } else if($(value).hasClass('selected')) $(value).removeClass('selected');
  });
}

function set_select_btn_value(el) {
  if(exists(el.data("input")) && exists(el.data("value"))){
    console.log($(el.data("input")));
    $(el.data("input")).val(el.data("value"));
    toggle_select_btn_class(el.data("input"), el);
  } else if(!exists(el.data("input"))) {
    console.log('!E: el.data("input"): ' + el.data("input"));
  } else {
    console.log('!E: el.data("value"): ' + el.data("value"));
  }
}


function select_btn_input(id) {
  let btns = get_all_btns_of_input(id);
  for(b in btns)
    if(b.hasClass('selected')) set_select_btn_value(b);
}

$(document).ready(function() {
  $(".select-btn").on("click", function(e) {
    set_select_btn_value($(this));
  });
});
