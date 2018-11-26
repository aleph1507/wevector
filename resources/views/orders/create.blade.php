@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <h1 class="uppercase">create order</h1>
    </div>
    <form class="" action="#" enctype="multipart/multipart/form-data" method="post">
      {{csrf_field()}}
      <div class="row">
        <div class="col-xs-10 offset-xs-1 col-md-5 offset-md-2">
          <input type="text" name="name" value="" placeholder="1. artwork name">
          <input type="hidden" name="orientation" value="">
          <div class="row">
            <h3 class="uppercase">artwork orientation</h3>
            <div class="col-xs-12 col-md-5">
              <button type="button" name="portrait"
              id="portrait" class="select-btn selected">Portrait</button>
            </div>
            <div class="col-xs-12 col-md-5 offset-md-2">
              <button type="button" name="landscape"
                id="landscape" class="select-btn">Landscape</button>
            </div>
          </div>
        </div>
        <div class="col-xs-10 offset-xs-1 col-md-3 offset-md-1">

        </div>
      </div>
    </form>
  </div>
@endsection
