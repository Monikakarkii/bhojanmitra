@extends('backend.layout.app') <!-- Assuming you have a layout file named app.blade.php -->

@section('title', 'Dashboard') <!-- Page Title -->

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                {{-- <h3 >{{ $menuItemCount }}</h3> <!-- Total Menu Items --> --}}
                                <h3 >10</h3> <!-- Total Menu Items -->
                                <p>Menu Items</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            {{-- <a href="{{route('menu-items.index')}}" class="small-box-footer">View Menu item.. <i class="fas fa-arrow-circle-right"></i></a> --}}
                            <a href="#" class="small-box-footer">View Menu item.. <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                {{-- <h3>{{$tablesCount}}</h3> --}}
                                <h3>5</h3>
                                <p>Tables</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            {{-- <a href="{{route('tables.index')}}" class="small-box-footer">View all table <i class="fas fa-arrow-circle-right"></i></a> --}}
                            <a href="#" class="small-box-footer">View Menu item.. <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>44</h3>
                                <p>User Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>65</h3>
                                <p>Unique Visitors</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->

                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-12 connectedSortable">
                        <!-- Custom Chart Box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    Orders
                                </h3>
                                <div class="card-tools">
                                    {{-- <a href="{{ route('orders.index') }}" class=" ml-2"> --}}
                                        <a href="#" class=" ml-2">
                                        <i class="fas fa-red o"></i> View All Orders
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <!-- Latest Orders -->
                                    <div class="tab-pane active" id="latest-orders" style="position: relative; height: auto;">
                                        <h5>Latest 5 Orders</h5>
                                        {{-- <ul class="list-group">
                                            @forelse($orders as $order)
                                                <li class="list-group-item">
                                                    <div>
                                                        <strong>Order #{{ $order->id }}</strong>
                                                        - <span>Table ->{{ $order->table->table_number ?? 'N/A' }}</span>
                                                        Status:
                                                        <span class="badge
                                                          @if($order->order_status == 'pending') badge-warning
                                                          @elseif($order->order_status == 'preparing') badge-info
                                                          @elseif($order->order_status == 'paid') badge-success
                                                          @elseif($order->order_status == 'canceled') badge-danger
                                                          @endif
                                                         ">
                                                            {{ ucfirst($order->order_status) }}
                                                        </span>
                                                    </div>
                                                    <span class="text-muted float-right">
                                                        <i class="fa fa-calendar-alt"></i> {{ $order->created_at->format('d M Y') }}
                                                          <i class="fa fa-clock"></i> {{ $order->created_at->format('h:i A') }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-center text-muted">No recent orders available</li>
                                            @endforelse
                                        </ul> --}}
                                    </div>
                                </div>
                            </div>

                        </div>

                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                </div>
                <!-- /.row -->
            </div>
        </section>
    </div>
@endsection
