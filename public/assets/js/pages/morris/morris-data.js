// Dashboard 1 Morris-chart
$(function () {
    "use strict";
    
    Morris.Bar({
        element: "morris-bar-chart",
        data: window.monthlyData.map(function(item) {
            return {
                y: item.month,
                a: item.income,
                b: item.expense
            };
        }),
        xkey: "y",
        ykeys: ["a", "b"],
        labels: ["Income", "Expense"],
        barColors: ["#01caf1", "#5f76e8"],
        hideHover: "auto",
        gridLineColor: "#eef0f2",
        resize: true,
    });
});