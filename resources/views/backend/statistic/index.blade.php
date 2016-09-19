@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading">Users</div>
            <div class="templatemo-chart-box col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <canvas id="templatemo-pie-chart"></canvas>
            </div>
            <div>
              <ul class="data col-lg-5 col-md-5 col-sm-5 col-xs-12">
                 <li>Total: <span> {{$users}} user </span> </li>
                 <li>Deactivate : <span> {{$deactiveUser}} user </span> </li>
              </ul>
              <p class="clearfix"></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Real States</div>
            <div class="templatemo-chart-box col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <canvas id="templatemo-doughnut-chart"></canvas>
            </div>
            <ul class="data col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <li>Total: <span> {{$real}} realstate </span> </li>
              <li>show : <span> {{$realShow}} realstate  </span> </li>
              <li>hidden : <span> {{$realHidden}} realstate  </span> </li>
            </ul>
            <p class="clearfix"></p>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading">Service Places</div>
            <div class="templatemo-chart-box col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <canvas id="templatemo-radar-chart"></canvas>
            </div>
            <ul class="data col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <li>Total: <span> {{$service}} ServicePlace </span> </li>
              <li>Approve : <span> {{$serviceApprove}} ServicePlace  </span> </li>
              <li>Hide : <span> {{$serviceHidden}} ServicePlace  </span> </li>
            </ul>
            <p class="clearfix"></p>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Discussions</div>

            <div class="templatemo-chart-box col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <canvas id="templatemo-polar-chart"></canvas>
            </div>
            <ul class="data col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <li>Total: <span> {{$discussion}} Discussion </span> </li>
              <li>Hide : <span> {{$discussionHide}} Discussion  </span> </li>
            </ul>
            <p class="clearfix"></p>
        </div>
    </div>
</div>
<script type="text/javascript">
  // Line chart
  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
  var lineChartData = {
    labels : ["January","February","March","April","May","June","July"],
    datasets : [
    {
      label: "My First dataset",
      fillColor : "rgba(220,220,220,0.2)",
      strokeColor : "rgba(220,220,220,1)",
      pointColor : "rgba(220,220,220,1)",
      pointStrokeColor : "#fff",
      pointHighlightFill : "#fff",
      pointHighlightStroke : "rgba(220,220,220,1)",
      data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
    },
    {
      label: "My Second dataset",
      fillColor : "rgba(151,187,205,0.2)",
      strokeColor : "rgba(151,187,205,1)",
      pointColor : "rgba(151,187,205,1)",
      pointStrokeColor : "#fff",
      pointHighlightFill : "#fff",
      pointHighlightStroke : "rgba(151,187,205,1)",
      data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
    }
    ]

  } // lineChartData

  var pieChartData = [
  {
    value: {{$users}},
    color:"#F7464A",
    highlight: "#FF5A5E",
    label: "User"
  },
  {
    value: {{$deactiveUser}},
    color: "#46BFBD",
    highlight: "#5AD3D1",
    label: "deactiveUser"
  }
  //,
  // {
  //   value: 100,
  //   color: "#FDB45C",
  //   highlight: "#FFC870",
  //   label: "Yellow"
  // }
  ] // pie chart data
  var digChartData = [
  {
    value: {{$real}},
    color:"#F7464A",
    highlight: "#FF5A5E",
    label: "Real state"
  },
  {
    value: {{$realShow}},
    color: "#46BFBD",
    highlight: "#5AD3D1",
    label: "Real Show"
  },
   {
     value: {{$realHidden}},
     color: "#FDB45C",
     highlight: "#FFC870",
     label: "Real Hidden"
   }
  ] // pie chart data

  // radar chart
  var radarChartData = [
  {
    value: {{$service}},
    color:"#F7464A",
    highlight: "#FF5A5E",
    label: "Service Place"
  },
  {
    value: {{$serviceApprove}},
    color: "#46BFBD",
    highlight: "#5AD3D1",
    label: "service Approve"
  },
   {
     value: {{$serviceHidden}},
     color: "#FDB45C",
     highlight: "#FFC870",
     label: "service Hidden"
   }
  ] // pie chart data
  // polar area chart
  var polarAreaChartData = [
  {
    value: {{$discussion}},
    color:"#F7464A",
    highlight: "#FF5A5E",
    label: "All discussion"
  },
  {
    value: {{$discussionHide}},
    color: "#FDB45C",
    highlight: "#FFC870",
    label: "discussion Hidden"
  }
  ];

  window.onload = function(){
    var ctx_pie = document.getElementById("templatemo-pie-chart").getContext("2d");
    var ctx_doughnut = document.getElementById("templatemo-doughnut-chart").getContext("2d");
    var ctxRadar = document.getElementById("templatemo-radar-chart").getContext("2d");
    var ctxPolar = document.getElementById("templatemo-polar-chart").getContext("2d");

    window.myPieChart = new Chart(ctx_pie).Pie(pieChartData,{
      responsive: true
    });
    window.myDoughnutChart = new Chart(ctx_doughnut).Doughnut(digChartData,{
      responsive: true
    });
    var myRadarChart = new Chart(ctxRadar).Doughnut(radarChartData, {
      responsive: true
    });
    var myPolarAreaChart = new Chart(ctxPolar).Doughnut(polarAreaChartData, {
      responsive: true
    });
  }
</script>

@show

@endsection
