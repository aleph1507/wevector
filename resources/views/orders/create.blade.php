@extends('layouts.app')

@section('content')
  <div class="container bg-white pt-5 pl-sm-1 pl-xs-1 pl-md-5">
    <div class="row d-flex flex-column justify-content-center align-items-center">
      <h1 class="uppercase large-title">send your order</h1>
      <h4 class="subtitle mt-5 mb-5">Fill in the form below to share your raster graphics with us.</h4>
    </div>
    <form class="order-form mt-5" action="{{route('orders.store')}}" id="form.orders.store"
      enctype="multipart/form-data" method="post">
      {{csrf_field()}}
      <div class="row">
        <div class="col-xs-10 offset-xs-1 col-md-6">
          <input type="text" class="w-100" name="name" value="{{ old('name') }}"
          placeholder="1. artwork name *" required>
          <input type="hidden" name="orientation"
          id="orientation" value="portrait" required>
          <h3 class="uppercase mt-5 mb-3 mid-title">2. artwork orientation *</h3>
          <div class="row">
            <div class="col-xs-12 col-md-5">
              <button type="button" name="portrait"
                id="portrait" data-input="#orientation"
                data-value="portrait"
                class="select-btn {{ old('orientation') != 'landscape' ? 'selected' : ''}}"
                >Portrait</button>
            </div>
            <div class="col-xs-12 col-md-5 offset-md-2">
              <button type="button" name="landscape"
                id="landscape" data-input="#orientation"
                data-value="landscape"
                class="select-btn {{ old('orientation') == 'landscape' ? 'selected' : ''}}"
                >Landscape</button>
            </div>
          </div>
          <h3 class="uppercase mt-5 mb-3 mid-title">3. artwork size *</h3>
          <div class="row">
            <div class="col-xs-10 offset-xs-1 col-md-4">
              <input type="tel" name="width" pattern="[0-9]*" id="width" class="sm-placeholder"
              value="{{ old('width') }}" placeholder="Width" onkeyup="this.value = this.value.replace(/[^0-9]/, '')" required>
            </div>
            <div class="col-xs-10 offset-xs-1 col-md-4 offset-md-1">
              <input type="tel" name="height" pattern="[0-9]*" id="height" class="sm-placeholder"
              value="{{ old('height') }}" placeholder="Height" onkeyup="this.value = this.value.replace(/[^0-9]/, '')" required>
            </div>
            <div class="col-xs-10 offset-xs-1 col-md-1 offset-md-1">
              <div class="wv_select">
                <select name="art_units">
                  <option value="mm" {{ old('wv_select') == 'mm' ? 'selected' : '' }}>mm</option>
                  <option value="cm" {{ old('wv_select') == 'cm' ? 'selected' : '' }}>cm</option>
                  <option value="in" {{ old('wv_select') == 'in' ? 'selected' : '' }}>in</option>
                </select>
              </div>
            </div>
          </div>
          <h3 class="uppercase mt-5 mb-3 mid-title">4. colour pallete</h3>
          <div class="row">
            <input type="hidden" name="color_scheme"
              id="color_scheme" value="">
            <div class="col-xs-12 col-md-5">
              <button type="button" name="rgb"
                id="rgb" data-input="#color_scheme"
                data-value="rgb"
                class="select-btn {{ old('color_scheme') == 'rgb' ? 'selected' : ''}}"
                >rgb</button>
            </div>
            <div class="col-xs-12 col-md-5 offset-md-2">
              <button type="button" name="cmyk"
                id="cmyk" data-input="#color_scheme"
                data-value="cmyk"
                class="select-btn {{ old('color_scheme') == 'cmyk' ? 'selected' : ''}}"
                >cmyk</button>
            </div>
          </div>
          <h3 class="uppercase mt-5 mb-3 mid-title">5. attach raster graphics</h3>
          <div class="nlp">
            <div class="d-flex flex-row justify-content-between">
              <label class="btn btn-lg btn-outline-dark input-btn">
                <input type="file" class="upload-input d-none"
                  accept="image/x-png,image/gif,image/jpeg"
                  name="file" id="file-upload">
                <i class="fas fa-cloud-upload-alt"></i> Attach file
              </label>
              <label>Max files size 10Mb.</label>
            </div>
            <span id="mainFileName">&nbsp;</span>
          </div>

          <div class="nlp">
            <label class="input-btn c-black">
              <input type="file"
                class="upload-input d-none" name="additional_files[]"
                accept="image/x-png,image/gif,image/jpeg"
                id="additional_files" multiple>
              <u><b>Upload another file</b></u>
            </label>
          </div>
        </div>
        <div class="col-xs-10 offset-xs-1 col-md-5 offset-md-1">
          <div class="w-75 h-50 mt-5 ml-5">
            <div class="text-center latoBold">
              - Width -
            </div>
            <div class="img-height h-100 grey-bg-img" id="previewImgDiv">
              <div class="img-height text-center w-100 d-block ml-3 heightTransformed latoBold">
                - Height -
              </div>
              <img src="" alt="" id="output_image" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12 mt-5">
          <div class="d-flex justify-content-center align-items-center" >
            <input type="submit"
              class="pink-button-lg text-uppercase"
              name="submit" value="send order" id="btn-send-order">
          </div>
        </div>
      </div>
    </form>
    <a href="{{route('orders.index')}}">&laquo; View orders</a>
  </div>
@endsection
