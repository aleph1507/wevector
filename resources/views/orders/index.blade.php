@extends('layouts.app')

@section('content')
  <div class="container pt-5">
    <div class="row d-flex justify-content-between align-items-between pl-xs-5 pl-sm-5">
      <h1 class="text-uppercase light-height-1 pt-3">requests</h1>
      <div class="row">
          <div class="col-xs-10 col-sm-10 col-md-7">
              <a href="{{route('orders.create')}}">
                  <div class="new_illustraion_div" id="new_illustraion_div">
                      <button type="button" name="new_illustration" id="new_illustration"
                              class="btn btn-pink btn-round-1 text-white text-center">+</button>
                      <span class="menu-span-1">Request new illustration</span>
                  </div>
              </a>
          </div>
          <div class="col-4">
              <div class="inputWithIcon">
                  <input type="text" id="search-orders" placeholder="Search">
                  <i class="fas fa-search"></i>
              </div>
          </div>
      </div>
    </div>

    <div class="list-orders mt-5">
      <ul class="nav nav-tabs orders-tabs" role="tablist">
        <li class="nav-item">
          <a data-toggle="tab" class="nav-link active text-center" href="#active"
            role="tab" aria-controls="active"
            id="activeTab" aria-selected="true">Active Orders</a>
        </li>
        <li class="nav-item">
          <a data-toggle="tab" class="nav-link text-center" href="#completed"
            role="tab" aria-controls="profile"
            id="completedTab" aria-selected="false">Completed Orders</a>
        </li>
      </ul>
     <div id="orders-table-div">
      <div class="d-none d-md-block">
        <div class="d-flex justify-content-start align-items-start mt-4 mb-3 t-header row pl-xs-5">
          {{--<div class="col-1 d-none d-sm-block">&nbsp;</div>--}}
            <span class="offset-1 sort-orders" id="so-name">Name <i class="fas fa-caret-down"></i></span>
            <span class="offset-1 sort-orders" id="so-id">ID <i class="fas fa-caret-down"></i></span>
            <span class="offset-1 sort-orders" id="so-type">Type <i class="fas fa-caret-down"></i></span>
            <span class="offset-1 sort-orders" id="so-sentOn">Sent on <i class="fas fa-caret-down"></i></span>
            <span class="offset-1 sort-orders" id="so-status">Status <i class="fas fa-caret-down"></i></span>
            <span class="offset-1 sort-orders" id="so-comment">Comment <i class="fas fa-caret-down"></i></span>
          {{--<div class="col-2 sort-orders" id="so-name">Name <i class="fas fa-caret-down"></i></div>--}}
          {{--<div class="col-2 sort-orders" id="so-id">ID <i class="fas fa-caret-down"></i></div>--}}
          {{--<div class="col-2 sort-orders" id="so-type">Type <i class="fas fa-caret-down"></i></div>--}}
          {{--<div class="col-2 sort-orders" id="so-sentOn">Sent on <i class="fas fa-caret-down"></i></div>--}}
          {{--<div class="col-2 sort-orders" id="so-status">Status <i class="fas fa-caret-down"></i></div>--}}
          {{--<div class="col-1 sort-orders" id="so-comment">Comment <i class="fas fa-caret-down"></i></div>--}}
        </div>
      </div>
      <div class="tab-content">
        <div class="tab-pane fade show active mt-xs-5 mt-sm-5 mt-md-0" id="active">
            {{-- @foreach($activeOrders as $ao)
              <a href="{{route('orders.view', $ao->id)}}" class="d-none d-md-block">
                <div class="d-flex mt-2 mb-2 order-row row">
                  <div class="col-md-1">
                    <img src="{{asset('images/' . $ao->id . '/thumb' . '/sm' . '/' . $ao->file)}}"
                    alt="image of {{$ao->name}}" class="mr-3">
                  </div>
                    <div class="col-md-2 align-middle">
                      <span>{{$ao->name}}</span>
                    </div>
                    <div class="col-md-2 align-middle">{{$ao->id}}</div>
                    <div class="col-md-2 align-middle">Rushi</div>
                    <div class="col-md-2 align-middle">{{$ao->created_at->format('d/m/Y')}}</div>
                    <div class="col-md-2 align-middle">Pocinat</div>
                    <div class="col-md-1 align-middle">Jok</div>
                </div>
              </a>
              <a href="{{route('orders.view', $ao->id)}}" class="d-block d-md-none">
                <div class="d-flex mt-2 mb-2 orders-sm row">
                  <div class="col-3">
                    <img src="{{asset('images/' . $ao->id . '/thumb' . '/sm' . '/' . $ao->file)}}"
                    alt="image of {{$ao->name}}" class="mr-3">
                  </div>
                  <div class="col-9">
                    <div class="row">
                      <div class="col-6">
                        <span>Name: </span> <span>{{$ao->name}}</span>
                      </div>
                      <div class="col-6">
                        <span>ID: </span> <span>{{$ao->id}}</span>
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
                        <span>Sent on: </span> <span>{{$ao->created_at->format('d/m/Y')}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            @endforeach
            {{$activeOrders->links()}} --}}

        </div>
        <div class="tab-pane fade" id="completed">
          {{-- @foreach($completedOrders as $co)
            <a href="{{route('orders.view', $co->id)}}" class="d-none d-md-block">
              <div class="d-flex mt-2 mb-2 order-row row">
                <div class="col-md-1">
                  <img src="{{asset('images/' . $co->id . '/thumb' . '/sm' . '/' . $co->file)}}"
                  alt="image of {{$co->name}}" class="mr-3">
                </div>
                  <div class="col-md-2 align-middle">
                    <span>{{$co->name}}</span>
                  </div>
                  <div class="col-md-2 align-middle">{{$co->id}}</div>
                  <div class="col-md-2 align-middle">Rushi</div>
                  <div class="col-md-2 align-middle">{{$co->created_at->format('d/m/Y')}}</div>
                  <div class="col-md-2 align-middle">Pocinat</div>
                  <div class="col-md-1 align-middle">Jok</div>
              </div>
            </a>
            <a href="{{route('orders.view', $co->id)}}" class="d-block d-md-none">
              <div class="d-flex mt-2 mb-2 orders-sm row">
                <div class="col-3">
                  <img src="{{asset('images/' . $co->id . '/thumb' . '/sm' . '/' . $co->file)}}"
                  alt="image of {{$co->name}}" class="mr-3">
                </div>
                <div class="col-9">
                  <div class="row">
                    <div class="col-6">
                      <span>Name: </span> <span>{{$co->name}}</span>
                    </div>
                    <div class="col-6">
                      <span>ID: </span> <span>{{$co->id}}</span>
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
                      <span>Sent on: </span> <span>{{$co->created_at->format('d/m/Y')}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          @endforeach --}}
        </div>
      </div>
      </div>
      <div class="paginator" id="paginationLinks"></div>
    </div>

    <a href="{{route('orders.create')}}">&laquo; Place an order</a>
  </div>

@endsection
