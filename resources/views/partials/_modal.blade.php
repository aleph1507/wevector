<div class="modal" id="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div style="width:100%;"></div>
  </div>
  <script type="text/javascript">
    function loadContent(content) {
      console.log('loadContent');
      console.log(`<img src="${content}" >`);
      $(".modal-content > div").html(`<img src=${content} class='img-fluid'>`);
    }
  </script>
</div>
