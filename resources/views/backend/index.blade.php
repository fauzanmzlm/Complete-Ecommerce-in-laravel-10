@extends('backend.layouts.master')
@section('title','USEBS || DASHBOARD')
@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

      <!-- Category -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Category</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{\App\Models\Category::countActiveCategory()}}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-sitemap fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Equipments -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Equipments</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{\App\Models\Product::countActiveProduct()}}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-cubes fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Booking</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{\App\Models\Order::countActiveOrder()}}</div>
                  </div>
                  
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--Posts-->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{\App\User::count()}}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">

      <!-- Area Chart -->
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Equipment Usage Overview</h6>
            
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <figure class="highcharts-figure">
              <div id="container"></div>
              <p class="highcharts-description">
                  Chart showing equipment usage. Clicking on individual columns
                  brings up more detailed data. This chart makes use of the drilldown
                  feature in Highcharts to easily switch between datasets.
              </p>
          </figure>
          </div>
        </div>
      </div>
    
      <!-- Pie Chart -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Users</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body" style="overflow:hidden">
            <div id="pie_chart" style="width:350px; height:320px;">
          </div>
        </div>
      </div>
    </div>
    <!-- Content Row -->
    
  </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
{{-- pie chart --}}
<script type="text/javascript">
  var analytics = <?php echo $users; ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart()
  {
      var data = google.visualization.arrayToDataTable(analytics);
      var options = {
          title : 'Last 7 Days registered user'
      };
      var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
      chart.draw(data, options);
  }
</script>
  {{-- line chart --}}
  <script type="text/javascript">
   
    // Data retrieved from https://gs.statcounter.com/browser-market-share#monthly-202201-202201-bar

// Create the chart
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: 'Equipment Usage. January, 2024'
    },
    subtitle: {
        align: 'left',
        text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">usebs.com</a>'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total usage'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:1f}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}</b> of total<br/>'
    },

    series: [
        {
            name: 'Category',
            colorByPoint: true,
            data: [
                {
                    name: 'Outdoor Sports',
                    y: 63,
                    drilldown: 'Outdoor Sports'
                },
                {
                    name: 'Indoor Sports',
                    y: 19,
                    drilldown: 'Indoor Sports'
                },
                {
                    name: 'Fitness and Gym',
                    y: 4,
                    drilldown: 'Fitness and Gym'
                },
                {
                    name: 'Cycling',
                    y: 10,
                    drilldown: 'Cycling'
                }
            ]
        }
    ],
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right'
            }
        },
        series: [
            {
                name: 'Outdoor Sports',
                id: 'Outdoor Sports',
                data: [
                    [
                        'Soccer balls',
                        2
                    ],
                    [
                        'Footballs',
                        3
                    ],
                    [
                        'Frisbees',
                        1
                    ],
                    [
                        'Volleyballs',
                        6
                    ]
                ]
            },
            {
                name: 'Indoor Sports',
                id: 'Indoor Sports',
                data: [
                    [
                        'Basketball',
                        3
                    ],
                    [
                        'Badminton rackets and shuttlecocks',
                        4
                    ],
                    [
                        'Table tennis paddles and balls',
                        1
                    ]
                ]
            },
            {
                name: 'Fitness and Gym',
                id: 'Fitness and Gym',
                data: [
                    [
                        'Dumbbells',
                        11
                    ],
                    [
                        'Yoga mats',
                        7
                    ],
                    [
                        'Resistance bands',
                        10
                    ]
                ]
            },
            {
                name: 'Cycling',
                id: 'Cycling',
                data: [
                    [
                        'Bicycles',
                        20
                    ],
                    [
                        'Helmets',
                        20
                    ],
                    [
                        'Bike locks',
                        20
                    ]
                ]
            }
        ]
    }
});


  </script>
@endpush