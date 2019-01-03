
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

function custom_select()
{
  var x, i, j, selElmnt, a, b, c;
  /*look for any elements with the class "custom-select":*/
  x = document.getElementsByClassName("wv_select");
  for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    let first = true;
    for (j = 1; j < selElmnt.length; j++) {
      /*for each option in the original select element,
      create a new DIV that will act as an option item:*/
      if(j == 1 && first){
        j--;
        first=false;
      }
      c = document.createElement("DIV");
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener("click", function(e) {
          /*when an item is clicked, update the original select box,
          and the selected item:*/
          var y, i, k, s, h;
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
          h = this.parentNode.previousSibling;
          for (i = 0; i < s.length; i++) {
            if (s.options[i].innerHTML == this.innerHTML) {
              s.selectedIndex = i;
              h.innerHTML = this.innerHTML;
              y = this.parentNode.getElementsByClassName("same-as-selected");
              for (k = 0; k < y.length; k++) {
                y[k].removeAttribute("class");
              }
              this.setAttribute("class", "same-as-selected");
              break;
            }
          }
          h.click();
      });
      b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
        /*when the select box is clicked, close any other select boxes,
        and open/close the current select box:*/
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
      });
  }
  function closeAllSelect(elmnt) {
    /*a function that will close all select boxes in the document,
    except the current select box:*/
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    for (i = 0; i < y.length; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i)
      } else {
        y[i].classList.remove("select-arrow-active");
      }
    }
    for (i = 0; i < x.length; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("select-hide");
      }
    }
  }
  /*if the user clicks anywhere outside the select box,
  then close all select boxes:*/
  document.addEventListener("click", closeAllSelect);
}

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

var navBtnHTML = '';

