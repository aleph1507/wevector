<div class="modal" id="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div></div>
  </div>
  <script type="text/javascript">
    function loadContent(content) {
      console.log('loadContent');
      console.log(`<img src="${content}">`);
      $(".modal-content > div").html(`<img src="${content}">`);
    }
  </script>
</div>
