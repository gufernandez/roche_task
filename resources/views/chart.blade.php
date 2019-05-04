@extends('layout')


@section('content')

    <div id="container" style="width:100%; height:500px;"></div>
    <script>

        var this_month = {};
        var months_index = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        var records = {!! json_encode($records) !!};

        var format_c_date;
        var format_o_date;

        var this_date;
        var orig_date;

        var on_time = 0;
        var init = 1;

        var list_months = [];

        for (i=0; i < records.length; i++){

            format_c_date = records[i].compliance_due_date.split(/\D/);
            format_o_date = records[i].original_date_closed.split(/\D/);
            this_date = new Date(format_c_date[2],Number(format_c_date[0])-1, format_c_date[1]);
            orig_date = new Date(format_o_date[2],Number(format_o_date[0])-1, format_o_date[1]);

            if (this_date.getTime() + 24*60*60*1000 >= orig_date.getTime())
                on_time = 1;

            if (init == 1){
                this_month["Month"] = this_date.getMonth();
                this_month["Year"] = this_date.getFullYear();
                this_month["N_Records"] = 1;
                this_month["Records_OnTime"] = on_time;
                on_time = 0;
                init = 0;
            }
            else if ((this_month["Month"] != this_date.getMonth()) || (this_month["Year"] != this_date.getFullYear())){

                list_months.push(this_month);

                var this_month = {"Month": this_date.getMonth(), "Year": this_date.getFullYear(), "N_Records": 1, "Records_OnTime": on_time};

                on_time = 0;
            }
            else {
                this_month["N_Records"] ++;
                this_month["Records_OnTime"] += on_time;
                on_time = 0;
            }


        }
        list_months.push(this_month);

        var my_categories = new Array(list_months.length);
        var my_data = new Array(list_months.length);
        var my_data_t = new Array(list_months.length);

        var pos = 0;

        for (i=0; i < list_months.length; i++){
            for (j=0; j < list_months.length; j++){

                if (list_months[i]["Year"] > list_months[j]["Year"])
                    pos++;
                if ((list_months[i]["Year"] == list_months[j]["Year"]) && (list_months[i]["Month"] > list_months[j]["Month"]))
                    pos++;

            }

            my_categories[pos] = (list_months[i]["Year"].toString()).concat("-", months_index[list_months[i]["Month"]]);

            my_data[pos] = Math.round(100*Number(list_months[i]["Records_OnTime"])/Number(list_months[i]["N_Records"]));

            my_data_t[pos] = list_months[i]["N_Records"];

            pos = 0;
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
                  },
                  labels: {
                      formatter: function () {
                          return this.value+"%";
                      }
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
                  name: 'Percentage of Records Completed on Time',
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