function openNav(e) {
    if(document.getElementById("mySidenav").style.width != "350px"){
      document.querySelector('.additional-btns').style.marginRight = '350px';
      document.getElementById("mySidenav").style.width = "350px";
      // navBtnHTML = document.getElementById('openNavSpan').innerHTML;
      // document.getElementById('openNavSpan').innerHTML = '&times;';
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
            <div class="col-md-2 align-middle">${o.status}</div>
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
                <span>Status: </span> <span>${o.status}</span>
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
  if(last_page == 1)
  {
    paginator.style.display = 'none';
    return;
  }
  paginator.style.display = 'block';
  paginator.innerHTML = '';
  for(let i = 1; i<=last_page; i++)
    paginator.innerHTML += i == current_page ? createPaginatorBtn(i, query, 'current') : createPaginatorBtn(i, query);
}

function getPage(page, element, paginator, query, queryString = '', etarget = null, newHTML = null, sortBy = '', direction = '')
{
  let url = '';
  queryString = document.getElementById('search-orders').value;
  if(direction !== '')
      direction = '&direction=' + (direction == 'asc' ? 'asc' : 'desc');
  if(etarget != null && newHTML != null && sortBy != '' && direction != '') {
     sortBy = '&sortBy=' + sortBy;
  }
  if(queryString != '') queryString = '&queryString=' + queryString + sortBy + direction;
  if(query == 'active') url = '/orders/active?page=' + page + queryString + sortBy + direction;
  if(query == 'completed') url = '/orders/completed?page=' + page + queryString + sortBy + direction;

  let success = null;
  axios.get(url)
    .then(function(response) {
      updatePaginator(paginator, response.data.current_page, response.data.last_page, query);
      renderOrders(response, element);
      setPaginationEvents(element, paginator, query, document.querySelectorAll('.paginator-btn'));
      console.log('etarget: ', etarget);
      if(sortBy != ''){
          console.log('sortBy: ', sortBy);
          etarget.innerHTML = newHTML;
          console.log('newHTML: ', newHTML);
          console.log('etarget.innerHTML: ', etarget.innerHTML);
      }

      success = 0;
    })
    .catch(function(error) {
      console.log(error.message);
      success = 1;
    });
    // .then(function() {
    //   //always executed
    // });
    return success;
}

/* Set the width of the side navigation to 0 */
function closeNav(e) {
    document.getElementById("mySidenav").style.width = "0";
    // document.getElementById('openNavSpan').innerHTML = navBtnHTML;
    document.querySelector('.additional-btns').style.marginRight = '0px';
    // document.getElementById('openNavSpan').innerHTML = '<i class="fas fa-bars fa-lg"></i>';
}

function setPaginationEvents(element, paginator, query, paginatorBtns)
{
  for(let pb of paginatorBtns) {
    pb.addEventListener('click', function(e) {
      getPage($(pb).data('page'), element, paginator, $(pb).data('query'))
    });
  }
}

function sendOrder(event) {
    let loadingHTML = '<div class="wrap pb-5">\n' +
        '  <div class="loading">\n' +
        '    <div class="bounceball"></div>\n' +
        '    <div class="text">Processing your order...</div>\n' +
        '  </div>\n' +
        '</div>';

    event.target.parentNode.innerHTML = loadingHTML;

    document.getElementById('form.orders.store').submit();
    // document.querySelector('#form.orders.store').submit();
}

$(document).ready(function() {

  if(document.getElementById('openNavSpan') != null)
    document.getElementById('openNavSpan').addEventListener("click", openNav);

  if(document.getElementById('closeNavBtn') != null)
    document.getElementById('closeNavBtn').addEventListener("click", closeNav);

  if(document.getElementById('btn-send-order')) {
      document.getElementById('btn-send-order').addEventListener('click', sendOrder);
  }

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

  let activeTab = document.getElementById('activeTab');

  let completedTab = document.getElementById('completedTab');

  let currentlyActive = null;

  let ordersPage = 1;

  let grantAdminBtns = $('.grantAdmin');

  let revokeAdminBtns = $('.revokeAdmin');

  let deleteUserBtns = $('.deleteUser');

  let adminControls = $('.admin-control');

  let searchOrders = document.getElementById('search-orders');

  let sortOrders = document.getElementsByClassName('sort-orders');

  custom_select();

  function deleteUser(user)
  {
    axios.delete('/users/' + user.data('userid'))
      .then(function(response) {
        user.closest('tr').css('display', 'none');
        toastr.success('User successfully deleted.');
      })
      .catch(function(error) {
        toastr.error('There\'s been an error deleting the user.');
        console.log(error);
      });
  }

  function adminStatus(user)
  {
    let grant = user.hasClass('revokeAdmin') ? true : false;
    console.log('grant: ', grant);
    let url = grant ? '/users/radmin/' + user.data('userid') : '/users/aadmin/' + user.data('userid');
    let classToRemove = grant ? 'revokeAdmin btn-warning' : 'grantAdmin btn-outline-dark';
    let classToAdd = grant ? 'grantAdmin btn-outline-dark' : 'revokeAdmin btn-warning';
    let btnHtml = grant ? 'Grant Admin Status' : 'Revoke Admin Status';
    let toastText = grant ? ' is no longer an admin.' : ' is now an admin.';
    axios.post(url)
      .then(function(response) {
        console.log(response);
        user.removeClass(classToRemove);
        user.addClass(classToAdd);
        user.html(btnHtml);
        toastr.success(user.data('username') + toastText);
      })
      .catch(function(error) {
        toastr.error('There\'s been an error.');
      })
      .then(function() {

      })
  }

  if(adminControls.length && deleteUserBtns.length > 0)
  {
    for(let i = 0; i < deleteUserBtns.length; i++)
    {
      $(deleteUserBtns[i]).on('click', function(e) {
        let confirmDelete =
          confirm('Delete user with username ' + $(e.target).data('username') + '?');
        confirmDelete ? deleteUser($(e.target)) : console.log('no delete');
      });
    }

    for(let i = 0; i<adminControls.length; i++)
    {
      $(adminControls[i]).on('click', function(e){
        let confirmAdmin = confirm('Are you sure?');
        confirmAdmin ? adminStatus($(e.target)) : console.log('no admin');
      });
    }
  }

  if(exists(active) && exists(completed))
  {
    let paginationLinks = document.getElementById('paginationLinks');
    currentlyActive = 'active';
    getPage(ordersPage, active, paginationLinks, currentlyActive);
    activeTab.addEventListener('click', function(tab) {
      currentlyActive = 'active';
      console.log(currentlyActive);
      getPage(ordersPage, active, paginationLinks, currentlyActive);
    });
    completedTab.addEventListener('click', function(tab) {
      currentlyActive = 'completed';
      console.log(currentlyActive);
      getPage(ordersPage, completed, paginationLinks, currentlyActive);
    });
    searchOrders.addEventListener('keyup', function(event) {
        let qString = event.target.value;
        let tab = currentlyActive == 'completed' ? completed : active;
        getPage(ordersPage, tab, paginationLinks, currentlyActive, qString);
    });
    for(let i = 0; i<sortOrders.length; i++) {
        sortOrders[i].addEventListener('click', function(event) {
            console.log('so event');
            let caretUp = '<i class="fas fa-caret-up"></i>';
            let caretDown = '<i class="fas fa-caret-down"></i>';
            let innerHTML = sortOrders[i].innerHTML;
            let sortBy = innerHTML.split(' ')[0];
            let direction = innerHTML.indexOf(caretUp) !== -1 ? 'asc' : 'desc';
            let newHTML = sortBy + ' ' + (direction == 'asc' ? caretDown : caretUp);
            let tab = currentlyActive == 'completed' ? completed : active;
            let etarget = (event.target.tagName.toUpperCase() === 'I' ? event.target.parentNode : event.target)
            getPage(ordersPage, tab, paginationLinks, currentlyActive, '', etarget, newHTML, sortBy, direction);
        });
    }
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
    $('#previewImgDiv').removeClass('h-100');
    output.src = reader.result;
   }
   reader.readAsDataURL(event.target.files[0]);
   document.getElementById("mainFileName").innerHTML =
    event.target.value.split('\\').pop();
  }
});
