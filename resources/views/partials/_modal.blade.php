<div class="modal" id="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <span style="width:100%;" id="fullImgLink"></span>
    <div style="width:100%;"></div>
  </div>
  <script type="text/javascript">
    function loadContent(content) {
      console.log('loadContent');
      console.log(`<img src="${content}" >`);
      $(".modal-content > div").html(`<img src=${content} class='img-fluid'>`);
      $(".modal-content > #fullImgLink").html(`<a target='_blank' href=${content}>See img in a separate tab</a>`)
    }
  </script>
</div>
