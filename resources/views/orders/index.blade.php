@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row d-flex justify-content-between align-items-between">
      <h1 class="text-uppercase light-height-1 pt-3">requests</h1>
      <div class="new_illustraion_div" id="new_illustraion_div">
        <button type="button" name="new_illustration" id="new_illustration"
        class="btn btn-pink btn-round-1 text-white text-center">+</button>
        <span class="menu-span-1">Request new illustration</span>
      </div>
    </div>

    <div class="list-orders mt-5">
      <ul class="nav nav-tabs orders-tabs" role="tablist">
        <li class="nav-item">
          <a data-toggle="tab" class="nav-link active" href="#active"
            role="tab" aria-controls="active" aria-selected="true">Active</a>
        </li>
        <li class="nav-item">
          <a data-toggle="tab" class="nav-link" href="#completed"
            role="tab" aria-controls="profile" aria-selected="false">Completed</a>
        </li>
      </ul>
      <div class="d-flex justify-content-start align-items-start mt-4 mb-3 t-header">
        <div class="col-2-filler">&nbsp;</div>
        <div class="col-2">Name</div>
        <div class="col-2">ID</div>
        <div class="col-2">Type</div>
        <div class="col-2">Sent on</div>
        <div class="col-2">Status</div>
        <div class="col-2">Comment</div>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="active">
            @foreach($activeOrders as $ao)
              <a href="{{route('orders.view', $ao->id)}}">
                <div class="d-flex mt-2 mb-2 order-row">
                  <img src="{{asset('images/' . $ao->id . '/thumb' . '/sm' . '/' . $ao->file)}}"
                    alt="image of {{$ao->name}}" class="mr-3">
                    <div class="col-2 align-middle">
                      <span>{{$ao->name}}</span>
                    </div>
                    <div class="col-2 align-middle">{{$ao->id}}</div>
                    <div class="col-2 align-middle">Rushi</div>
                    <div class="col-2 align-middle">{{$ao->created_at->format('d/m/Y')}}</div>
                    <div class="col-2 align-middle">Pocinat</div>
                    <div class="col-2 align-middle">Jok</div>
                </div>
              </a>
            @endforeach
        </div>
        <div class="tab-pane fade" id="completed">
          @foreach($completedOrders as $co)
            <a href="{{route('orders.view', $co->id)}}">
              <div class="d-flex mt-2 mb-2 order-row">
                <img src="{{asset('images/' . $co->id . '/thumb' . '/sm' . '/' . $co->file)}}"
                  alt="image of {{$co->name}}" class="mr-3">
                  <div class="col-2 align-middle">
                    <span>{{$co->name}}</span>
                  </div>
                  <div class="col-2 align-middle">{{$co->id}}</div>
                  <div class="col-2 align-middle">Rushi</div>
                  <div class="col-2 align-middle">{{$co->created_at}}</div>
                  <div class="col-2 align-middle">Pocinat</div>
                  <div class="col-2 align-middle">Jok</div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

@endsection
