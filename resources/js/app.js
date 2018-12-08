
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
    if(document.getElementById("mySidenav").style.width != "350px"){
      document.getElementById("mySidenav").style.width = "350px";
      document.getElementById('openNavSpan').innerHTML = '&times;';
    }
    else{
      closeNav(e);
    }
}

function renderOrders(orders, element)
{
  let order_html = ``;
  for(let i = 0; i<orders.data.data.length; i++)
  {
    let o = orders.data.data[i];
    order_html += `
      <a href="/orders/${o.id}" class="d-none d-md-block">
        <div class="d-flex mt-2 mb-2 order-row row">
          <div class="col-md-1">
            <img src="/images/${o.id}/thumb/sm/${o.file}"
            alt="image of ${o.name}" class="mr-3">
          </div>
            <div class="col-md-2 align-middle">
              <span>${o.name}</span>
            </div>
            <div class="col-md-2 align-middle">${o.id}</div>
            <div class="col-md-2 align-middle">Rushi</div>
            <div class="col-md-2 align-middle">${o.created_at}</div>
            <div class="col-md-2 align-middle">Pocinat</div>
            <div class="col-md-1 align-middle">Jok</div>
        </div>
      </a>
      <a href="/orders/${o.id}" class="d-block d-md-none">
        <div class="d-flex mt-2 mb-2 orders-sm row">
          <div class="col-3">
          <img src="/images/${o.id}/thumb/sm/${o.file}"
          alt="image of ${o.name}" class="mr-3">
          </div>
          <div class="col-9">
            <div class="row">
              <div class="col-6">
                <span>Name: </span> <span>${o.name}</span>
              </div>
              <div class="col-6">
                <span>ID: </span> <span>${o.id}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <span>Comments: </span> <span>Jok</span>
              </div>
              <div class="col-6">
                <span>Status: </span> <span>Pocinat</span>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <span>Type: </span> <span>Rushi</span>
              </div>
              <div class="col-6">
                <span>Sent on: </span> <span>${o.created_at}</span>
              </div>
            </div>
          </div>
        </div>
      </a>
    `;
  }
  element.innerHTML = order_html;
  //             <span>Sent on: </span> <span>{{$ao->created_at->format('d/m/Y')}}</span>

}

function createPaginatorBtn(page, query, current = '')
{
  return `<div class="paginator-btn ${current}" data-query="${query}" data-page="${page}">${page}</div>`;
}

function updatePaginator(paginator, current_page, last_page, query)
{
  console.log('paginator: ', paginator);
  console.log('current_page: ', current_page);
  console.log('last_page: ', last_page);
  if(last_page == 1)
  {
    paginator.style.display = 'none';
    return;
  }
  for(let i = 1; i<=last_page; i++)
    paginator.innerHTML += i == current_page ? createPaginatorBtn(i, query, 'current') : createPaginatorBtn(i, query);
}

function getPage(page, element, paginator, query)
{
  let url = '';
  if(query == 'active') url = '/orders/active?page=' + page;
  axios.get(url)
    .then(function(response) {
      console.log(response);
      updatePaginator(paginator, response.data.current_page, response.data.last_page, query);
      renderOrders(response, element);
    })
    .catch(function(error) {

    })
    .then(function() {
      //always executed
    });
}

function goToPage(e)
{
  console.log($(e).data("query"));
  console.log($(e).data("page"));
}

/* Set the width of the side navigation to 0 */
function closeNav(e) {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById('openNavSpan').innerHTML = '<i class="fas fa-bars fa-lg"></i>';
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

  let active = document.querySelector('.tab-content > div#active');

  let completed = document.querySelector('.tab-content > div#completed');

  let ordersPage = 1;

  if(exists(active) && exists(completed))
  {
    let paginationLinks = document.getElementById('paginationLinks');
    getPage(ordersPage, active, paginationLinks, 'active');
  }

  if(exists(modal) && exists(modalTrigger))
  {
    let modal_trigger_rect = modalTrigger.getBoundingClientRect();
    let modal_image_container_rect = mainImageContainer.getBoundingClientRect();
    modalOverlay.style.height = modal_trigger_rect.height + 'px';
    modalOverlay.style.width = modal_trigger_rect.width + 'px';
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
