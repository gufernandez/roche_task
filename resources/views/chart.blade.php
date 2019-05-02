@extends('layout')


@section('content')

    <div id="container" style="width:100%; height:500px;"></div>
    <script>

        var this_month = {};
        var months_index = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        var comp_due_date;
        var org_date_closed;

        var records = {!! json_encode($records) !!};
        console.log(records);
        var format_c_date;
        var format_o_date;

        var this_date;

        var on_time = 0;
        var init = 1;

        var my_categories = [];
        var my_data = [];
        var my_data_t = [];

        for (i=0; i < records.length; i++){
            comp_due_date = records[i].compliance_due_date;
            org_date_closed = records[i].original_date_closed;

            format_c_date = comp_due_date.split(/\D/);
            format_o_date = org_date_closed.split(/\D/);

            this_date = new Date(format_c_date[2],Number(format_c_date[0])-1, format_c_date[1]);
            orig_date = new Date(format_o_date[2],Number(format_o_date[0])-1, format_o_date[1]);

            if (this_date.getTime() + 24*60*60*1000 >= orig_date.getTime())
                on_time = 1;

            if (init == 1){
                this_month["Month"] = this_date.getMonth();
                this_month["Year"] = this_date.getFullYear();
                this_month["N_Records"] = 0;
                this_month["Records_OnTime"] = on_time;
                on_time = 0;
                init = 0;
            }
            else if ((this_month["Month"] != this_date.getMonth()) || (this_month["Year"] != this_date.getFullYear())){

                my_categories.push((this_month["Year"].toString()).concat("-", months_index[this_month["Month"]]));

                my_data.push(Math.round(100*Number(this_month["Records_OnTime"])/Number(this_month["N_Records"])));

                my_data_t.push(this_month["N_Records"]);

                this_month["Month"] = this_date.getMonth();
                this_month["Year"] = this_date.getFullYear();
                this_month["N_Records"] = 1;
                this_month["Records_OnTime"] = on_time;
                on_time = 0;
            }
            else {
                this_month["N_Records"] ++;
                this_month["Records_OnTime"] += on_time;
                on_time = 0;
            }


        }

        document.addEventListener('DOMContentLoaded', function () {
          var myChart = Highcharts.chart('container', {
              chart: {
                  alignTicks: false
              },
              title: {
                  text: 'On Time Completion'
              },
              yAxis: [{
                  max: 100,
                  title: {
                      text: "Percentage of Completion On Time"
                  }
              }, {
                  title: {
                      text: 'Total Number of Projects'
                  },
                  opposite: true
              }],
              xAxis: {
                  categories: my_categories
              },
              series: [{
                  type: 'column',
                  yAxis: 0,
                  data: my_data
              }, {
                  type: 'spline',
                  yAxis: 1,
                  name: 'Total Number of Records',
                  data: my_data_t,
                  marker: {
                      lineWidth: 2,
                      lineColor: Highcharts.getOptions().colors[3],
                      fillColor: 'white'
                  }
              }]
          });
        });

    </script>

@endsection

@section('title',"Records Completion Chart")
