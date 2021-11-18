<template>
    <div class="card" id="print">
        <div class="card-header border-0">
            <div class="d-flex justify-content-center">
                <h3 class="card-title">Monthly/Yearly Interactions Report</h3>

                <v-btn
                    v-if="$route.name === 'reports'"
                    class="hideOnPrint" @click="$router.push({name: 'interactions'})" color="primary" small style="position: absolute; right: 10px">
                    print
                </v-btn>
            </div>
        </div>

        <div class="card-body">
            <div class="text-center">
                <span class="text-bold text-lg">{{ payload.year }}</span>
            </div>
            <div class="d-flex">
                <p class="d-flex flex-column">
          <span class="text-bold text-lg"
          ><v-icon>mdi-account-multiple</v-icon
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
                    <span class="text-muted">Since last year</span>
                </p>
            </div>
            <!-- /.d-flex -->

            <div class="position-relative mb-4">
                <canvas id="monthlyInteraction-chart" height="300"></canvas>
            </div>

            <div class="d-flex flex-row justify-content-end">
        <span class="mr-2">
          <i class="fas fa-square text-primary"></i> Interaction
        </span>
                <!--                <span> <i class="fas fa-square text-gray"></i> Last year </span>-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MonthlyInteraction",
    props: ["payload"],
    async mounted() {
    },
    watch: {
        payload() {
            this.setup();
        },
    },
    methods: {
        async setup() {
            await Vue.loadScript(
                "https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"
            );
            await Vue.loadScript(
                "https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"
            );
            var ticksStyle = {
                fontColor: "#495057",
                fontStyle: "bold",
            };
            var mode = "index";
            var intersect = true;
            var $salesChart = $("#monthlyInteraction-chart");
            // eslint-disable-next-line no-unused-vars
            var salesChart = new Chart($salesChart, {
                type: "bar",
                data: {
                    labels: Object.keys(this.payload.data),
                    datasets: [
                        {
                            backgroundColor: "#007bff",
                            borderColor: "#007bff",
                            data: Object.values(this.payload.data),
                        },
                        /*
                                                              //Last year data
                                                              {
                                                                  backgroundColor: "#ced4da",
                                                                  borderColor: "#ced4da",
                                                                  data: [700, 1700, 2700, 2000, 1800, 1500, 2000],
                                                              },*/
                    ],
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
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [
                            {
                                // display: false,
                                gridLines: {
                                    display: true,
                                    lineWidth: "4px",
                                    color: "rgba(0, 0, 0, .1)",
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

<style scoped>
</style>
