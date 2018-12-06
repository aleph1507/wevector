
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

function openNav(e) {
    if(document.getElementById("mySidenav").style.width != "250px"){
      document.getElementById("mySidenav").style.width = "250px";
      console.log('open: ' + e.target.innerHTML);
      console.log('e.target: ', e.target);
      document.getElementById('openNavSpan').innerHTML = '&times;';
    }
    else{
      document.getElementById("mySidenav").style.width = "0";
      console.log('close: ' + e.target.innerHTML);
      console.log('e.target: ', e.target);
      document.getElementById('openNavSpan').innerHTML = '<i class="fas fa-bars"></i>';
    }
}

/* Set the width of the side navigation to 0 */
function closeNav(e) {
    document.getElementById("mySidenav").style.width = "0";
    e.target.innerHTML = '<i class="fas fa-bars"></i>';
}

$(document).ready(function() {

  document.getElementById('openNavSpan').addEventListener("click", openNav);

  document.getElementById('closeNavBtn').addEventListener("click", closeNav);

  $(".select-btn").on("click", function(e) {
    set_select_btn_value($(this));
  });

  let modal = document.getElementById('modal');

  let modalTrigger = document.getElementById('modal-trigger');

  let modalSpan = document.getElementsByClassName('close')[0];

  let modalOverlay = document.getElementById('main-image-overlay');

  let mainImageContainer = document.getElementById('main-image-container');

  if(exists(modal) && exists(modalTrigger))
  {
    let modal_trigger_rect = modalTrigger.getBoundingClientRect();
    let modal_image_container_rect = mainImageContainer.getBoundingClientRect();
    modalOverlay.style.height = modal_trigger_rect.height + 'px';
    modalOverlay.style.width = modal_trigger_rect.width + 'px';
    console.log(modalOverlay.style.position);
    modalTrigger.addEventListener('mouseenter', function(e) {
      modalOverlay.classList.remove('d-none');
    });
    modalOverlay.addEventListener('mouseleave', function(e) {
      e.target.classList.add('d-none');
    });
    modalTrigger.onclick = modalOverlay.onclick = display_main_image_modal;

    function display_main_image_modal()
    {
      let modalContent = $(modalTrigger).data('content');
      console.log($(modalTrigger).data('content'));
      loadContent(modalContent);
      modal.style.display = 'block';
    }

    function closeModal()
    {
        modal.style.display = 'none';
    }

    window.onclick = e => {if(e.target == modal) closeModal()};
    modalSpan.onclick = e => closeModal();
  }



  let imgUpload = document.getElementById('file-upload');

  if(exists(imgUpload))
  {
    imgUpload.addEventListener("change", preview_image);
  }

  function preview_image(event)
  {
   var reader = new FileReader();
   reader.onload = function()
   {
    var output = document.getElementById('output_image');
    output.src = reader.result;
   }
   reader.readAsDataURL(event.target.files[0]);
   document.getElementById("mainFileName").innerHTML =
    event.target.value.split('\\').pop();
  }
});
