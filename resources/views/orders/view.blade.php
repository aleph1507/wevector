@extends('layouts.app')

@section('content')

  <div class="container">
    <div class="row">
      <div class="card w-100">
        <div class="card-header text-center">
          <h1>{{$order->name}}</h1>
        </div>
        <div class="card-body p-5">
          <div class="row">
            <div class="col-xs-12 col-md-5 offset-md-1">
              <div class="row border-bottom border-info">
                <span>Orientation:</span>
                <span class="ml-3">{{$order->orientation}}</span>
              </div>
              @if($order->color_scheme != null)
                <div class="row border-bottom border-info mt-3">
                  <span>Color Scheme:</span>
                  <span class="ml-3">{{$order->color_scheme}}</span>
                </div>
              @endif
              <div class="row border-bottom border-info mt-3">
                <span>Size:</span>
                <span class="ml-3">
                  {{$order->width}}x{{$order->height}} {{$order->units}}
                </span>
              </div>
                @if($order->additional_files != null)
                    <div class="row mt-5 ml-5">
                        <ul class="three-cols">
                            @foreach(explode(';', $order->additional_files) as $add_file)
                                <li class="border-info pt-2">
                                    <a
                                        target="_blank"
                                        href="{{asset('images/' . $order->id . '/additional_files' . '/' . $add_file)}}">
                                        {{$add_file}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div
              class="col-xs-12 col-md-6 d-flex justify-content-center align-items-center mt-3 main-img-col">
              <div class="main-image-container" id="main-image-container">
                <div class="main-image-overlay d-none" id="main-image-overlay">+</div>
                <img src="{{asset('images/' . $order->id . '/thumb' . '/' . $order->file)}}"
                  alt="{{$order->file}}"
                  id="modal-trigger"
                  data-content="{{asset('images/' . $order->id . '/' . $order->file)}}"
                  class="img-fluid modal-trigger">
              </div>
            </div>
          </div>

          {{--@if($order->additional_files != null)--}}
            {{--<div class="row mt-5 ml-5">--}}
              {{--<ul class="three-cols">--}}
                {{--@foreach(explode(';', $order->additional_files) as $add_file)--}}
                  {{--<li class="border-bottom border-info pt-2">--}}
                    {{--<a--}}
                      {{--target="_blank"--}}
                      {{--href="{{asset('images/' . $order->id . '/additional_files' . '/' . $add_file)}}">--}}
                      {{--{{$add_file}}--}}
                    {{--</a>--}}
                  {{--</li>--}}
                {{--@endforeach--}}
              {{--</ul>--}}
            {{--</div>--}}
          {{--@endif--}}
          @if(Auth::user()->isAdmin())
                <form action="{{route('orders.changeStatus')}}" method="POST" class="my-5">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$order->id}}" name="id">
                    <select name="status">
                        <option value="Received" {{$order->status == "Received" ? 'selected' : ''}}>Received</option>
                        <option value="In Process" {{$order->status == "In Process" ? 'selected' : ''}}>In Process</option>
                        <option value="On Hold" {{$order->status == "On Hold" ? 'selected' : ''}}>On Hold</option>
                        <option value="Completed" {{$order->status == "Completed" ? 'selected' : ''}}>Completed</option>
                    </select>
                    <input type="submit" class="btn btn-sm btn-outline-dark" value="Change Status">
                </form>
          @endif
          <a href="{{route('orders.index')}}">&laquo; View orders</a>
        </div>
      </div>
    </div>
  </div>

@endsection
