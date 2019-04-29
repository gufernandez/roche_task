@extends('layout')


@section('content')
    <h1>Chart</h1>
    <div id="container" style="width:100%; height:500px;"></div>
    <script>
        var month_list = [];
        var month = {};
        var months_index = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        month_list
        var comp_due_date;
        var org_date_closed;

        var records = {!! json_encode($records) !!};

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
                month["Month"] = this_date.getMonth();
                month["Year"] = this_date.getFullYear();
                month["N_Records"] = 0;
                month["Records_OnTime"] = on_time;
                on_time = 0;
                init = 0;
            }
            else if ((month["Month"] != this_date.getMonth()) || (month["Year"] != this_date.getFullYear())){

                my_categories.push((month["Year"].toString()).concat("-", months_index[month["Month"]]));

                my_data.push(Math.round(100*Number(month["Records_OnTime"])/Number(month["N_Records"])));

                my_data_t.push(month["N_Records"]);

                month_list.push({
                    "Month": month["Month"],
                    "Year": month["Year"],
                    "N_Records": month["N_Records"],
                    "Records_OnTime": month["Records_OnTime"]
                });

                month["Month"] = this_date.getMonth();
                month["Year"] = this_date.getFullYear();
                month["N_Records"] = 1;
                month["Records_OnTime"] = on_time;
                on_time = 0;
            }
            else {
                month["N_Records"] ++;
                month["Records_OnTime"] += on_time;
                on_time = 0;
            }


        }
        console.log(month_list);
        console.log(my_data);
        console.log(my_categories);

        document.addEventListener('DOMContentLoaded', function () {
          var myChart = Highcharts.chart('container', {
              title: {
                  text: 'On Time Completion'
              },
              xAxis: {
                  categories: my_categories
              },
              series: [{
                  type: 'column',
                  name: 'Jane',
                  data: my_data
              }, {
                  type: 'spline',
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

@section('title',"Charting")
