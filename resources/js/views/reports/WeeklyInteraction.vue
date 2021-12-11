<template>
  <div class="card">
    <div class="card-header border-0">
      <div class="d-flex justify-content-center">
        <h3 class="card-title">Weekly/Daily Interactions Report</h3>
      </div>
    </div>
    <div class="card-body mt-5">
      <div class="text-center mb-5">
        <span class="text-bold text-lg">{{ date }}</span>
      </div>
      <div class="d-flex">
        <p class="d-flex flex-column">
          <span class="text-bold text-lg">
            <v-icon>mdi-account-multiple</v-icon
            >{{ payload.interactions }}</span
          >
          <span>Interactions Over Time</span>
        </p>
        <p class="ml-auto d-flex flex-column text-right">
          <span v-show="parseInt(payload.increased) > 0" class="text-success">
            <i class="fas fa-arrow-up"></i>
            {{ payload.increased }}%
          </span>

          <span v-show="parseInt(payload.increased) < 0" class="text-danger">
            <i class="fas fa-arrow-down"></i>
            {{ payload.increased }}%
          </span>

          <span v-show="payload.increased == 0">
            {{ payload.increased }}%
          </span>
          <span class="text-muted">Since last month</span>
        </p>
      </div>
      <!-- /.d-flex -->
      <div class="position-relative mb-4">
        <canvas id="weekly-sales-chart" height="300"></canvas>
      </div>
      <div class="d-flex flex-row justify-content-end">
        <span class="mr-2">
          <i class="fas fa-square text-primary"></i> Monday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-info"></i> Tuesday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-orange"></i> Wednesday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-secondary"></i> Thursday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-warning"></i> Friday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-danger"></i> Saturday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-purple"></i> Sunday
        </span>
        <span class="mr-2">
          <i class="fas fa-square text-success"></i> Total
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "WeeklySales",
  props: ["payload"],
  async mounted() {
    this.date = "Month of " + moment().format("MMM YYYY");
  },
  watch: {
    payload() {
      this.rework(this.payload);
      this.setup();
    },
  },
  data: () => ({
    days: [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ],
    date: "",
    datasets: [
      {
        backgroundColor: "#007bff",
        borderColor: "#007bff",
        data: [],
      },
      {
        backgroundColor: "#17A2B8",
        borderColor: "#17A2B8",
        data: [],
      },
      {
        backgroundColor: "#FD7E14",
        borderColor: "#FD7E14",
        data: [],
      },
      {
        backgroundColor: "#6C757D",
        borderColor: "#6C757D",
        data: [],
      },
      {
        backgroundColor: "#FFC107",
        borderColor: "#FFC107",
        data: [],
      },
      {
        backgroundColor: "#DC3545",
        borderColor: "#DC3545",
        data: [],
      },
      {
        backgroundColor: "#6F42C1",
        borderColor: "#6F42C1",
        data: [],
      },
      {
        backgroundColor: "#28A745",
        borderColor: "#28A745",
        data: [],
      },
    ],
    weeks: [],
  }),
  methods: {
    reloadChart(val) {
      this.weeks = [];
      for (let i = 0; i < val; i++) {
        this.weeks.push(`Week ${i + 1}`);
      }
    },
    rework(payload) {
      this.reloadChart(payload.totalWeeks);
      let dayPos = this.days.indexOf(payload.start);
      const data = Object.values(payload.data);
      for (let i = 0; i < dayPos; i++) {
        this.datasets[i].data.push(0);
      }
      let total = 0;
      for (let i = 0; i < data.length; i++) {
        total += data[i];
        if (dayPos !== 7) {
          this.datasets[dayPos].data.push(data[i]);
          dayPos++;
        } else {
          this.datasets[dayPos].data.push(total);
          this.datasets[0].data.push(data[i]);
          total = 0;
          dayPos = 1;
        }
        /*      if (dayPos !== 7 && i === data.length - 1) {
                          this.datasets[dayPos].data.push(total)
                      }*/
      }
      // console.log(this.weeks);
    },
    async setup() {
      await Vue.loadScript(
        "https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"
      );
      await Vue.loadScript(
        "https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"
      );
      var ticksStyle = {
        fontColor: "#495057",
        fontStyle: "bold",
      };
      var mode = "index";
      var intersect = true;
      var $salesChart = $("#weekly-sales-chart");
      // eslint-disable-next-line no-unused-vars
      var salesChart = new Chart($salesChart, {
        type: "bar",
        data: {
          labels: this.weeks,
          datasets: this.datasets,
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            mode: mode,
            intersect: intersect,
          },
          hover: {
            mode: mode,
            intersect: intersect,
          },
          plugins: {
            legend: {
              display: false,
            },
          },
          scales: {
            yAxes: [
              {
                // display: false,
                gridLines: {
                  display: true,
                  lineWidth: "4px",
                  color: "rgba(0, 0, 0, .2)",
                  zeroLineColor: "transparent",
                },
                ticks: $.extend(
                  {
                    beginAtZero: true,

                    // Include a dollar sign in the ticks
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000;
                        value += "k";
                      }

                      return value;
                    },
                  },
                  ticksStyle
                ),
              },
            ],
            xAxes: [
              {
                display: true,
                gridLines: {
                  display: false,
                },
                ticks: ticksStyle,
              },
            ],
          },
        },
      });
    },
  },
};
</script>

<style>
</style>
